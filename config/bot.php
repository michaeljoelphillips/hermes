<?php

return [
    'drivers' => [
        'slack' => [
            'token' => env('SLACK_TOKEN')
        ]
    ],

    'messages' => [
        'Hi' => 'Hello',
        'Can you hear me\?' => 'Loud and clear!',
        'Test' => 'I\'m working!',
    ]
];
