<?php

namespace Tests\Travelr\Cli\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Travelr\Cli\Command\AlbumsToJson as AlbumsToJsonCommand;
use Travelr\Compiler\AlbumsToJson as AlbumsToJsonCompiler;

class AlbumsToJsonTest extends TestCase
{
    /** @var Filesystem */
    private $compiler;

    /** @var OutputInterface */
    private $output;

    /** @var AlbumsToJsonCommand */
    private $command;

    public function setUp(): void
    {
        $this->compiler = $this->createMock(AlbumsToJsonCompiler::class);
        $this->output = $this->createMock(OutputInterface::class);

        $this->command = new AlbumsToJsonCommand($this->compiler);
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
