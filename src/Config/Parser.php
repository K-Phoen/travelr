<?php

declare(strict_types=1);

namespace Travelr\Config;

use Symfony\Component\Yaml\Yaml;
use Travelr\Coordinates;
use Travelr\DirectoryConfig;

class Parser
{
    public function read(string $path): DirectoryConfig
    {
        $config = Yaml::parseFile($path);

        // todo check the config using a schema validator

        return new DirectoryConfig(
            $config['title'],
            $config['description'] ?? '',
            new Coordinates($config['latitude'], $config['longitude']),
            $config['cover']
        );
    }
}
