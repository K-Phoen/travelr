<?php

namespace Travelr\Cli\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Travelr\Compiler\AlbumsList;
use Travelr\Compiler\MapView;

class CompileHtml
{
    /** @var AlbumsList */
    private $mapViewCompiler;

    public function __construct(MapView $mapViewCompiler)
    {
        $this->mapViewCompiler = $mapViewCompiler;
    }

    public function run(OutputInterface $output): void
    {
        $output->writeln('<info>Compiling map view...</info>');

        $this->mapViewCompiler->compile();

        $output->writeln('Done.');
    }
}
