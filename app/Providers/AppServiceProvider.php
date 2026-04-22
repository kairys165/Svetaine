<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // 👈 PRIDĖK ŠITĄ

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // 👇 PRIDĖK ŠITĄ BLOKĄ
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Share product categories with all views
        View::composer('partials.navbar', function ($view) {
            $view->with('navProductCategories',
                Category::where('type', 'product')
                    ->where('is_active', true)
                    ->whereNotIn('slug', ['mityba', 'sportas'])
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get()
            );
        });
    }
}