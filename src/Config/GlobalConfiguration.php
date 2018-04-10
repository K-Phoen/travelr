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
            ->end()
        ;

        return $treeBuilder;
    }
}
