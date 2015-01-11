<?php

namespace ScoreYa\Cinderella\Bundle\SDKBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see
 * {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 *
 * @author Alexander Miehe <thelex@beamscore.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('score_ya_cinderella_sdk');
        $rootNode
            ->children()
                ->arrayNode('clients')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->append($this->addClientNode('template'))
                    ->end()
                ->end()
                ->scalarNode('api_key')->cannotBeEmpty()->isRequired()->end()

            ->end();

        return $treeBuilder;
    }

    /**
     * @param string $name
     *
     * @return \Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    private function addClientNode($name)
    {
        $builder = new TreeBuilder();
        $node    = $builder->root($name);
        $checkIfIsOverwrittenTemplateClient = function($value) {
            return $value !== 'ScoreYa\Cinderella\SDK\Template\TemplateClient';
        };
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('class')
                    ->defaultValue('ScoreYa\Cinderella\SDK\Template\TemplateClient')
                    ->validate()
                    ->ifTrue($checkIfIsOverwrittenTemplateClient)
                        ->thenInvalid('Class configuration for template client was overwritten with %s.')
                    ->end()
                ->end()
                ->scalarNode('base_url')->end()
            ->end()
        ;

        return $node;
    }
}
