<?php

namespace Laragle\Translate;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Lang;

class ImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lanslate:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import localizations to translate.laragle.com';

    /** @var \Illuminate\Foundation\Application  */
    protected $app;

    /** @var \Illuminate\Filesystem\Filesystem  */
    protected $files;    

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Application $app, Filesystem $files)
    {
        parent::__construct();

        $this->app = $app;
        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data = [];

        foreach ($this->files->directories($this->app['path.lang']) as $langPath) {
            $locale = basename($langPath);
            foreach ($this->files->allfiles($langPath) as $file) {
                $info = pathinfo($file);
                $group = $info['filename'];
                $subLangPath = str_replace($langPath . DIRECTORY_SEPARATOR, "", $info['dirname']);
                if ($subLangPath != $langPath) {
                    $group = $subLangPath . "/" . $group;
                }
                $translations = Lang::getLoader()->load($locale, $group);
                if ($translations && is_array($translations)) {
                    foreach (array_dot($translations) as $key => $value) {
                        array_push($data, [
                            'key' => $key,
                            'value' => $value,
                            'locale' => $locale,
                            'group' => $group
                        ]);                        
                    }
                }
            }
        }

        dd($data);
        //return $counter;
    }
}