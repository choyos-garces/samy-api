<?php

namespace AdministracionBundle\Controller;

use AdministracionBundle\Document\Proveedor;
use AdministracionBundle\Form\Proveedor\EditProveedorType;
use AdministracionBundle\Form\Proveedor\NewProveedorForm;
use AppBundle\Api\BaseController;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProveedorController
 * @package AdministracionBundle\Controller
 * @Route("/proveedores")
 */
class ProveedorController extends BaseController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("GET")
     * @Route("", name="proveedor_list")
     */
    public function indexAction()
    {
        $queryParameters = $this->get('app.collection_query_parameters');
        $filters = $queryParameters->getFilters();
        $range = $queryParameters->getRange();
        
        $dm = $this->get('doctrine_mongodb')->getManager();
        $proveedores = $dm->getRepository("AdministracionBundle:Proveedor")
            ->findWhereQueryParamaters($filters, $range, ['created' => 'DSC']);

        $data = ["proveedores" => $proveedores];

        $context = new SerializationContext();
        $context->enableMaxDepthChecks();
        return $this->createApiResponse($data);
    }

    /**
     * @param Proveedor $proveedor
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("GET")
     * @Route("/{id}", name="proveedor_view")
     */
    public function viewAction(Proveedor $proveedor)
    {
        return $this->createApiResponse($proveedor);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("POST")
     * @Route("", name="proveedor_new")
     */
    public function newAction(Request $request)
    {
        $proveedor = new Proveedor();
        $form = $this->createForm(NewProveedorForm::class, $proveedor);
        $this->processForm($request, $form);

        if(!$form->isValid())
        {
            $this->throwApiValidationException($form);
        }

        $proveedor->setActive(true);
        $dm = $this->get('doctrine_mongodb')->getManager();
        $dm->persist($proveedor);
        $dm->flush();

        return $this->createApiResponse($proveedor, 201);
    }

    /**
     * @param Request $request
     * @param Proveedor $proveedor
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method({"PUT", "PATCH"})
     * @Route("/{id}", name="proveedor_edit")
     */
    public function editAction(Request $request, Proveedor $proveedor)
    {
        $form = $this->createForm(EditProveedorType::class, $proveedor);
        $this->processForm($request, $form);

        if(!$form->isValid())
        {
            $this->throwApiValidationException($form);
        }

        $dm = $this->get('doctrine_mongodb')->getManager();
        $dm->persist($proveedor);
        $dm->flush();

        return $this->createApiResponse($proveedor, 200);
    }
}