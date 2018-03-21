<?php

namespace DockerForProduction;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Slack\SlackDriver;
use BotMan\BotMan\Cache\SymfonyCache;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

/**
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
class App
{
    public function start() : void
    {
        $this->loadEnvironment();
        $botman = $this->createBotMan();

        $botman->hears('Can you hear me\?', function (BotMan $bot) {
            $bot->reply('Loud and clear!');
        });

        $botman->hears('Say hi, BotMan!', function (BotMan $bot) {
            $bot->reply('Hi, Dallas PHP!');
        });

        $botman->listen();
    }

    private function loadEnvironment()
    {
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/../.env');
    }

    private function createBotMan() : BotMan
    {
        DriverManager::loadDriver(SlackDriver::class);

        $config = [
            'slack' => [
                'token' => getenv('SLACK_TOKEN')
            ]
        ];

        $adapter = new FilesystemAdapter();
        $botman = BotManFactory::create($config, new SymfonyCache($adapter));

        return $botman;
    }
}
