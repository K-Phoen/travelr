<?php

namespace Tests\Travelr;

use PHPUnit\Framework\TestCase;
use Travelr\Album;
use Travelr\Coordinates;
use Travelr\Image;

class AlbumTest extends TestCase
{
    public function testItRepresentsAnAlbum(): void
    {
        $cover = Image::fromPath('/webroot/data/album-name/cover.jpeg');
        $images = [$cover];
        $album = new Album('/webroot/data/album-name', 'Title', 'Description', new Coordinates(42.2, 24.4), $cover, $images);

        $this->assertSame('/webroot/data/album-name', $album->path());
        $this->assertSame('album-name', $album->slug());
        $this->assertSame('Title', $album->title());
        $this->assertSame('Description', $album->description());
        $this->assertSame(42.2, $album->latitude());
        $this->assertSame(24.4, $album->longitude());
        $this->assertSame($images, $album->images());
        $this->assertSame($cover, $album->cover());
    }
}
