<?php

namespace Travelr;

class Album
{
    /** @var string */
    private $title;

    /** @var string */
    private $description;

    /** @var Image */
    private $cover;

    /** @var Image[] */
    private $images = [];

    /** @var float */
    private $latitude;

    /** @var float */
    private $longitude;

    /** @var string */
    private $directory;

    public static function fromArray(array $data): Album
    {
        $album = new static();

        $album->title = $data['title'];
        $album->description = $data['description'] ?? '';
        $album->directory = $data['directory'];
        $album->cover = Image::fromPath($data['directory'].'/'.$data['cover']);
        $album->latitude = (float) $data['latitude'];
        $album->longitude = (float) $data['longitude'];

        return $album;
    }

    public function withImages(iterable $images): Album
    {
        $album = clone $this;
        $album->images = $images;

        return $album;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function slug(): string
    {
        $parts = explode('/', $this->directory);

        return end($parts);
    }

    public function description(): string
    {
        return $this->description;
    }

    public function cover(): Image
    {
        return $this->cover;
    }

    public function latitude(): float
    {
        return $this->latitude;
    }

    public function longitude(): float
    {
        return $this->longitude;
    }

    public function directory(): string
    {
        return $this->directory;
    }

    /**
     * @return iterable|Image[]
     */
    public function images(): iterable
    {
        return $this->images;
    }
}
