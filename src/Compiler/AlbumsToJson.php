<?php

declare(strict_types=1);

namespace Travelr\Compiler;

use Travelr\Repository\Albums;

class AlbumsToJson
{
    /** @var Albums */
    private $albumsRepo;

    public function __construct(Albums $albumsRepo)
    {
        $this->albumsRepo = $albumsRepo;
    }

    public function compile(string $webRoot): void
    {
        $albumsData = [];

        foreach ($this->albumsRepo->findAll($webRoot) as $album) {
            $albumsData[] = [
                'slug' => $album->slug(),
                'title' => $album->title(),
                'description' => $album->description(),
                'thumbnail' => $album->cover()->relativeTo($webRoot)->thumbnailPath(),
                'latitude' => $album->latitude(),
                'longitude' => $album->longitude(),
            ];
        }

        file_put_contents($webRoot.'/albums.json', json_encode($albumsData, JSON_PRETTY_PRINT));
    }
}
