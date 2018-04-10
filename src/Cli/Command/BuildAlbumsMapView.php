<?php

declare(strict_types=1);

namespace Travelr\Cli\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Travelr\Compiler\AlbumsMapView;

class BuildAlbumsMapView
{
    /** @var AlbumsMapView */
    private $albumsMapViewCompiler;

    public function __construct(AlbumsMapView $albumsMapViewCompiler)
    {
        $this->albumsMapViewCompiler = $albumsMapViewCompiler;
    }

    public function run(OutputInterface $output, string $webRoot): void
    {
        $output->writeln('<info>Compiling albums map view...</info>');

        $this->albumsMapViewCompiler->compile($webRoot);

        $output->writeln('Done.');
    }
}
