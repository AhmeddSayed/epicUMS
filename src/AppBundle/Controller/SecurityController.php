<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Request;
use \AppBundle\Entity\User;
use \AppBundle\Form\Type\RegistrationType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SecurityController extends Controller {

    public function registerAction(Request $request) {

        if ($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();

            $user = new User();


            $user->setUsername(trim($request->get('username')));
            $user->setEmail(trim($request->get('email')));
            $user->setActive(true);


            $pwd = $request->get('password');
            $confirm = $request->get('confirm');

            if ($pwd != $confirm) {
                return $this->render('AppBundle:Security:register.html.twig');
            }

            $encoder = $this->container->get('security.password_encoder');
            $pwd = $encoder->encodePassword($user, $pwd);
            $user->setPassword($pwd);

            $em->persist($user);
            $em->flush();

            $securityContext = $this->container->get('security.authorization_checker');

            if ($securityContext->isGranted('ROLE_ADMIN')) {
                $user->setRoles($request->get('roles'));
            } else {
                $user->setRoles('ROLE_USER');
            }

            $url = $this->generateUrl('index');
            return $this->redirect($url);
        } else {
            // need to retrieve groups
            $em = $this->getDoctrine()->getManager();
            $RolesRepo = $em->getRepository('AppBundle:Roles');

            $roles = array();
            foreach ($RolesRepo->findAll() as $aRole) {
                array_push($roles, $aRole);
            }

            return $this->container->get('templating')->renderResponse('AppBundle:Security:register.html.twig', array('roles' => $roles));
        }


        return $this->render('AppBundle:Security:register.html.twig');
    }

    public function loginAction(Request $request) {

        if ($request->getMethod() != 'POST') {
            return $this->render('AppBundle:Security:login.html.twig');
        } else {
            $em = $this->getDoctrine()->getManager();
            $usersRepo = $em->getRepository('AppBundle:User');
            $user = new User();
            $user = $usersRepo->findOneBy(array("username" => $request->get('_username')));

            if ($user) {
                $encoder_service = $this->container->get('security.encoder_factory');
                $encoder = $encoder_service->getEncoder($user);
                if ($encoder->isPasswordValid($user->getPassword(), $request->get('_password'), $user->getSalt())) {
                    $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                    $this->get('security.token_storage')->setToken($token);
                    $this->get('session')->set('_security_main', serialize($token));
                    return $this->redirect($this->generateUrl('index'));
                } else {
                    return $this->render('AppBundle:Security:login.html.twig');
                }
            } else {
                return $this->render('AppBundle:Security:login.html.twig');
            }
        }
    }

    public function createAction(Request $request) {
        return $this->registerAction($request);
    }

    public function logoutAction() {
        $this->get('security.token_storage')->setToken(null);
        $this->get('request')->getSession()->invalidate();
    }

    public function indexAction(Request $request) {
        return $this->render('AppBundle:Default:index.html.twig');
    }

}
