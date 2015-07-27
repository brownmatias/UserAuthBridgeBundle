<?php

namespace Ministerio\Bundle\UserAuthBridgeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function returnAction()
    {
        return $this->redirect($this->container->getParameter('login_url'));
    }

    public function loginAction()
    {
        return $this->redirect($this->container->getParameter('login_url'));
    }

    public function logoutAction()
    {
        $this->get('security.token_storage')->setToken(null);
        $this->get('request')->getSession()->invalidate();
        return $this->redirect($this->container->getParameter('logout_url'));
    }
}
