<?php

namespace Travelr\Cli\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Travelr\Compiler\AlbumsList;
use Travelr\Compiler\AlbumView;
use Travelr\Compiler\MapView;
use Travelr\Repository\Albums;

class CompileHtml
{
    /** @var Albums */
    private $albumsRepo;

    /** @var AlbumsList */
    private $mapViewCompiler;

    /** @var AlbumView */
    private $albumViewCompiler;

    public function __construct(Albums $albumsRepo, MapView $mapViewCompiler, AlbumView $albumViewCompiler)
    {
        $this->albumsRepo = $albumsRepo;
        $this->mapViewCompiler = $mapViewCompiler;
        $this->albumViewCompiler = $albumViewCompiler;
    }

    public function run(OutputInterface $output): void
    {
        $output->writeln('<info>Compiling map view...</info>');

        $this->mapViewCompiler->compile();

        $output->writeln('Done.');

        $output->writeln('<info>Compiling albums...</info>');

        /** @var Albums $album */
        foreach ($this->albumsRepo->findAll() as $album) {
            $this->albumViewCompiler->compile($album);
        }

        $output->writeln('Done.');
    }
}
