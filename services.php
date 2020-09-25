<?php

declare(strict_types=1);

use App\BotMan\ConfigParser;
use App\BotMan\Conversation\SpotifyUrlConversation;
use App\BotMan\Middleware\SlackUrlMiddleware;
use App\Console\TwitchSubscriptionCommand;
use App\Controllers\BotManController;
use App\Controllers\TwitchWebhookController;
use App\Middleware\SlackVerificationMiddleware;
use App\Middleware\TwitchVerificationMiddleware;
use App\SpotifyTrackConverter;
use App\Twitch\Client;
use App\Twitch\SerializedTokenStorage;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Slack\SlackDriver;
use BotMan\Drivers\Web\WebDriver;
use Google_Service_YouTube as Youtube;
use GuzzleHttp\Client as Guzzle;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PhpMiddleware\LogHttpMessages\Formatter\ZendDiactorosToArrayMessageFormatter;
use PhpMiddleware\LogHttpMessages\LogMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Slim\App;
use Slim\Factory\AppFactory;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI as Spotify;
use Symfony\Component\Console\Application as Console;

return [
    BotMan::class => static function (ContainerInterface $container): BotMan {
        $config = $container->get('config')['bot'];
        $parser = new ConfigParser($config['messages'], $config['conversations']);

        DriverManager::loadDriver(WebDriver::class);
        DriverManager::loadDriver(SlackDriver::class);

        $botMan = BotManFactory::create($config['drivers']);

        $botMan->setContainer($container);
        $botMan->middleware->received(new SlackUrlMiddleware());
        $parser->configure($botMan);

        return $botMan;
    },
    SpotifyUrlConversation::class => static function (ContainerInterface $container): SpotifyUrlConversation {
        return new SpotifyUrlConversation(
            new SpotifyTrackConverter(
                $container->get(Youtube::class),
                $container->get(Spotify::class)
            )
        );
    },
    Youtube::class => static function (ContainerInterface $container): Youtube {
        $config = $container->get('config');

        $googleClient = new Google_Client($config['google']['client']);
        $googleClient->setDeveloperKey($config['google']['access_token']);

        return new Youtube($googleClient);
    },
    Spotify::class => static function (ContainerInterface $container): Spotify {
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
    BotManController::class => static function (ContainerInterface $container): BotManController {
        return new BotManController($container->get(BotMan::class));
    },
    TwitchWebhookController::class => static function (ContainerInterface $container): TwitchWebhookController {
        return new TwitchWebhookController($container->get(BotMan::class), new NullLogger());
    },
    App::class => static function (ContainerInterface $container): App {
        AppFactory::setContainer($container);
        $app = AppFactory::create();

        $app->addRoutingMiddleware();
        $app->addBodyParsingMiddleware();
        $app->addErrorMiddleware(true, true, true);
        $app->add(new TwitchVerificationMiddleware());
        $app->add(new SlackVerificationMiddleware());

        $logger           = $container->get(LoggerInterface::class);
        $messageFormatter = new ZendDiactorosToArrayMessageFormatter();

        $app->add(new LogMiddleware(
            $messageFormatter,
            $messageFormatter,
            $logger
        ));

        $app->post('/botman', BotManController::class . ':chat');
        $app->post('/twitch/webhook', TwitchWebhookController::class);

        return $app;
    },
    LoggerInterface::class => static function (ContainerInterface $container): LoggerInterface {
        $config = $container->get('config');

        $handler = $config['debug'] ?
            new StreamHandler($config['storage']['path'] . '/hermes.log') :
            new NullHandler();

        return new Logger('default', [$handler]);
    },
];
