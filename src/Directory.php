<?php

declare(strict_types=1);

namespace Travelr;

final class Directory
{
    /** @var string */
    private $path;

    /** @var DirectoryConfig */
    private $config;

    /** @var iterable|string[] */
    private $images;

    /**
     * @param iterable|string[] $images
     */
    public function __construct(string $path, DirectoryConfig $config, iterable $images)
    {
        $this->path = $path;
        $this->config = $config;
        $this->images = $images;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function config(): DirectoryConfig
    {
        return $this->config;
    }

    /**
     * @return iterable|string[]
     */
    public function images(): iterable
    {
        return $this->images;
    }
}
