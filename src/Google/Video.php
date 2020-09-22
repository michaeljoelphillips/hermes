<?php

declare(strict_types=1);

namespace App\Youtube;

use function sprintf;

class Video
{
    private const YOUTUBE_URL = 'https://youtube.com/watch?v=%s';

    private string $id;

    private function __construct()
    {
    }

    public static function fromId(string $id): self
    {
        $video = new self();

        $video->id = $id;

        return $video;
    }

    public function getUrl(): string
    {
        return sprintf(self::YOUTUBE_URL, $this->id);
    }

    public function __toString(): string
    {
        return $this->getUrl();
    }
}
