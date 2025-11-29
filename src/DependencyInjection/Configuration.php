<?php

namespace Squirrel\EntitiesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final readonly class Configuration implements ConfigurationInterface
{
    public function __construct(
        private string $alias,
    ) {
    }

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder($this->alias);

        $rootNode = $treeBuilder->getRootNode();

        /*
         * The only configuration options are:
         *
         * - directories (there are no default directories)
         * - table_names (overwriting table names in attributes)
         * - connection_names (overwriting connection names in attributes)
         */
        $rootNode
            ->fixXmlConfig('directory', 'directories')
            ->fixXmlConfig('table_name', 'table_names')
            ->fixXmlConfig('connection_name', 'connection_names')
            ->children()
                ->arrayNode('directories')
                    ->prototype('scalar')
                    ->end()
                ->end()
                ->arrayNode('table_names')
                    ->useAttributeAsKey('class')
                    ->prototype('scalar')
                    ->end()
                ->end()
                ->arrayNode('connection_names')
                    ->useAttributeAsKey('class')
                    ->prototype('scalar')
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
