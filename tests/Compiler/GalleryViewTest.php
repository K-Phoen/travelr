<?php

declare(strict_types=1);

namespace Tests\Travelr\Compiler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Travelr\Album;
use Travelr\Compiler\GalleryView;
use Travelr\Coordinates;
use Travelr\Image;

class GalleryViewTest extends TestCase
{
    /** @var \Twig_Environment */
    private $twig;

    /** @var Filesystem */
    private $fs;

    /** @var GalleryView */
    private $compiler;

    public function setUp(): void
    {
        $this->twig = $this->createMock(\Twig_Environment::class);
        $this->fs = $this->createMock(Filesystem::class);

        $this->compiler = new GalleryView($this->twig, $this->fs);
    }

    public function testItRendersTheView(): void
    {
        $album = new Album('/album/path', 'Title', '', new Coordinates(0, 0), Image::fromPath('/img/path'), []);

        $this->twig->expects($this->once())
            ->method('render')
            ->with('album.html.twig')
            ->willReturn('content');

        $this->fs->expects($this->once())
            ->method('dumpFile')
            ->with('/album/path/index.html', 'content');

        $this->compiler->compile($album);
    }
}
