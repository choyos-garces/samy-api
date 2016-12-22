<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 12/20/2016
 * Time: 8:30 AM
 */

namespace InventarioBundle\Controller;

use AppBundle\Api\BaseController;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class HistorialController
 * @package InventarioBundle\Controller
 * @Route("/historial")
 */
class HistorialController extends BaseController
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("GET")
     * @Route("/", name="historial_lista")
     */
    public function indexAction() {
        $queryParameters = $this->get('app.collection_query_parameters');
        $filters = $queryParameters->getFilters();
        $range = $queryParameters->getRange();

        $dm = $this->get('doctrine_mongodb')->getManager();
        $historial = $dm->getRepository('InventarioBundle:MovimientoMaterial')
            ->findWhereQueryParamaters($filters, $range, ['created' => 'DSC']);
        
        $data = [
            "historial" => $historial
        ];

        $context = new SerializationContext();
        $context->enableMaxDepthChecks();
        return $this->createApiResponse($data, 200, $context);
    }

}