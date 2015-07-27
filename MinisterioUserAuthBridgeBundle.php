<?php

namespace Ministerio\Bundle\UserAuthBridgeBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ministerio\Bundle\UserAuthBridgeBundle\Security\Factory\MinisterioUserBridgeFactory;

class MinisterioUserAuthBridgeBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new MinisterioUserBridgeFactory());
    }
}
