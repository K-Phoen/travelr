<?php

declare(strict_types=1);

namespace Tests\Travelr\Cli\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Travelr\Cli\Command\AlbumsToJson as AlbumsToJsonCommand;
use Travelr\Compiler\AlbumsToJson as AlbumsToJsonCompiler;
use Travelr\Config\GlobalParser as ConfigParser;
use Travelr\GlobalConfig;

class AlbumsToJsonTest extends TestCase
{
    /** @var Filesystem */
    private $compiler;

    /** @var OutputInterface */
    private $output;

    /** @var ConfigParser */
    private $configParser;

    /** @var AlbumsToJsonCommand */
    private $command;

    public function setUp(): void
    {
        $this->compiler = $this->createMock(AlbumsToJsonCompiler::class);
        $this->output = $this->createMock(OutputInterface::class);
        $this->configParser = $this->createMock(ConfigParser::class);

        $this->command = new AlbumsToJsonCommand($this->compiler, $this->configParser);
    }

    public function testItDelegateTheWorkToTheCompiler(): void
    {
        $this->configParser->expects($this->never())->method('read');

        $this->compiler
            ->expects($this->once())
            ->method('compile')
            ->with(__DIR__, $this->isInstanceOf(GlobalConfig::class));

        $this->command->run($this->output, __DIR__);
    }

    public function testItCanReadTheGlobalConfigIfAsked(): void
    {
        $config = GlobalConfig::default();

        $this->configParser
            ->method('read')
            ->with('./global-config.yaml')
            ->willReturn($config);

        $this->compiler
            ->expects($this->once())
            ->method('compile')
            ->with(__DIR__, $config);

        $this->command->run($this->output, __DIR__, './global-config.yaml');
    }
}
