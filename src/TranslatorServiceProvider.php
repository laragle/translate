<?php

namespace DarwinLuague\Translator;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TranslatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/dluaguetranslator.php' => config_path('dluaguetranslator.php')
        ], 'config');

        $this->registerRoutes();
        $this->registerViews();
        $this->defineAssetPublishing();
    }

    /**
     * Register package routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group([
            'prefix' => config('dluaguetranslator.uri', 'translator'),
            'namespace' => 'DarwinLuague\Translator\Http\Controllers',
            'middleware' => config('dluaguetranslator.middleware', 'web'),
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
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'translator');
    }

    /**
     * Define the asset publishing configuration.
     *
     * @return void
     */
    public function defineAssetPublishing()
    {
        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/dluaguetranslator'),
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
            __DIR__.'/../config/dluaguetranslator.php', 'dluaguetranslator'
        );
    }
}