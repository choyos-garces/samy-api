<?php

namespace AdministracionBundle\Controller;

use AdministracionBundle\Form\Bodega\EditBodegaForm;
use AdministracionBundle\Form\Bodega\NewBodegaForm;
use AppBundle\Api\BaseController;
use AdministracionBundle\Document\Bodega;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Bodegas
 * @package AdministracionBundle\Controller
 * @Route("/bodegas")
 * @Method("GET")
 */
class BodegasController extends BaseController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("GET")
     * @Route("", name="bogegas_list")
     */
    public function indexAction(Request $request)
    {
        if($request->get("codigo")) {
            $codigo = $this->generarCodigoBodega();
            $data = ["codigo" => $codigo];
            return $this->createApiResponse($data, 200);
        }

        $queryParameters = $this->get('app.collection_query_parameters');
        $filters = $queryParameters->getFilters();
        $range = $queryParameters->getRange();

        $dm = $this->get('doctrine_mongodb')->getManager();
        $bodegas = $dm->getRepository('AdministracionBundle:Bodega')
            ->findWhereQueryParamaters($filters, $range, ['created' => 'DSC']);

        $data = [ "bodegas" => $bodegas ];

        $context = new SerializationContext();
        $context->enableMaxDepthChecks();
        return $this->createApiResponse($data, 200, $context);
    }

    /**
     * @param Bodega $bodega
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("GET")
     * @Route("/{id}", name="bodega_view")
     */
    public function viewAction(Bodega $bodega)
    {
        $context = new SerializationContext();
        $context->enableMaxDepthChecks();

        return $this->createApiResponse($bodega, 200, $context);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("POST")
     * @Route("", name="bodega_new")
     */
    public function newAction(Request $request)
    {
        $bodega = new Bodega();
        $form = $this->createForm(NewBodegaForm::class, $bodega);
        $this->processForm($request, $form);

        if(!$form->isValid())
        {
            $this->throwApiValidationException($form);
        }

        $bodega->setActive(true);
        $dm = $this->get('doctrine_mongodb')->getManager();
        $dm->persist($bodega);
        $dm->flush();

        $context = new SerializationContext();
        $context->enableMaxDepthChecks();

        return $this->createApiResponse($bodega, 201);
    }

    /**
     * @param Request $request
     * @param Bodega $bodega
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method({"PUT", "PATCH"})
     * @Route("/{id}", name="bodega_edit")
     */
    public function editAction(Request $request, Bodega $bodega)
    {
        $form = $this->createForm(EditBodegaForm::class, $bodega);
        $this->processForm($request, $form);

        if(!$form->isValid()) {
            $this->throwApiValidationException($form);
        }

        $dm = $this->get('doctrine_mongodb')->getManager();
        $dm->persist($bodega);
        $dm->flush();

        $context = new SerializationContext();
        $context->enableMaxDepthChecks();

        return $this->createApiResponse($bodega, 201);
    }

    /**
     * Genera codigo unico
     *
     * @return string
     */
    public function generarCodigoBodega()
    {
        $codigo = "B".random_int(1000, 9999);

        $dm = $this->get('doctrine_mongodb')->getManager();

        // Busca material con el codigo generado.
        $bodegas = $dm->getRepository('AdministracionBundle:Bodega')->findBy(["codigo" => $codigo]);
        if(!empty($bodegas)) {
            // Encontro material, intenta de nuevo.
            return $this->generarCodigoBodega();
        }

        // No es necesario generar otro codigo, retorna el valor.
        return $codigo;
    }
}
