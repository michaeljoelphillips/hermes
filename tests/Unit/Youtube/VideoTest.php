<?php

namespace Test\Unit\Youtube;

use PHPUnit\Framework\TestCase;
use App\Youtube\Video;

/**
 * @author Michael Phillips <michael.phillips@realpage.com>
 */
class VideoTest extends TestCase
{
    public function testGetUrl()
    {
        $subject = Video::fromId('_c1NJQ0UP_Q');

        $this->assertEquals('https://youtube.com/watch?v=_c1NJQ0UP_Q', $subject->getUrl());
        $this->assertEquals('https://youtube.com/watch?v=_c1NJQ0UP_Q', (string) $subject);
    }
}
