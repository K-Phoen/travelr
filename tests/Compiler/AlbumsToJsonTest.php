<?php

declare(strict_types=1);

namespace Tests\Travelr\Compiler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Travelr\Album;
use Travelr\Compiler\AlbumsToJson;
use Travelr\Coordinates;
use Travelr\GlobalConfig;
use Travelr\Image;
use Travelr\Repository\Albums;

class AlbumsToJsonTest extends TestCase
{
    /** @var Filesystem */
    private $albumsRepo;

    /** @var Filesystem */
    private $fs;

    /** @var AlbumsToJson */
    private $compiler;

    public function setUp(): void
    {
        $this->albumsRepo = $this->createMock(Albums::class);
        $this->fs = $this->createMock(Filesystem::class);

        $this->compiler = new AlbumsToJson($this->albumsRepo, $this->fs);
    }

    public function testItGeneratesAJsonFileEvenIfThereAreNoAlbums(): void
    {
        $config = GlobalConfig::default();

        $this->albumsRepo
            ->method('findAll')
            ->with('/webroot', $config)
            ->willReturn([]);

        $this->fs->expects($this->once())
            ->method('dumpFile')
            ->with('/webroot/albums.json', '[]');

        $this->compiler->compile('/webroot', $config);
    }

    public function testItCorrectlyDumpsAlbums(): void
    {
        $config = GlobalConfig::default();

        $this->albumsRepo
            ->method('findAll')
            ->with('/webroot', $config)
            ->willReturn([
                new Album('/webroot/data/album-name', 'Title', '', new Coordinates(42.2, 24.4), Image::fromPath('/webroot/data/album-name/cover.jpeg'), []),
            ]);

        $this->fs->expects($this->once())
            ->method('dumpFile')
            ->with('/webroot/albums.json', json_encode([
                [
                    'slug' => 'album-name',
                    'title' => 'Title',
                    'description' => '',
                    'thumbnail' => 'data/album-name/cover.jpeg',
                    'latitude' => 42.2,
                    'longitude' => 24.4,
                ],
            ], JSON_PRETTY_PRINT));

        $this->compiler->compile('/webroot', $config);
    }
}
