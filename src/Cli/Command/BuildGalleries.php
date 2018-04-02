<?php

declare(strict_types=1);

namespace Travelr\Cli\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Travelr\Album;
use Travelr\Compiler\GalleryView;
use Travelr\Repository\Albums;

class BuildGalleries
{
    /** @var Albums */
    private $albumsRepo;

    /** @var GalleryView */
    private $galleryViewCompiler;

    public function __construct(Albums $albumsRepo, GalleryView $galleryViewCompiler)
    {
        $this->albumsRepo = $albumsRepo;
        $this->galleryViewCompiler = $galleryViewCompiler;
    }

    public function run(string $webRoot, OutputInterface $output): void
    {
        $output->writeln('<info>Compiling galleries...</info>');

        /** @var Album $album */
        foreach ($this->albumsRepo->findAll($webRoot) as $album) {
            $this->galleryViewCompiler->compile($album);
        }

        $output->writeln('Done.');
    }
}
