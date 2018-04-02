<?php

declare(strict_types=1);

namespace Travelr\Cli\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Travelr\Compiler\AlbumsToJson as AlbumsToJsonCompiler;

class AlbumsToJson
{
    /** @var AlbumsToJsonCompiler */
    private $albumsListCompiler;

    public function __construct(AlbumsToJsonCompiler $albumsListCompiler)
    {
        $this->albumsListCompiler = $albumsListCompiler;
    }

    public function run(string $webRoot, OutputInterface $output): void
    {
        $output->writeln('<info>Compiling albums...</info>');

        $this->albumsListCompiler->compile($webRoot);

        $output->writeln('Done.');
    }
}
