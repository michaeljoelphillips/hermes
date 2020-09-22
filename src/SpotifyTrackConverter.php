<?php

declare(strict_types=1);

namespace App;

use App\Spotify\Track;
use App\Youtube\Video;
use Google_Service_YouTube as Youtube;
use SpotifyWebAPI\SpotifyWebAPI as Spotify;

use function sprintf;

class SpotifyTrackConverter
{
    private Youtube $youtube;

    private Spotify $spotify;

    public function __construct(Youtube $youtube, Spotify $spotify)
    {
        $this->youtube = $youtube;
        $this->spotify = $spotify;
    }

    public function convert(Track $track): Video
    {
        $track   = $this->spotify->getTrack($track->getId());
        $query   = $this->buildYoutubeQuery($track);
        $results = $this->searchYoutube($query);

        return $this->getVideoFromResults($results);
    }

    private function buildYoutubeQuery(object $track): string
    {
        return sprintf(
            '%s %s',
            $track->artists[0]->name,
            $track->name
        );
    }

    private function searchYoutube(string $query): object
    {
        return $this
            ->youtube
            ->search
            ->listSearch('id,snippet', [
                'q' => $query,
                'maxResults' => 5,
            ]);
    }

    private function getVideoFromResults(object $results): Video
    {
        $id = $results->items[0]->id->videoId;

        return Video::fromId($id);
    }
}
