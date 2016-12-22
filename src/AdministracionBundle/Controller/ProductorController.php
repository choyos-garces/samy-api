<?php

namespace AdministracionBundle\Controller;

use AdministracionBundle\Document\Productor;
use AdministracionBundle\Form\Productor\EditProductorType;
use AdministracionBundle\Form\Productor\NewProductorForm;
use AppBundle\Api\BaseController;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProductorController
 * @package AdministracionBundle\Controller
 * @Route("/productores")
 */
class ProductorController extends BaseController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("GET")
     * @Route("", name="productor_list")
     */
    public function indexAction()
    {
        $queryParameters = $this->get('app.collection_query_parameters');
        $filters = $queryParameters->getFilters();
        $range = $queryParameters->getRange();
        
        $dm = $this->get('doctrine_mongodb')->getManager();
        $productores = $dm->getRepository("AdministracionBundle:Productor")
            ->findWhereQueryParamaters($filters, $range, ['created' => 'DSC']);
        
        $data = ["productores" => $productores];
        
        $context = new SerializationContext();
        $context->enableMaxDepthChecks();
        return $this->createApiResponse($data);
    }

    /**
     * @param Productor $productor
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("GET")
     * @Route("/{id}", name="productor_view")
     */
    public function viewAction(Productor $productor)
    {
        return $this->createApiResponse($productor);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("POST")
     * @Route("", name="productor_new")
     */
    public function newAction(Request $request)
    {
        $productor = new Productor();
        $form = $this->createForm(NewProductorForm::class, $productor);
        $this->processForm($request, $form);

        if(!$form->isValid())
        {
            $this->throwApiValidationException($form);
        }

        $productor->setActive(true);
        $dm = $this->get('doctrine_mongodb')->getManager();
        $dm->persist($productor);
        $dm->flush();

        return $this->createApiResponse($productor, 201);
    }

    /**
     * @param Request $request
     * @param Productor $productor
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method({"PUT", "PATCH"})
     * @Route("/{id}", name="productor_edit")
     */
    public function editAction(Request $request, Productor $productor)
    {
        $form = $this->createForm(EditProductorType::class, $productor);
        $this->processForm($request, $form);

        if(!$form->isValid())
        {
            $this->throwApiValidationException($form);
        }

        $dm = $this->get('doctrine_mongodb')->getManager();
        $dm->persist($productor);
        $dm->flush();

        return $this->createApiResponse($productor, 200);
    }
}