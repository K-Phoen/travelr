<?php

declare(strict_types=1);

namespace Travelr\Config;

use Geocoder\Geocoder;
use Geocoder\Query\GeocodeQuery;
use Symfony\Component\Yaml\Yaml;
use Travelr\Coordinates;
use Travelr\DirectoryConfig;

class Parser
{
    /** @var Geocoder */
    private $geocoder;

    public function __construct(Geocoder $geocoder)
    {
        $this->geocoder = $geocoder;
    }

    public function read(string $path): DirectoryConfig
    {
        $config = Yaml::parseFile($path);

        if (empty($config['latitude'])) {
            $result = $this->geocoder->geocodeQuery(GeocodeQuery::create($config['location']));

            $config['latitude'] = $result->first()->getCoordinates()->getLatitude();
            $config['longitude'] = $result->first()->getCoordinates()->getLongitude();
        }

        // todo check the config using a schema validator

        return new DirectoryConfig(
            $config['title'],
            $config['description'] ?? '',
            new Coordinates($config['latitude'], $config['longitude']),
            $config['cover']
        );
    }
}
