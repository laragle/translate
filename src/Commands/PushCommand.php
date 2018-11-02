<?php

namespace Laragle\Translate\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Lang;

class PushCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laragle:push-translations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push translations to https://translate.laragle.com';

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
            $data[$locale] = [];
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
                        if (! is_array($value)) {
                            array_push($data[$locale], [
                                'key' => $key,
                                'value' => $value,
                                'group' => $group
                            ]);
                        }                        
                    }
                }
            }
        }

        $client = new Client([
            'base_uri' => config('laragle.translate.api_url')
        ]);

        try {
            $response = $client->post('oauth/token', [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => config('laragle.translate.app_id'),
                    'client_secret' => config('laragle.translate.app_secret'),
                    'scope' => 'push-translations'
                ]
            ]);
        } catch (RequestException $e) {
            $message = json_decode($e->getResponse()->getBody()->getContents(), true)['message'];
            $this->error($message);
            $this->error('Kindly check the following environment variables in your .env file.');
            $this->error('LARAGLE_TRANSLATE_APP_ID');
            $this->error('LARAGLE_TRANSLATE_APP_SECRET');
            return;
        }

        $access_token = json_decode((string) $response->getBody(), true)['access_token'];

        try {
            $client->post('client/'.config('laragle.translate.app_id').'/language/translations/import', [
                'form_params' => [
                    'default_language_code' => config('app.locale'),
                    'translations' => $data
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$access_token
                ]
            ]);
            
            $this->info('Success!');
        } catch (RequestException $e) {
            $message = json_decode($e->getResponse()->getBody()->getContents(), true)['message'];
            $this->error($message);            
        }        
    }
}