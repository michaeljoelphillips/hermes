<?php

namespace App;

use Google_Service_YouTube as Youtube;
use SpotifyWebAPI\SpotifyWebAPI as Spotify;
use App\Spotify\Track;
use App\Youtube\Video;

/**
 * @author Michael Phillips <michael.phillips@realpage.com>
 */
class SpotifyTrackConverter
{
    /** @var Youtube */
    private $youtube;

    /** @var Spotify */
    private $spotify;

    /**
     * @param Youtube $youtube
     * @param Spotify $spotify
     */
    public function __construct(Youtube $youtube, Spotify $spotify)
    {
        $this->youtube = $youtube;
        $this->spotify = $spotify;
    }

    /**
     * Convert a Spotify Track to a Youtube Video.
     *
     * @param Track
     * @return Video
     */
    public function convert(Track $track) : Video
    {
        $track = $this->spotify->getTrack($track->getId());
        $query = $this->buildYoutubeQuery($track);
        $results = $this->searchYoutube($query);

        return $this->getVideoFromResults($results);
    }

    /**
     * Build the Youtube Query.
     *
     * @param object $track
     * @return string
     */
    private function buildYoutubeQuery(object $track) : string
    {
        return sprintf(
            '%s %s',
            $track->artists[0]->name,
            $track->name
        );
    }

    /**
     * Search Youtube with the $query.
     *
     * @param string $query
     * @return object
     */
    private function searchYoutube(string $query) : object
    {
        return $this
            ->youtube
            ->search
            ->listSearch('id,snippet', [
                'q' => $query,
                'maxResults' => 5
            ]);
    }

    /**
     * Dereference the Video ID from the Youtube Results.
     *
     * @param object $results
     * @param Video
     */
    private function getVideoFromResults(object $results) : Video
    {
        $id = $results->items[0]->id->videoId;

        return Video::fromId($id);
    }
}
