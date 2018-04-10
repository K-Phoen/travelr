<?php

declare(strict_types=1);

namespace Travelr\Config;

use Geocoder\Geocoder;
use Geocoder\Query\GeocodeQuery;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;
use Travelr\Coordinates;
use Travelr\DirectoryConfig;

class DirectoryParser
{
    /** @var Geocoder */
    private $geocoder;

    public function __construct(Geocoder $geocoder)
    {
        $this->geocoder = $geocoder;
    }

    public function read(string $path): DirectoryConfig
    {
        $processor = new Processor();

        try {
            $config = $processor->processConfiguration(
                new AlbumConfiguration(),
                ['album' => Yaml::parseFile($path)]
            );
        } catch (InvalidConfigurationException $e) {
            throw InvalidConfiguration::inFile($path, $e->getMessage(), $e);
        }

        if (!empty($config['location'])) {
            $result = $this->geocoder->geocodeQuery(GeocodeQuery::create($config['location']));

            $config['latitude'] = $result->first()->getCoordinates()->getLatitude();
            $config['longitude'] = $result->first()->getCoordinates()->getLongitude();
        }

        if (empty($config['latitude']) || empty($config['longitude'])) {
            throw InvalidConfiguration::inFile($path, 'either specify the "location" option or the latitude/longitude ones.');
        }

        return new DirectoryConfig(
            $config['title'],
            $config['description'] ?? '',
            new Coordinates($config['latitude'], $config['longitude']),
            $config['cover']
        );
    }
}
