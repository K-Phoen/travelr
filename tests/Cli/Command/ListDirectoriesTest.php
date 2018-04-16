<?php

declare(strict_types=1);

namespace Tests\Travelr\Cli\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Travelr\Cli\Command\ListDirectories;
use Travelr\Coordinates;
use Travelr\Directory;
use Travelr\DirectoryConfig;
use Travelr\GlobalConfig;
use Travelr\Repository\Directories;

class ListDirectoriesTest extends TestCase
{
    /** @var Filesystem */
    private $directories;

    /** @var OutputInterface */
    private $output;

    /** @var ListDirectories */
    private $command;

    public function setUp(): void
    {
        $this->directories = $this->createMock(Directories::class);
        $this->output = new NullOutput();

        $this->command = new ListDirectories($this->directories);
    }

    public function testItRuns(): void
    {
        $this->directories->expects($this->once())
            ->method('findAll')
            ->with(__DIR__, $this->isInstanceOf(GlobalConfig::class))
            ->willReturn([
                new Directory('path', new DirectoryConfig('title', 'desc', new Coordinates(0, 0), 'cover.jpg'), []),
            ]);

        $this->command->run($this->output, __DIR__);
    }
}
