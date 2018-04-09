<?php

namespace Tests\Travelr\Cli\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Travelr\Cli\Command\AlbumsToJson as AlbumsToJsonCommand;
use Travelr\Cli\Command\CopyDist;

class CopyDistTest extends TestCase
{
    private const DIST_DIR = '/dist/dir';

    /** @var Filesystem */
    private $fs;

    /** @var OutputInterface */
    private $output;

    /** @var AlbumsToJsonCommand */
    private $command;

    public function setUp(): void
    {
        $this->fs = $this->createMock(Filesystem::class);
        $this->output = $this->createMock(OutputInterface::class);

        $this->command = new CopyDist(self::DIST_DIR, $this->fs);
    }

    public function testItDelegateTheWorkToTheCompiler(): void
    {
        $this->fs
            ->expects($this->once())
            ->method('mirror')
            ->with(self::DIST_DIR, __DIR__.'/dist');

        $this->command->run(__DIR__, $this->output);
    }
}
