<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';

(new Dotenv())
    ->usePutenv(true)
    ->load(__DIR__ . '/.env');

return (new ContainerBuilder())
    ->addDefinitions(__DIR__ . '/config.php')
    ->addDefinitions(__DIR__ . '/services.php')
    ->build();
