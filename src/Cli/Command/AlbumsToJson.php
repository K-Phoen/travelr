<?php

declare(strict_types=1);

namespace Travelr\Cli\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Travelr\Compiler\AlbumsToJson as AlbumsToJsonCompiler;
use Travelr\Config\GlobalParser as ConfigParser;
use Travelr\GlobalConfig;

class AlbumsToJson
{
    /** @var AlbumsToJsonCompiler */
    private $albumsListCompiler;

    /** @var ConfigParser */
    private $configParser;

    public function __construct(AlbumsToJsonCompiler $albumsListCompiler, ConfigParser $configParser)
    {
        $this->albumsListCompiler = $albumsListCompiler;
        $this->configParser = $configParser;
    }

    public function run(OutputInterface $output, string $webRoot, string $globalConfig = null): void
    {
        $output->writeln('<info>Compiling albums...</info>');

        $config = $globalConfig ? $this->configParser->read($globalConfig) : GlobalConfig::default();

        $this->albumsListCompiler->compile(\realpath($webRoot), $config);

        $output->writeln('Done.');
    }
}
