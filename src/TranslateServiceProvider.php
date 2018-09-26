<?php

namespace Laragle\Translate;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laragle\Translate\ExportCommand;
use Laragle\Translate\ImportCommand;

class TranslateServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/translate.php' => config_path('laragle/translate.php')
        ], 'config');

        $this->registerRoutes();
        $this->registerViews();
        $this->defineAssetPublishing();
        $this->registerCommands();
    }

    /**
     * Register package commands.
     * 
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ExportCommand::class,
                ImportCommand::class
            ]);
        }
    }

    /**
     * Register package routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group([
            'prefix' => config('laragle.translate.uri', 'translate'),
            'namespace' => 'Laragle\Translate\Http\Controllers',
            'middleware' => config('translate.middleware', 'web'),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    /**
     * Register package views.
     *
     * @return void
     */
    protected function registerViews()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'translate');
    }

    /**
     * Define the asset publishing configuration.
     *
     * @return void
     */
    public function defineAssetPublishing()
    {
        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/laragle/translate'),
        ], 'assets');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->configure();        
    }

    /**
     * Setup package configuration.
     *
     * @return void
     */
    protected function configure()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/translate.php', 'laragle.translate'
        );
    }
}