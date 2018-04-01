<?php

namespace Travelr\Compiler;

use Travelr\Repository\Albums;
use Travelr\Thumbnail\Thumbnailer;

class AlbumsList
{
    /** @var Albums */
    private $albumsRepo;

    /** @var Thumbnailer */
    private $thumbnailer;

    /** @var string */
    private $webDirectory;

    /** @var string */
    private $destinationFile;

    public function __construct(Albums $albumsRepo, Thumbnailer $thumbnailer, string $webDirectory, string $destinationFile)
    {
        $this->albumsRepo = $albumsRepo;
        $this->thumbnailer = $thumbnailer;
        $this->webDirectory = $webDirectory;
        $this->destinationFile = $destinationFile;
    }

    public function compile(): void
    {
        $albumsData = [];

        foreach ($this->albumsRepo->findAll() as $album) {
            $thumbnail = $this->thumbnailer->forImage($album->cover());
            $relativeDir = rtrim(str_replace($this->webDirectory, '', $album->directory()), '/');

            $albumsData[] = [
                'title' => $album->title(),
                'description' => $album->description(),
                'thumbnail' => $relativeDir.'/'.$thumbnail->filename(),
                'latitude' => $album->latitude(),
                'longitude' => $album->longitude(),
            ];
        }

        file_put_contents($this->destinationFile, json_encode($albumsData, JSON_PRETTY_PRINT));
    }
}
