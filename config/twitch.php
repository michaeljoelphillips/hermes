<?php

return [
    'client' => [
        'id' => env('TWITCH_CLIENT_ID'),
        'secret' => env('TWITCH_CLIENT_SECRET'),
    ],
    'redirect_uri' => env('TWITCH_REDIRECT_URI'),
];
