<?php

declare(strict_types=1);

namespace Travelr;

final class Image
{
    /** @var string */
    private $path;

    /** @var string */
    private $thumbnailPath;

    public static function fromPath(string $path, string $thumbnailPath = null): Image
    {
        return new static($path, $thumbnailPath ?? $path);
    }

    public static function thumbFrom(Image $source, string $thumbPrefix): Image
    {
        return static::fromPath($source->path(), $source->directory().'/'.$thumbPrefix.$source->filename());
    }

    private function __construct(string $path, string $thumbnailPath)
    {
        $this->path = $path;
        $this->thumbnailPath = $thumbnailPath;
    }

    public function relativeTo(string $directory): Image
    {
        $directory = \realpath($directory);

        return new static(
            trim(str_replace($directory, '', $this->path), '/'),
            trim(str_replace($directory, '', $this->thumbnailPath), '/')
        );
    }

    public function path(): string
    {
        return $this->path;
    }

    public function thumbnailPath(): string
    {
        return $this->thumbnailPath;
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
