## Tardibot

Tardibot is a Chat Bot written in [Laravel](https://laravel.com/) with the [Botman.io](https://botman.io) framework.

## Using Tardibot

Tardibot will respond to chat messages that are defined in `config/bot.php`:

```php
return [
    'messages' => [
        'Hi' => 'Hello',
    ],
];
```

Tardibot can also be configured do perform more advanced tasks with [Conversations](https://botman.io/2.0/conversations):

```php
return [
    'conversations' => [
        '^https://open.spotify.com/track.*$' => 'App\BotMan\Conversation\SpotifyUrlConversation@convertUrl'
    ],
];
```

## Features

Some things that Tardibot can do:

* Respond to Spotify URLs with Youtube Video Urls
* Respond to RegEx Phrases

## Contributing

If there is something you'd like add to Tardibot, submit a Pull Request!
