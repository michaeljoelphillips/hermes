<?php

namespace App\Providers\BotMan;

use Illuminate\Support\ServiceProvider;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\BotMan;

/**
 * @author Michael Phillips <michael.phillips@realpage.com>
 */
class BotManProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(BotMan::class, function ($app) {
            $factory = $app->make(BotManFactory::class);

            return BotManFactory::create(config('bot'));
        });
    }
}
