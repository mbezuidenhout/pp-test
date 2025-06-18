<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('autoload.options', function () {
            return \App\Models\Option::where('autoload', true)->pluck('value', 'name')->toArray();
        });
        // Use in Controllers with $options = app('autoload.options');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Added to prevent errors on console commands
        if (app()->runningInConsole() || app()->runningUnitTests()) {
            return;
        }
        $autoloadOptions = \App\Models\Option::where('autoload', true)->pluck('value', 'name')->toArray();
        View::share('autoloadOptions', $autoloadOptions);
    }
}
