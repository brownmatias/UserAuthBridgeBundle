<?php

namespace Ministerio\Bundle\UserAuthBridgeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ministerio_user_auth_bridge');
        $rootNode
            ->children()
                ->scalarNode('roles')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('logout_url')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('login_url')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
            ->end();
        return $treeBuilder;
    }
}
