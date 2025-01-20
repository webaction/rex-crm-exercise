<?php

namespace Modules\Core\Contacts\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Contacts\Console\Commands\ContactCreateCommand;

class ContactsServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ContactCreateCommand::class,
            ]);
        }
    }
}
