<?php

namespace Laragle\Translate\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laragle:sync-translations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import/Export localizations from https://translate.laragle.com';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Artisan::call('laragle:push-translations');
        Artisan::call('laragle:pull-translations');

        $this->info('Success!');
    }
}