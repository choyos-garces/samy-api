<?php

namespace AppBundle\Controller;

use AppBundle\Form\LoginForm;
use FOS\RestBundle\Controller\Annotations\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**k
 * Class OAuthController
 * @package AppBundle\Controller
 * @Route("/auth", name="oauth")
 */
class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/", name="auth_index")
     */
    public function indexAction(Request $request)
    {
        $json_data = json_decode($request->getContent(), true);
        $form = $this->createForm(LoginForm::class);
        $form->submit($json_data);

        $data = $form->getData();

        return new JsonResponse($request->getContent());
    }

    /**
     * @Route("/token", name="auth_token")
     * @Template()
     */
    public function tokenAction()
    {
        return [
            "token" => "e44rweruyt2m5ue435bg"
        ];
    }
}