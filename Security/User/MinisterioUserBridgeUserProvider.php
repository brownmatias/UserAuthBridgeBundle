<?php

namespace Ministerio\Bundle\UserAuthBridgeBundle\Security\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\HttpFoundation\Session\Session;
use Theodo\Evolution\Bundle\SessionBundle\Manager\BagManagerConfigurationInterface;
use Theodo\Evolution\Bundle\SessionBundle\Manager\Symfony1\BagConfiguration;

class MinisterioUserBridgeUserProvider implements UserProviderInterface
{
    private $session;
    private $container;

    public function __construct(Session $session, $container)
    {
        $this->session = $session;
        $this->container = $container;
    }

    public function loadUserByUsername($username)
    {

        $bag_configuration = new BagConfiguration();
        $sf1_guard_security_user = $this->session->getBag($bag_configuration->getNamespace(BagManagerConfigurationInterface::ATTRIBUTE_NAMESPACE))->get('sfGuardSecurityUser');
        $credentials = $this->session->getBag($bag_configuration->getNamespace(BagManagerConfigurationInterface::CREDENTIAL_NAMESPACE))->all();
        $is_authenticated = $this->session->getBag($bag_configuration->getNamespace(BagManagerConfigurationInterface::AUTH_NAMESPACE))->get();
        $user_id = $sf1_guard_security_user['user_id'];
        $user_name = $sf1_guard_security_user['username'];

        $final_credentials = array();
        if(is_array($credentials)) {
            $allowed_roles = explode(",", strtolower($this->container->getParameter('roles')));
            foreach($credentials as $credential) {
                if(in_array(strtolower($credential), $allowed_roles)) {
                    $final_credentials[] = strtolower($credential);
                }
            }
        }

        if(count($final_credentials) > 0) {
            $final_credentials[] = "access";
        }

        if ($username == $user_name) {
            return new MinisterioUserBridgeUser($user_name, $user_id, $is_authenticated, $final_credentials);
        }

        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof MinisterioUserBridgeUser) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Ministerio\Bundle\UserAuthBridgeBundle\Security\User\MinisterioUserBridgeUser';
    }
}