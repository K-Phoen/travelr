<?php

namespace Travelr\Compiler;

use Travelr\Repository\Albums;
use Travelr\Thumbnail\Thumbnailer;

class AlbumsToJson
{
    /** @var Albums */
    private $albumsRepo;

    /** @var Thumbnailer */
    private $thumbnailer;

    /** @var string */
    private $webDirectory;

    public function __construct(Albums $albumsRepo, Thumbnailer $thumbnailer, string $webDirectory)
    {
        $this->albumsRepo = $albumsRepo;
        $this->thumbnailer = $thumbnailer;
        $this->webDirectory = $webDirectory;
    }

    public function compile(string $destinationFile): void
    {
        $albumsData = [];

        foreach ($this->albumsRepo->findAll() as $album) {
            $thumbnail = $this->thumbnailer->forImage($album->cover());
            $relativeDir = trim(str_replace($this->webDirectory, '', $thumbnail->directory()), '/');

            $albumsData[] = [
                'slug' => $album->slug(),
                'title' => $album->title(),
                'description' => $album->description(),
                'thumbnail' => $relativeDir.'/'.$thumbnail->filename(),
                'latitude' => $album->latitude(),
                'longitude' => $album->longitude(),
            ];
        }

        file_put_contents($destinationFile, json_encode($albumsData, JSON_PRETTY_PRINT));
    }
}
