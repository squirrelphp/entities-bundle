<?php

namespace Squirrel\EntitiesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    private $alias;

    public function __construct(string $alias)
    {
        $this->alias = $alias;
    }

    /**
     * @inheritdoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root($this->alias);

        /*
         * The only configuration options are:
         *
         * - directories (there are no default directories)
         * - table_names (overwriting annotated table names)
         * - connection_names (overwriting annotated connection names)
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
