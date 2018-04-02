<?php

declare(strict_types=1);

namespace Travelr;

final class Album
{
    /** @var string */
    private $path;

    /** @var string */
    private $slug;

    /** @var string */
    private $title;

    /** @var string */
    private $description;

    /** @var Image */
    private $cover;

    /** @var iterable|Image[] */
    private $images;

    /** @var Coordinates */
    private $coordinates;

    /**
     * @param iterable|Image[] $images
     */
    public function __construct(string $path, string $title, string $description, Coordinates $coordinates, Image $cover, iterable $images)
    {
        $this->path = $path;
        $this->slug = \basename($path);
        $this->title = $title;
        $this->description = $description;
        $this->cover = $cover;
        $this->images = $images;
        $this->coordinates = $coordinates;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function slug(): string
    {
        return $this->slug;
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
        return $this->coordinates->latitude();
    }

    public function longitude(): float
    {
        return $this->coordinates->longitude();
    }

    /**
     * @return iterable|Image[]
     */
    public function images(): iterable
    {
        return $this->images;
    }
}
