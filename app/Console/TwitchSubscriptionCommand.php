<?php

declare(strict_types=1);

namespace App\Console;

use GuzzleHttp\Client;
use Illuminate\Console\Command;

class TwitchSubscriptionCommand extends Command
{
    protected $signature = 'twitch:stream:subscribe {user}';
    protected $description = 'Subscribe to stream notifications for a user';

    private $client;

    public function __construct(Client $client)
    {
        parent::__construct();

        $this->client = $client;
    }

    public function handle(): void
    {
        $this->subscribe($this->argument('user'));
    }

    public function subscribe(string $user): void
    {
        $user = $this->getUserIdByName($user);

        $response = $this->client->request('POST', 'https://api.twitch.tv/helix/webhooks/hub', [
            'json' => [
                'hub.lease_seconds' => 86400,
                'hub.mode' => 'subscribe',
                'hub.callback' => secure_url('/botman/twitch/webhook'),
                'hub.topic' => "https://api.twitch.tv/helix/streams?user_id=$user",
            ],
        ]);
    }

    private function getUserIdByName(string $user): string
    {
        $response = $this->client->get("https://api.twitch.tv/helix/users?login=$user");
        $response = json_decode((string) $response->getBody());

        return array_pop($response->data)->id;
    }
}
