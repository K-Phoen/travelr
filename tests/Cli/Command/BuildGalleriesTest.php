<?php

declare(strict_types=1);

namespace Tests\Travelr\Cli\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;
use Travelr\Album;
use Travelr\Cli\Command\AlbumsToJson as AlbumsToJsonCommand;
use Travelr\Cli\Command\BuildGalleries;
use Travelr\Compiler\GalleryView;
use Travelr\Coordinates;
use Travelr\Image;
use Travelr\Repository\Albums;

class BuildGalleriesTest extends TestCase
{
    /** @var Albums */
    private $albumsRepo;

    /** @var GalleryView */
    private $galleryCompiler;

    /** @var OutputInterface */
    private $output;

    /** @var AlbumsToJsonCommand */
    private $command;

    public function setUp(): void
    {
        $this->albumsRepo = $this->createMock(Albums::class);
        $this->galleryCompiler = $this->createMock(GalleryView::class);
        $this->output = $this->createMock(OutputInterface::class);

        $this->command = new BuildGalleries($this->albumsRepo, $this->galleryCompiler);
    }

    public function testItDelegateTheWorkToTheCompiler(): void
    {
        $firstAlbum = new Album('/webroot/data/album-name', 'Title', '', new Coordinates(42.2, 24.4), Image::fromPath('/webroot/data/album-name/cover.jpeg'), []);
        $secondAlbum = new Album('/webroot/data/second-album-name', 'Title', '', new Coordinates(42.2, 24.4), Image::fromPath('/webroot/data/second-album-name/cover.jpeg'), []);

        $this->albumsRepo
            ->method('findAll')
            ->with(__DIR__)
            ->willReturn([
                $firstAlbum,
                $secondAlbum,
            ]);

        $this->galleryCompiler
            ->expects($this->at(0))
            ->method('compile')
            ->with($firstAlbum);

        $this->galleryCompiler
            ->expects($this->at(1))
            ->method('compile')
            ->with($secondAlbum);

        $this->command->run(__DIR__, $this->output);
    }
}
