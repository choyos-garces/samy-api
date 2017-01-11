<?php

namespace AppBundle\Controller;

use AppBundle\Document\User;
use AppBundle\Form\Type\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController
 * @package AppBundle\Controller
 * @Route("/user", name="user")
 * @Security("has_role('ROLE_ADMIN')")
 */
class UserController extends Controller
{
    /**
     * @return array
     * @Route("/", name="users")
     * @Template()
     */
    public function indexAction() {
        $dm = $this->get("doctrine_mongodb")->getManager();
        $users = $dm->getRepository("AppBundle:User")->findAll();

        return [ "users" => $users ];
    }

    /**
     * @param User $user
     * @return array
     * @Route("/show/{username}", name="user_show")
     * @Template()
     */
    public function showAction(User $user)
    {
        return [ "user" => $user ];
    }

    /**
     * @param Request $request
     * @return array
     * @Route("/new", name="user_new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(UserType::class, new User(), [
            "validation_groups" => ['Default', 'Registration']
        ]);
        $form->handleRequest($request);

        if($form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $dm = $this->get('doctrine_mongodb')->getManager();
            $user->setRoles(['ROLE_USER']);
            $dm->persist($user);
            $dm->flush();
            $dm->clear();

            $this->addFlash('success', "Usuario {$user->getUsername()} ({$user->getEmail()}) creado con exito");
            return $this->redirectToRoute("users");

        }
        return [
            "form" => $form->createView()
        ];
    }

    /**
     * @param User $user
     * @param Request $request
     * @return array
     * @Route("/edit/{username}", name="user_edit")
     * @Template()
     */
    public function editAction(User $user, Request $request)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();

        $form = $this->createForm(UserType::class, $user, [
            "validation_groups" =>  ['Default']
        ]);

        $form->handleRequest($request);

        if($form->isValid()) {
            $user = $form->getData();
            $dm->persist($user);
            $dm->flush();

            $this->addFlash('success', "Usuario {$user->getUsername()} ({$user->getEmail()}) editado con exito");
            return $this->redirectToRoute("users");

        }
        return [
            "user" => $user,
            "form" => $form->createView()
        ];
    }

}
