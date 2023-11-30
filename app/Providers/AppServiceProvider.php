<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Factory::guessFactoryNamesUsing(function (string $model) {
            return 'Database\\Factories\\' . class_basename($model) . 'Factory';
        });
    }
}
