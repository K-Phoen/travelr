<?php

declare(strict_types=1);

namespace Travelr\Cli\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Travelr\Compiler\AlbumsMapView;
use Travelr\Config\GlobalParser as ConfigParser;
use Travelr\GlobalConfig;

class BuildAlbumsMapView
{
    /** @var AlbumsMapView */
    private $albumsMapViewCompiler;

    /** @var ConfigParser */
    private $configParser;

    public function __construct(AlbumsMapView $albumsMapViewCompiler, ConfigParser $configParser)
    {
        $this->albumsMapViewCompiler = $albumsMapViewCompiler;
        $this->configParser = $configParser;
    }

    public function run(OutputInterface $output, string $webRoot, string $globalConfig = null): void
    {
        $output->writeln('<info>Compiling albums map view...</info>');

        $config = $globalConfig ? $this->configParser->read($globalConfig) : GlobalConfig::default();

        $this->albumsMapViewCompiler->compile($webRoot, $config);

        $output->writeln('Done.');
    }
}
