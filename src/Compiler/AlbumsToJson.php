<?php

declare(strict_types=1);

namespace Travelr\Compiler;

use Travelr\Repository\Albums;

class AlbumsToJson
{
    /** @var Albums */
    private $albumsRepo;

    /** @var string */
    private $webDirectory;

    public function __construct(Albums $albumsRepo, string $webDirectory)
    {
        $this->albumsRepo = $albumsRepo;
        $this->webDirectory = $webDirectory;
    }

    public function compile(string $destinationFile): void
    {
        $albumsData = [];

        foreach ($this->albumsRepo->findAll() as $album) {
            $albumsData[] = [
                'slug' => $album->slug(),
                'title' => $album->title(),
                'description' => $album->description(),
                'thumbnail' => $album->cover()->relativeTo($this->webDirectory)->thumbnailPath(),
                'latitude' => $album->latitude(),
                'longitude' => $album->longitude(),
            ];
        }

        file_put_contents($destinationFile, json_encode($albumsData, JSON_PRETTY_PRINT));
    }
}
