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

    public function editUserAction(Request $request) {
        if ($request->getMethod() == "POST") {
            $role = $request->get('roles');
            $id = $request->request->get('id');
            $em = $this->getDoctrine()->getManager();
            $UsersRepo = $em->getRepository('AppBundle:User');

            $user = new User();
            $user = $UsersRepo->findOneBy(array('id'=>$id));

            $user->setRoles($role);
            $em->persist($user);
            $em->flush();

            $url = $this->generateUrl('edit');
            return $this->redirect($url);
        } elseif ($request->get('id') == null) {
            // need to retrieve users
            $em = $this->getDoctrine()->getManager();
            $UsersRepo = $em->getRepository('AppBundle:User');

            $users = array();
            foreach ($UsersRepo->findAll() as $aUser) {
                array_push($users, $aUser);
            }

            return $this->container->get('templating')->renderResponse('AppBundle:Default:show.html.twig', array('users' => $users));
        } else {
            // need to retrieve groups
            $em = $this->getDoctrine()->getManager();
            $RolesRepo = $em->getRepository('AppBundle:Roles');

            $roles = array();
            foreach ($RolesRepo->findAll() as $aRole) {
                array_push($roles, $aRole);
            }

            return $this->container->get('templating')->renderResponse('AppBundle:Default:edit.html.twig', array('roles' => $roles));
        }
    }

}
