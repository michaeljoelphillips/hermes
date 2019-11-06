<?php

declare(strict_types=1);

namespace App\Providers;

use App\Console\TwitchSubscriptionCommand;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(TwitchSubscriptionCommand::class, function ($app) {
            return new TwitchSubscriptionCommand(
                new Client([
                    'headers' => [
                        'Client-ID' => config('twitch.client.id'),
                    ],
                ])
            );
        });
    }
}
