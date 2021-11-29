<?php

namespace Squirrel\EntitiesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    private string $alias;

    public function __construct(string $alias)
    {
        $this->alias = $alias;
    }

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder($this->alias);

        $rootNode = $treeBuilder->getRootNode();

        /**
         * The only configuration options are:
         *
         * - directories (there are no default directories)
         * - table_names (overwriting annotated table names)
         * - connection_names (overwriting annotated connection names)
         *
         * @psalm-suppress PossiblyUndefinedMethod
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
