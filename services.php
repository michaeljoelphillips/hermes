<?php

declare(strict_types=1);

use App\BotMan\Conversation\SpotifyUrlConversation;
use App\BotMan\Middleware\SlackUrlMiddleware;
use App\Console\TwitchSubscriptionCommand;
use App\Twitch\Client;
use App\Twitch\SerializedTokenStorage;
use BotMan\BotMan\BotMan;
use Google_Service_YouTube as Youtube;
use GuzzleHttp\Client as Guzzle;
use Psr\Container\ContainerInterface;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI as Spotify;
use Symfony\Component\Console\Application as Console;

return [
    BotMan::class => static function (ContainerInterface $container): BotMan {
        $config = $container->get('config')['bot'];
        $parser = new ConfigParser($config['messages'], $config['conversations']);
        $botMan = BotManFactory::create($config['drivers']);

        $botMan->middleware->recieved(new SlackUrlMiddleware());
        $parser->configure($botMan);

        return $botMan;
    },
    SpotifyUrlConversation::class => static function (ContainerInterface $container): SpotifyUrlConversation {
        return new SpotifyUrlConversation($youtube, $spotify);
    },
    Youtube::class => static function (ContainerInterface $container): Youtube {
        $config = $container->get('config');

        $googleClient = new Google_Client($config['google']['client']);
        $googleClient->setDeveloperKey($config['google']['access_token']);

        return Youtube($googleClient);
    },
    Spotify::class => static function (ContainterInterface $container): Spotify {
        $config  = $container->get('config')['spotify'];
        $session = new Session($config['client_id'], $config['client_secret']);

        $session->requestCredentialsToken();

        return new Spotify([], $session);
    },
    Console::class => static function (ContainerInterface $container): Console {
        $console = new Console();

        $console->add($container->get(TwitchSubscriptionCommand::class));

        return $console;
    },
    TwitchSubscriptionCommand::class => static function (ContainerInterface $container): TwitchSubscriptionCommand {
        $config = $container->get('config');

        $client = new Client(
            new Guzzle(),
            new SerializedTokenStorage($config['storage']['path']),
            $config['twitch']['client']['id'],
            $config['twitch']['client']['secret']
        );

        return new TwitchSubscriptionCommand($client, $config['twitch']['redirect_uri']);
    },
];
