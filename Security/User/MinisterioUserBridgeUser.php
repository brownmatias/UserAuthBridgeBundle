<?php

namespace Ministerio\Bundle\UserAuthBridgeBundle\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

class MinisterioUserBridgeUser implements UserInterface, EquatableInterface
{
    private $username;
    private $password;
    private $salt;
    private $roles;
    private $is_authenticated;
    private $user_id;

    public function __construct($username, $user_id, $is_authenticated, array $roles)
    {
        $this->username = $username;
        $this->password = "";
        $this->salt = "";
        $final_roles = array();
        foreach($roles as $rol) {
            $final_roles[] = "ROLE_".strtoupper($rol);
        }
        $this->roles = $final_roles;
        $this->is_authenticated = $is_authenticated;
        $this->user_id = $user_id;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
    }

    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof MinisterioUserBridgeUser) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->salt !== $user->getSalt()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }

    public function isAuthenticated()
    {
        return $this->is_authenticated;
    }

    public function getUserId()
    {
        return $this->user_id;
    }
}