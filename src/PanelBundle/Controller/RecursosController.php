<?php

namespace PanelBundle\Controller;

use AppBundle\Api\ApiErrorException;
use AppBundle\Api\ApiErrorMessage;
use AppBundle\Api\BaseController;
use JMS\Serializer\SerializationContext;
use PanelBundle\Document\Categoria;
use PanelBundle\Document\Recurso;
use PanelBundle\Form\EditRecursoForm;
use PanelBundle\Form\NewRecursoForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class InventarioController
 * @package ResourcesBundle\Controller
 * @Route("/recursos")
 */
class RecursosController extends BaseController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("", name="recurso_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();


        if( $request->get("index") ) {
            $aplicaciones = $dm->getRepository("PanelBundle:Aplicacion")->findBy([], ["created" => "DSC"]);
            $data = ["index" => $aplicaciones];
            return $this->createApiResponse($data);
        }

        $queryParameters = $this->get('app.collection_query_parameters');
        $filters = $queryParameters->getFilters();
        $range = $queryParameters->getRange();
        
        $recursos = $dm->getRepository('PanelBundle:Recurso')
            ->findWhereQueryParamaters($filters, $range, ['created' => 'DSC']);
        
        $data = [ "recursos" => $recursos];
        return $this->createApiResponse($data);

    }

    /**
     * @param Recurso $recurso
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{id}", name="recurso_view")
     * @Method("GET")
     */
    public function viewAction(Recurso $recurso)
    {
        $context = new SerializationContext();
        $context->enableMaxDepthChecks();

        return $this->createApiResponse($recurso, 200, $context);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("POST")
     * @Route("", name="recurso_new")
     */
    public function newAction(Request $request)
    {
        $recurso = new Recurso();
        $form = $this->createForm(NewRecursoForm::class, $recurso);
        $this->processForm($request, $form);

        if(!$form->isValid()) {
            $this->throwApiValidationException($form);
        }

        $recurso->setActive(true);
        $dm = $this->get('doctrine_mongodb')->getManager();
        $dm->persist($recurso);
        $dm->flush();

        $context = new SerializationContext();
        $context->enableMaxDepthChecks();

        return $this->createApiResponse($recurso, 201);
    }

    /**
     * @param Request $request
     * @param Recurso $recurso
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method({"PUT", "PATCH"})
     * @Route("/{id}", name="recurso_edit")
     */
    public function editAction(Request $request, Recurso $recurso)
    {
        $form = $this->createForm(EditRecursoForm::class, $recurso);
        $this->processForm($request, $form);

        if(!$form->isValid()) {
            $this->throwApiValidationException($form);
        }

        /** @var Categoria[] $categorias */
        $categorias = $recurso->getCategorias();
        for($i = 0; $i < count($categorias); $i++) {
            $categorias[$i]->setRecurso($recurso);
        }

        $dm = $this->get('doctrine_mongodb')->getManager();
        $dm->persist($recurso);
        $dm->flush();

        return $this->createApiResponse($recurso);
    }
}
