<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \AppBundle\Entity\User;

class DefaultController extends Controller {

    public function indexAction(Request $request) {
        $securityContext = $this->container->get('security.authorization_checker');

        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->render('AppBundle:Default:index.html.twig');
        } else {
            return $this->render('AppBundle:Security:login.html.twig');
        }
    }

    public function addGroupAction(Request $request) {
        $securityContext = $this->container->get('security.authorization_checker');

        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            if ($request->getMethod() == "POST") {
                $em = $this->getDoctrine()->getManager();

                $role = new \AppBundle\Entity\Roles();

                $usersRepo = $em->getRepository('AppBundle:Roles');

                if ($usersRepo->findOneBy(array("role" => $request->get('groupname')))) {
                    // group exists
                    return $this->render('AppBundle:Default:addGroup.html.twig');
                }

                $role->setRole($request->get('groupname'));
                $em->persist($role);
                $em->flush();

                $url = $this->generateUrl('index');
                return $this->redirect($url);
            } else {
                return $this->render('AppBundle:Default:addGroup.html.twig');
            }
        } else {
            return $this->render('AppBundle:Security:login.html.twig');
        }
    }

}
