<?php

namespace Ministerio\Bundle\UserAuthBridgeBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Ministerio\Bundle\UserAuthBridgeBundle\Security\Authentication\Token\MinisterioUserBridgeToken;

class MinisterioUserBridgeAuthenticatedEvent extends Event 
{
    protected $userToken;

    public function __construct(MinisterioUserBridgeToken $userToken)
    {
        $this->userToken = $userToken;
    }

    public function getUserToken()
    {
        return $this->userToken;
    }

}