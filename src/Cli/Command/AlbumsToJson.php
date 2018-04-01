<?php

namespace Travelr\Cli\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Travelr\Compiler\AlbumsToJson as AlbumsToJsonCompiler;

class AlbumsToJson
{
    /** @var AlbumsToJsonCompiler */
    private $albumsListCompiler;

    public function __construct(AlbumsToJsonCompiler $albumslistCompiler)
    {
        $this->albumsListCompiler = $albumslistCompiler;
    }

    public function run(string $destinationFile, OutputInterface $output): void
    {
        $output->writeln('<info>Compiling albums...</info>');

        $this->albumsListCompiler->compile($destinationFile);

        $output->writeln('Done.');
    }
}
