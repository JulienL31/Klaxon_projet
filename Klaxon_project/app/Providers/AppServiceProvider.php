<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Tu peux enregistrer ici des bindings, singletons, etc.
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Forcer les templates de pagination à utiliser Bootstrap 5
        Paginator::useBootstrapFive();

        // Si tu préfères Bootstrap 4 :
        // Paginator::useBootstrapFour();
    }
}
