<?php

declare(strict_types=1);

namespace Travelr\Cli\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Travelr\Album;
use Travelr\Compiler\GalleryView;
use Travelr\GlobalConfig;
use Travelr\Repository\Albums;
use Travelr\Config\GlobalParser as ConfigParser;

class BuildGalleries
{
    /** @var Albums */
    private $albumsRepo;

    /** @var GalleryView */
    private $galleryViewCompiler;

    /** @var ConfigParser */
    private $configParser;

    public function __construct(Albums $albumsRepo, GalleryView $galleryViewCompiler, ConfigParser $configParser)
    {
        $this->albumsRepo = $albumsRepo;
        $this->galleryViewCompiler = $galleryViewCompiler;
        $this->configParser = $configParser;
    }

    public function run(OutputInterface $output, string $webRoot, string $globalConfig = null): void
    {
        $output->writeln('<info>Compiling galleries...</info>');

        $config = $globalConfig ? $this->configParser->read($globalConfig) : GlobalConfig::default();

        /** @var Album $album */
        foreach ($this->albumsRepo->findAll($webRoot, $config) as $album) {
            $this->galleryViewCompiler->compile($album, $config);
        }

        $output->writeln('Done.');
    }
}
