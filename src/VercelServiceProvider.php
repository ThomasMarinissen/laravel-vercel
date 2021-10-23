<?php

namespace ThomasMarinissen\LaravelVercel;

use Illuminate\Support\ServiceProvider;
use ThomasMarinissen\LaravelVercel\Commands\InstallCommands;

/**
 * Class VercelServiceProvider
 */
class VercelServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // register the vercel config file
        $this->mergeConfigFrom(__DIR__.'/../config/vercel.php', 'vercel');

        // register te install command
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommands::class
            ]);
        }
    }
}
