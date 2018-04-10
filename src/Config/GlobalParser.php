<?php

declare(strict_types=1);

namespace Travelr\Config;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;
use Travelr\GlobalConfig;

class GlobalParser
{
    public function read(string $path): GlobalConfig
    {
        $processor = new Processor();

        try {
            $config = $processor->processConfiguration(
                new GlobalConfiguration(),
                ['travelr' => Yaml::parseFile($path)]
            );
        } catch (InvalidConfigurationException $e) {
            throw InvalidConfiguration::inFile($path, $e->getMessage(), $e);
        }

        return new GlobalConfig($config['sort_images_by']);
    }
}
