<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('forms', function (Request $request) {
            return Limit::perMinute(8)->by($request->ip());
        });

        View::composer(['layouts.site', 'pages.*', 'partials.*', 'errors.*'], function ($view) {
            if (($view->getData()['site'] ?? null) instanceof Setting) {
                return;
            }

            try {
                $view->with('site', Setting::current());
            } catch (\Throwable) {
                $view->with('site', new Setting([
                    'site_name' => 'Islam Textile',
                    'tagline' => 'Weaving Tradition, Ensuring Quality',
                ]));
            }
        });
    }
}
