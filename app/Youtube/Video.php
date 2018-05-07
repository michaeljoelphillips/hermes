<?php

namespace App\Youtube;

/**
 * @author Michael Phillips <michael.phillips@realpage.com>
 */
class Video
{
    private const YOUTUBE_URL = 'https://youtube.com/watch?v=%s';

    /** @var string */
    private $id;

    private function __construct()
    {
    }

    /**
     * Create a Video from an ID.
     *
     * @param string
     * @return self
     */
    public static function fromId(string $id) : self
    {
        $video = new self();

        $video->id = $id;

        return $video;
    }

    /**
     * Get the Youtube URL.
     *
     * @return string
     */
    public function getUrl() : string
    {
        return sprintf(self::YOUTUBE_URL, $this->id);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getUrl();
    }
}
