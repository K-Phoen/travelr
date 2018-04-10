<?php

declare(strict_types=1);

namespace Travelr\Config;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class AlbumConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('album');

        $rootNode
            ->children()
                ->scalarNode('title')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('description')
                    ->defaultValue('')
                ->end()
                ->scalarNode('cover')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()

                ->scalarNode('location')->defaultNull()->end()
                ->floatNode('latitude')->defaultNull()->end()
                ->floatNode('longitude')->defaultNull()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
