<?php

namespace Modules\Core\Contacts\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Contacts\Console\ContactCreateCommand;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Modules\Core\Contacts\Exceptions\Handler as PackageExceptionHandler;

class ContactsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/contacts.php', 'contacts');
        $this->app->singleton(ExceptionHandler::class, PackageExceptionHandler::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/contacts.php' => config_path('contacts.php'),
        ]);
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ContactCreateCommand::class,
            ]);
        }
    }
}
