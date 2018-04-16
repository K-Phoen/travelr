<?php

declare(strict_types=1);

namespace Tests\Travelr\Repository;

use PHPUnit\Framework\TestCase;
use Travelr\Album;
use Travelr\Coordinates;
use Travelr\Directory;
use Travelr\DirectoryConfig;
use Travelr\GlobalConfig;
use Travelr\Image;
use Travelr\Repository\Albums;
use Travelr\Repository\Directories;
use Travelr\Thumbnail\Thumbnailer;

class AlbumsTest extends TestCase
{
    /** @var Directories */
    private $directoriesRepo;

    /** @var Thumbnailer */
    private $thumbnailer;

    /** @var Directories */
    private $repo;

    public function setUp(): void
    {
        $this->directoriesRepo = $this->createMock(Directories::class);
        $this->thumbnailer = $this->createMock(Thumbnailer::class);

        $this->repo = new Albums($this->directoriesRepo, $this->thumbnailer);
    }

    public function testItTurnsDirectoriesIntoAlbums(): void
    {
        $thumbCover = Image::fromPath('web-root/data/dir-path/cover.jpg', 'web-root/data/dir-path/travelr/thumb_cover.jpg');
        $image001 = Image::fromPath('web-root/data/dir-path/image001.jpg', 'web-root/data/dir-path/travelr/thumb_image001.jpg');

        $globalConfig = GlobalConfig::default();
        $directoryConfig = new DirectoryConfig('title', 'desc', new Coordinates(42, 24), 'cover.jpg');
        $directory = new Directory('web-root/data/dir-path', $directoryConfig, [
            'cover.jpg',
            'image_001.jpg',
        ]);

        $this->directoriesRepo->expects($this->once())
            ->method('findAll')
            ->with('web-root', $globalConfig)
            ->willReturn([$directory]);

        $this->thumbnailer->expects($this->at(0))
            ->method('forImage')
            ->willReturn($thumbCover);

        $this->thumbnailer->expects($this->at(1))
            ->method('forImage')
            ->willReturn($thumbCover);

        $this->thumbnailer->expects($this->at(2))
            ->method('forImage')
            ->willReturn($image001);

        $albums = iterator_to_array($this->repo->findAll('web-root', $globalConfig));

        $this->assertCount(1, $albums);
        $this->assertInstanceOf(Album::class, $albums[0]);

        /** @var Album $album */
        $album = $albums[0];

        $this->assertSame('web-root/data/dir-path', $album->path());
        $this->assertSame('dir-path', $album->slug());
        $this->assertSame('title', $album->title());
        $this->assertSame('desc', $album->description());
        $this->assertSame($thumbCover, $album->cover());
        $this->assertSame(42.0, $album->latitude());
        $this->assertSame(24.0, $album->longitude());

        $images = iterator_to_array($album->images());

        $this->assertCount(2, $images);
        $this->assertSame($thumbCover, $images[0]);
        $this->assertSame($image001, $images[1]);
    }
}
