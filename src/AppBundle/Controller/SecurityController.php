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
            $form = $this->createForm(new RegistrationType(), new User());
            $form->handleRequest($request);

            $user = new User();
            $user = $form->getData();
            $securityContext = $this->container->get('security.authorization_checker');

            if ($securityContext->isGranted('ROLE_ADMIN')) {
                $user->setRoles($request->get('roles'));
            } else {
                $user->setRoles('ROLE_USER');
            }

            $user->setActive(true);

            $pwd = $user->getPassword();
            $encoder = $this->container->get('security.password_encoder');
            $pwd = $encoder->encodePassword($user, $pwd);
            $user->setPassword($pwd);

            $em->persist($user);
            $em->flush();

            $url = $this->generateUrl('app');
            return $this->redirect($url);
        } else {
            $registration = new User();

            $form = $this->createForm(new RegistrationType(), $registration, ['action' => $this->generateUrl('register'), 'method' => 'POST']);

            return $this->render('AppBundle:Default:register.html.twig', ['form' => $form->createView()]);
        }
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
                    return $this->render('AppBundle:Default:login.html.twig');
                }
            } else {
                return $this->render('AppBundle:Default:login.html.twig');
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
