<?php

namespace Ministerio\Bundle\UserAuthBridgeBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Ministerio\Bundle\UserAuthBridgeBundle\Security\Authentication\Token\MinisterioUserBridgeToken;

class MinisterioUserBridgeProvider implements AuthenticationProviderInterface
{
    private $userProvider;

    public function __construct(UserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByUsername($token->getUsername());
        if ($user && $user->isAuthenticated()) {
            $authenticatedToken = new MinisterioUserBridgeToken($user->getRoles());
            $authenticatedToken->setUser($user);
            return $authenticatedToken;
        }
        throw new AuthenticationException('The Ministerio User Bridge authentication failed.');
    }

    protected function validateUser($token)
    {
      return true;
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof MinisterioUserBridgeToken;
    }
}