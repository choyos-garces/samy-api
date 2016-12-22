<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 12/19/2016
 * Time: 8:18 AM
 */

namespace InventarioBundle\Controller;

use AppBundle\Api\BaseController;
use InventarioBundle\Document\GuiaTransferencia;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GuiasController
 * @package InventarioBundle\Controller
 * @Route("/guias")
 */
class GuiasController extends BaseController
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("GET")
     * @Route("", name="guias_lista")
     */
    public function indexAction()
    {
        $queryParameters = $this->get('app.collection_query_parameters');
        $filters = $queryParameters->getFilters();
        $range = $queryParameters->getRange();

        $dm = $this->get('doctrine_mongodb')->getManager();
        $guias = $dm->getRepository('InventarioBundle:GuiaTransferencia')
            ->findWhereQueryParamaters($filters, $range, ['created' => 'DSC']);
        
        $data = ["guias" => $guias];

        $context = new SerializationContext();
        $context->enableMaxDepthChecks();
        return $this->createApiResponse($data, 200, $context);
    }


    /**
     * @param GuiaTransferencia $guia
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("GET")
     * @Route("/{numeroGuia}", name="guia_view")
     */
    public function viewAction(GuiaTransferencia $guia)
    {
        return $this->createApiResponse($guia);
    }
}