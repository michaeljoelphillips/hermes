<?php

namespace App\Providers\BotMan;

use Illuminate\Support\ServiceProvider;
use App\BotMan\ConfigParser;

/**
 * @author Michael Phillips <michael.phillips@realpage.com>
 */
class ConfigParserProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ConfigParser::class, function ($app) {
            return new ConfigParser(config('bot.messages'), config('bot.conversations'));
        });
    }
}
