<?php

namespace Ministerio\Bundle\UserAuthBridgeBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Ministerio\Bundle\UserAuthBridgeBundle\Security\Authentication\Token\MinisterioUserBridgeToken;
use Theodo\Evolution\Bundle\SessionBundle\Manager\BagManagerConfigurationInterface;
use Theodo\Evolution\Bundle\SessionBundle\Manager\Symfony1\BagConfiguration;
use Ministerio\Bundle\UserAuthBridgeBundle\Event\MinisterioUserBridgeAuthenticatedEvent;

class MinisterioUserBridgeListener implements ListenerInterface
{
    const AUTHENTICATED_EVENT = "miniterio.user.bridge.authenticated";

    protected $tokenStorage;
    protected $authenticationManager;
    protected $container;

    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationManagerInterface $authenticationManager, $container)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->container = $container;
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $session = $request->getSession();

        $bag_configuration = new BagConfiguration();
        if($session->getBag($bag_configuration->getNamespace(BagManagerConfigurationInterface::ATTRIBUTE_NAMESPACE))->has('sfGuardSecurityUser')) {
            $sf1_guard_security_user = $session->getBag($bag_configuration->getNamespace(BagManagerConfigurationInterface::ATTRIBUTE_NAMESPACE))->get('sfGuardSecurityUser');
            $username = $sf1_guard_security_user['username'];
            
            $token = new MinisterioUserBridgeToken();
            $token->setUser($username);

            try {
                $authToken = $this->authenticationManager->authenticate($token);
                $this->tokenStorage->setToken($authToken);
                $event->getDispatcher()->dispatch(self::AUTHENTICATED_EVENT, new MinisterioUserBridgeAuthenticatedEvent($authToken));
                return;
            } catch (AuthenticationException $failed) {
                // ... you might log something here

                // To deny the authentication clear the token. This will redirect to the login page.
                // Make sure to only clear your token, not those of other authentication listeners.
                // $token = $this->tokenStorage->getToken();
                // if ($token instanceof WsseUserToken && $this->providerKey === $token->getProviderKey()) {
                //     $this->tokenStorage->setToken(null);
                // }
                // return;
            }
        }

        // By default deny authorization
        $response = new Response("", Response::HTTP_TEMPORARY_REDIRECT, array("Location" => $this->container->getParameter('logout_url')));
        $event->setResponse($response);
    }
}