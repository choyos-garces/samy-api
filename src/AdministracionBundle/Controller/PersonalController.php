<?php

namespace AdministracionBundle\Controller;

use AdministracionBundle\Document\Personal;
use AdministracionBundle\Form\Personal\EditPersonalForm;
use AdministracionBundle\Form\Personal\NewPersonalForm;
use AdministracionBundle\Form\Personal\SearchPersonalForm;
use AppBundle\Api\BaseController;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PersonalController
 * @package AdministracionBundle\Controller
 * @Route("/personal")
 */
class PersonalController extends BaseController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("GET")
     * @Route("", name="personal_list")
     */
    public function indexAction(Request $request)
    {
        if($request->get("codigo"))
        {
            $codigo = $this->generarCodigoPersonal();
            $data = ["codigo" => $codigo];
            return $this->createApiResponse($data);
        }

        $queryParameters = $this->get('app.collection_query_parameters');
        $filters = $queryParameters->getFilters();
        $range = $queryParameters->getRange();

        $dm = $this->get('doctrine_mongodb')->getManager();
        $personal = $dm->getRepository("AdministracionBundle:Personal")
            ->findWhereQueryParamaters($filters, $range, ['created' => 'DSC']);
        
        $data = ["personal" => $personal];
        
        $context = new SerializationContext();
        $context->enableMaxDepthChecks();
        return $this->createApiResponse($data);
    }

    /**
     * @param Personal $personal
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("GET")
     * @Route("/{id}", name="personal_view")
     */
    public function viewAction(Personal $personal)
    {
        return $this->createApiResponse($personal);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("POST")
     * @Route("", name="personal_new")
     */
    public function newAction(Request $request)
    {
        $personal = new Personal();
        $form = $this->createForm(NewPersonalForm::class, $personal);
        $this->processForm($request, $form);

        if(!$form->isValid())
        {
            $this->throwApiValidationException($form);
        }

        $personal->setActive(true);
        $dm = $this->get('doctrine_mongodb')->getManager();
        $dm->persist($personal);
        $dm->flush();

        return $this->createApiResponse($personal, 201);
    }

    /**
     * @param Request $request
     * @param Personal $personal
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method({"PUT", "PATCH"})
     * @Route("/{id}", name="personal_edit")
     */
    public function editAction(Request $request, Personal $personal)
    {
        $form = $this->createForm(EditPersonalForm::class, $personal);
        $this->processForm($request, $form);

        if(!$form->isValid())
        {
            $this->throwApiValidationException($form);
        }

        $dm = $this->get('doctrine_mongodb')->getManager();
        $dm->persist($personal);
        $dm->flush();

        return $this->createApiResponse($personal, 200);
    }

    /**
     * Genera codigo unico
     * @return string
     */
    public function generarCodigoPersonal()
    {
        $codigo = "P".random_int(1000, 9999);

        $dm = $this->get('doctrine_mongodb')->getManager();

        // Busca material con el codigo generado.
        $bodegas = $dm->getRepository('AdministracionBundle:Personal')->findBy(["codigo" => $codigo]);
        if(!empty($bodegas)) {
            // Encontro material, intenta de nuevo.
            return $this->generarCodigoPersonal();
        }

        // No es necesario generar otro codigo, retorna el valor.
        return $codigo;
    }
}