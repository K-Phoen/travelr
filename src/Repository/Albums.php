<?php

declare(strict_types=1);

namespace Travelr\Repository;

use Travelr\Album;
use Travelr\Directory;
use Travelr\GlobalConfig;
use Travelr\Image;
use Travelr\Thumbnail\Thumbnailer;

class Albums
{
    /** @var Directories */
    private $directoriesRepo;

    /** @var Thumbnailer */
    private $thumbnailer;

    public function __construct(Directories $directoriesRepo, Thumbnailer $thumbnailer)
    {
        $this->directoriesRepo = $directoriesRepo;
        $this->thumbnailer = $thumbnailer;
    }

    /**
     * @return iterable|Album[]
     */
    public function findAll(string $webRoot, GlobalConfig $config): iterable
    {
        foreach ($this->directoriesRepo->findAll($webRoot, $config) as $directory) {
            yield $this->albumFromDirectory($directory);
        }
    }

    private function albumFromDirectory(Directory $directory): Album
    {
        $config = $directory->config();

        return new Album(
            $directory->path(),
            $config->title(),
            $config->description(),
            $config->coordinates(),
            $this->imageFromPath($directory->path().'/'.$config->cover()),
            $this->imagesFromDirectory($directory)
        );
    }

    private function imagesFromDirectory(Directory $directory): iterable
    {
        foreach ($directory->images() as $imagePath) {
            yield $this->imageFromPath($imagePath);
        }
    }

    private function imageFromPath(string $path): Image
    {
        return $this->thumbnailer->forImage(Image::fromPath($path));
    }
}
