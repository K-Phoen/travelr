<?php

namespace Travelr;

class Image
{
    /** @var string */
    private $path;

    public static function fromPath(string $path): Image
    {
        $image = new static();

        $image->path = $path;

        return $image;
    }

    public static function thumbFrom(Image $source, string $prefix): Image
    {
        $image = clone $source;

        $image->path = $source->directory().'/'.$prefix.$source->filename();

        return $image;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function filename(): string
    {
        return pathinfo($this->path, PATHINFO_BASENAME);
    }

    public function directory(): string
    {
        return pathinfo($this->path, PATHINFO_DIRNAME);
    }
}
