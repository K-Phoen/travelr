<?php

namespace Travelr\Repository;

use Symfony\Component\Finder\Finder;
use Travelr\Album;
use Travelr\Metadata\AlbumReader;

class Albums
{
    private const ALBUM_DESCRIPTOR_FILENAME = 'album.yaml';

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
            yield $this->albumReader->read($file->getRealPath());
        }
    }
}
