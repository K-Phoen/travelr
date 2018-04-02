<?php

declare(strict_types=1);

namespace Travelr;

final class DirectoryConfig
{
    /** @var string */
    private $title;

    /** @var string */
    private $description;

    /** @var Coordinates */
    private $coordinates;

    /** @var string */
    private $cover;

    public function __construct(string $title, string $description, Coordinates $coordinates, string $cover)
    {
        $this->title = $title;
        $this->description = $description;
        $this->coordinates = $coordinates;
        $this->cover = $cover;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function coordinates(): Coordinates
    {
        return $this->coordinates;
    }

    public function cover(): string
    {
        return $this->cover;
    }
}
