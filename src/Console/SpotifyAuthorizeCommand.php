<?php

declare(strict_types=1);

namespace App\Console;

use SpotifyWebAPI\Session;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SpotifyAuthorizeCommand extends Command
{
    protected static $defaultName = 'spotify:authorize';
    protected string $description = 'Authorize Hermes to access account data';

    private Session $session;

    public function __construct(Session $session)
    {
        parent::__construct();

        $this->session = $session;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $authorizationUrl = $this->session->getAuthorizeUrl([
            'scope' => [
                'playlist-modify-public',
                'playlist-modify-private',
            ],
        ]);

        $output->writeln($authorizationUrl);

        return 0;
    }
}
