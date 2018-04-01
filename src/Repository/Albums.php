<?php

namespace Travelr\Repository;

use Symfony\Component\Finder\Finder;
use Travelr\Album;
use Travelr\Image;
use Travelr\Metadata\AlbumReader;

class Albums
{
    private const ALBUM_DESCRIPTOR_FILENAME = 'album.yaml';
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png'];

    /** @var string */
    private $rootDirectory;

    /** @var AlbumReader */
    private $albumReader;

    public function __construct(string $rootDirectory, AlbumReader $albumReader)
    {
        $this->rootDirectory = $rootDirectory;
        $this->albumReader = $albumReader;
    }

    /**
     * @return \Generator|Album[]
     */
    public function findAll(): \Generator
    {
        $finder = new Finder();
        $finder
            ->files()
            ->name(self::ALBUM_DESCRIPTOR_FILENAME)
            ->depth(1)
            ->in($this->rootDirectory);

        foreach ($finder as $file) {
            $album = $this->albumReader->read($file->getRealPath());

            yield $album->withImages($this->imagesPaths($album));
        }
    }

    private function imagesPaths(Album $album): iterable
    {
        $finder = new Finder();
        $finder
            ->files()
            ->filter(function (\SplFileInfo $fileInfo) {
                $extension = strtolower($fileInfo->getExtension());

                return \in_array($extension, self::ALLOWED_EXTENSIONS, true);
            })
            ->depth(0)
            ->in($album->directory());

        foreach ($finder as $file) {
            yield Image::fromPath($file->getRealPath());
        }
    }
}
