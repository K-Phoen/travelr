<?php

declare(strict_types=1);

namespace Travelr;

final class GlobalConfig
{
    public const SORT_BY_NAME = 'name';

    public const SORT_BY_MODIFICATION_DATE = 'modification_date';

    public const MAP_OPENSTREETMAP = 'openstreetmap';

    public const MAP_MAPBOX = 'mapbox';

    /** @var string */
    private $title;

    /** @var string */
    private $sortImagesBy;

    /** @var string */
    private $mapProvider;

    /** @var string */
    private $mapApiKey;

    public static function default(): self
    {
        return new static('Travelr', self::SORT_BY_NAME, self::MAP_OPENSTREETMAP, '');
    }

    public function __construct(string $title, string $sortImagesBy, string $mapProvider, string $mapApiKey)
    {
        if (!\in_array($sortImagesBy, [self::SORT_BY_MODIFICATION_DATE, self::SORT_BY_NAME], true)) {
            throw new \DomainException('Invalid sort option');
        }

        if (!\in_array($mapProvider, [self::MAP_OPENSTREETMAP, self::MAP_MAPBOX], true)) {
            throw new \DomainException('Invalid map provider option');
        }

        if ($mapProvider === self::MAP_MAPBOX && empty($mapApiKey)) {
            throw new \DomainException('The chosen map provider requires an API key');
        }

        $this->title = $title;
        $this->sortImagesBy = $sortImagesBy;
        $this->mapProvider = $mapProvider;
        $this->mapApiKey = $mapApiKey;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function sortImagesBy(): string
    {
        return $this->sortImagesBy;
    }

    public function mapTileLayerUrl(): string
    {
        switch ($this->mapProvider) {
            case self::MAP_MAPBOX:
                return 'https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}';
            case self::MAP_OPENSTREETMAP:
                return 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
            default:
                throw new \LogicException('No tile layer URL found.');
        }
    }

    public function mapApiKey(): string
    {
        return $this->mapApiKey;
    }
}
