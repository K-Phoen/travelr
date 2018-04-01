<?php

namespace Travelr\Cli\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Travelr\Compiler\AlbumsList;

class CompileAlbums
{
    /** @var AlbumsList */
    private $albumslistCompiler;

    public function __construct(AlbumsList $albumslistCompiler)
    {
        $this->albumslistCompiler = $albumslistCompiler;
    }

    public function run(OutputInterface $output): void
    {
        $output->writeln('<info>Compiling albums...</info>');

        $this->albumslistCompiler->compile();

        $output->writeln('Done.');
    }
}
