<?php

namespace Ministerio\Bundle\UserAuthBridgeBundle\Security\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;

class MinisterioUserBridgeFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'security.authentication.provider.ministerio_user_bridge.'.$id;
        $container
            ->setDefinition($providerId, new DefinitionDecorator('ministerio_user_bridge.security.authentication.provider'))
            ->replaceArgument(0, new Reference($userProvider))
        ;

        $listenerId = 'security.authentication.listener.ministerio_user_bridge.'.$id;
        $listener = $container->setDefinition($listenerId, new DefinitionDecorator('ministerio_user_bridge.security.authentication.listener'));
        return array($providerId, $listenerId, $defaultEntryPoint);
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'ministerio_user_bridge';
    }

    public function addConfiguration(NodeDefinition $node)
    {
    }
}