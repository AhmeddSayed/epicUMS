<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller {

    public function loginAction(Request $request) {
        /*
        $securityContext = $this->container->get('security.authorization_checker');

        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // user already logged in
            return $this->redirect($this->generateUrl('index'));
        } else {
            // if form is submitted
            if ($request->getMethod() == "POST") {
                // check if user is authentic
            } else {
                // user not logged in and form not submitted, display login
                return $this->render('AppBundle:Security:login.html.twig');
            }
        }*/
        $MetaManager = new \AppBundle\Services\MetaManager($this->getDoctrine()->getManager());
        var_dump($MetaManager->getMeta(1));
    }

    public function logoutAction(Request $request) {
        $securityContext = $this->container->get('security.authorization_checker');

        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->render('AppBundle:Default:index.html.twig');
        } else {
            return $this->render('AppBundle:Security:login.html.twig');
        }
    }

}
