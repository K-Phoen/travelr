<?php

declare(strict_types=1);

namespace Travelr\Compiler;

use Symfony\Component\Filesystem\Filesystem;
use Travelr\GlobalConfig;
use Travelr\Repository\Albums;

class AlbumsToJson
{
    /** @var Albums */
    private $albumsRepo;

    /** @var Filesystem */
    private $fs;

    public function __construct(Albums $albumsRepo, Filesystem $fs = null)
    {
        $this->albumsRepo = $albumsRepo;
        $this->fs = $fs ?: new Filesystem();
    }

    public function compile(string $webRoot, GlobalConfig $config): void
    {
        $albumsData = [];

        foreach ($this->albumsRepo->findAll($webRoot, $config) as $album) {
            $albumsData[] = [
                'slug' => $album->slug(),
                'title' => $album->title(),
                'description' => $album->description(),
                'thumbnail' => $album->cover()->relativeTo($webRoot)->thumbnailPath(),
                'latitude' => $album->latitude(),
                'longitude' => $album->longitude(),
            ];
        }

        $this->fs->dumpFile($webRoot.'/albums.json', json_encode($albumsData, JSON_PRETTY_PRINT));
    }
}
