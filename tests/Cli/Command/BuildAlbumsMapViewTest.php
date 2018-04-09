<?php

namespace Tests\Travelr\Cli\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Travelr\Cli\Command\AlbumsToJson as AlbumsToJsonCommand;
use Travelr\Cli\Command\BuildAlbumsMapView;
use Travelr\Compiler\AlbumsMapView;

class BuildAlbumsMapViewTest extends TestCase
{
    /** @var Filesystem */
    private $compiler;

    /** @var OutputInterface */
    private $output;

    /** @var AlbumsToJsonCommand */
    private $command;

    public function setUp(): void
    {
        $this->compiler = $this->createMock(AlbumsMapView::class);
        $this->output = $this->createMock(OutputInterface::class);

        $this->command = new BuildAlbumsMapView($this->compiler);
    }

    public function testItDelegateTheWorkToTheCompiler(): void
    {
        $this->compiler
            ->expects($this->once())
            ->method('compile')
            ->with(__DIR__);

        $this->command->run(__DIR__, $this->output);
    }
}
