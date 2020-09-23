<?php

declare(strict_types=1);

return [
    'config' => [
        'twitch' => [
            'client' => [
                'id' => getenv('TWITCH_CLIENT_ID'),
                'secret' => getenv('TWITCH_CLIENT_SECRET'),
            ],
            'redirect_uri' => getenv('TWITCH_REDIRECT_URI'),
        ],
        'google' => [
            'client' => ['application_name' => 'Tardibot'],
            'access_token' => getenv('GOOGLE_ACCESS_TOKEN'),
        ],
        'spotify' => [
            'client_id' => getenv('SPOTIFY_CLIENT_ID'),
            'client_secret' => getenv('SPOTIFY_CLIENT_SECRET'),
        ],
        'bot' => [
            'drivers' => [
                'slack' => [
                    'token' => getenv('SLACK_TOKEN'),
                ],
            ],
            'messages' => [
                'Hi' => 'Hello',
                'Can you hear me\?' => 'Loud and clear!',
                'Test' => 'I\'m working!',
            ],
            'conversations' => ['^https://open.spotify.com/track.*$' => 'App\BotMan\Conversation\SpotifyUrlConversation@convertUrl'],
        ],
        'storage' => [
            'path' => getenv('STORAGE_PATH'),
        ],
    ],
];
