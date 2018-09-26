<?php

namespace Laragle\Translate;

use Illuminate\Console\Command;

class ExportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lanslate:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export localizations from the database';    

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
        dd('export');
    }
}