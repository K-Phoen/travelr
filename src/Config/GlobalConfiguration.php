<?php

declare(strict_types=1);

namespace Travelr\Config;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Travelr\GlobalConfig;

class GlobalConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('travelr');

        $rootNode
            ->children()
                ->enumNode('sort_images_by')
                    ->values([GlobalConfig::SORT_BY_NAME, GlobalConfig::SORT_BY_MODIFICATION_DATE])
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->defaultValue(GlobalConfig::SORT_BY_NAME)
                ->end()
                ->enumNode('map_provider')
                    ->values([GlobalConfig::MAP_OPENSTREETMAP, GlobalConfig::MAP_MAPBOX])
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->defaultValue(GlobalConfig::MAP_OPENSTREETMAP)
                ->end()
                ->scalarNode('map_api_key')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->defaultValue('')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
