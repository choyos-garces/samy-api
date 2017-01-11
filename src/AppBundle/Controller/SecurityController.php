<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 11/2/2016
 * Time: 2:14 PM
 */

namespace AppBundle\Controller;


use AppBundle\Form\LoginForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginForm::class, [
           "_usermane" => $lastUsername
        ]);

        return array(
            'form'  => $form->createView(),
            'error' => $error
        );
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        throw new \Exception('Go away!');
    }
}
