<?php

declare(strict_types=1);

namespace Test\Unit\Youtube;

use App\Youtube\Video;
use PHPUnit\Framework\TestCase;

class VideoTest extends TestCase
{
    public function testGetUrl(): void
    {
        $subject = Video::fromId('_c1NJQ0UP_Q');

        $this->assertEquals('https://youtube.com/watch?v=_c1NJQ0UP_Q', $subject->getUrl());
        $this->assertEquals('https://youtube.com/watch?v=_c1NJQ0UP_Q', (string) $subject);
    }
}
