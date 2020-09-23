<?php

declare(strict_types=1);

namespace App\Console;

use App\Twitch\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TwitchSubscriptionCommand extends Command
{
    protected static $defaultName = 'twitch:stream:subscribe';
    protected string $description = 'Subscribe to stream notifications for a user';

    private Client $client;
    private string $webhook;

    public function __construct(Client $client, string $webhook)
    {
        parent::__construct();

        $this->client  = $client;
        $this->webhook = $webhook;
    }

    public function configure(): void
    {
        $this
            ->setDescription('Subscribe to a notification for a Twitch user')
            ->addArgument('user', InputArgument::REQUIRED, 'A Twitch User');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->client->subscribe($input->getArgument('user'), $this->webhook);

        return 0;
    }
}
