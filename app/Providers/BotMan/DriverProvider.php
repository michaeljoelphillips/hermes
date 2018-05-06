<?php

namespace App\Providers\BotMan;

use Illuminate\Support\ServiceProvider;
use BotMan\Drivers\Slack\SlackDriver;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Web\WebDriver;

/**
 * @author Michael Phillips <michael.phillips@realpage.com>
 */
class DriverProvider extends ServiceProvider
{
    /**
     * The drivers that should be loaded to
     * use with BotMan
     *
     * @var array
     */
    protected $drivers = [
        WebDriver::class,
        SlackDriver::class,
    ];

    /**
     * @return void
     */
    public function boot()
    {
        foreach ($this->drivers as $driver) {
            DriverManager::loadDriver($driver);
        }
    }
}
