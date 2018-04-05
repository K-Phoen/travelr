<?php

namespace Tests\Travelr\Compiler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Travelr\Compiler\AlbumsMapView;

class AlbumsMapViewTest extends TestCase
{
    /** @var \Twig_Environment */
    private $twig;

    /** @var Filesystem */
    private $fs;

    /** @var AlbumsMapView */
    private $compiler;

    public function setUp(): void
    {
        $this->twig = $this->createMock(\Twig_Environment::class);
        $this->fs = $this->createMock(Filesystem::class);

        $this->compiler = new AlbumsMapView($this->twig, $this->fs);
    }

    public function testItRendersTheView(): void
    {
        $this->twig->expects($this->once())
            ->method('render')
            ->with('index.html.twig')
            ->willReturn('content');

        $this->fs->expects($this->once())
            ->method('dumpFile')
            ->with('/webroot/index.html', 'content');

        $this->compiler->compile('/webroot');
    }
}
