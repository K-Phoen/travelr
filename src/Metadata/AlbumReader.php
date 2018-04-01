<?php

namespace Travelr\Metadata;

use Symfony\Component\Yaml\Yaml;
use Travelr\Album;

class AlbumReader
{
    public function read(string $descriptorPath): Album
    {
        $albumData = Yaml::parseFile($descriptorPath);
        $albumData['directory'] = \dirname($descriptorPath);

        return Album::fromArray($albumData);
    }
}
