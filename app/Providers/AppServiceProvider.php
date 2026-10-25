<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\CartService;
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
    public function boot(CartService $cartService): void
    {
        // Share cart count with all views
        View::composer('*', function ($view) use ($cartService) {
            try {
                $view->with('globalCartCount', $cartService->getCount());
            } catch (\Throwable $e) {
                $view->with('globalCartCount', 0);
            }
        });
    }
}
