<?php

namespace Laragle\Translate;

use Illuminate\Console\Command;

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
    protected $description = 'Import localizations to lanslate.com';    

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
        dd('import');
    }
}