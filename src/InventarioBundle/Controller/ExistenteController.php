<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 12/16/2016
 * Time: 3:12 PM
 */

namespace InventarioBundle\Controller;

use AppBundle\Api\BaseController;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ExistenteController
 * @package InventarioBundle\Controller
 * @Sensio\Route("/existente")
 */
class ExistenteController extends BaseController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Sensio\Method("GET")
     * @Sensio\Route("/bodegas", name="existente_bodegas")
     */
    public function bodegasAction()
    {
        $queryParameters = $this->get('app.collection_query_parameters');
        $filters = $queryParameters->getFilters();
        $range = $queryParameters->getRange();

        $dm = $this->get('doctrine_mongodb')->getManager();
        $existente = $dm->getRepository('InventarioBundle:InventarioBodega')
            ->findWhereQueryParamaters($filters, $range, ['created' => 'DSC']);

        $data = ["existente" => $existente, "flag" => empty($existente)];

        $context = new SerializationContext();
        $context->enableMaxDepthChecks();
        return $this->createApiResponse($data, 200, $context);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Sensio\Method("GET")
     * @Sensio\Route("/materiales", name="existente_materiales")
     */
    public function materialesAction()
    {
        $queryParameters = $this->get('app.collection_query_parameters');
        $filters = $queryParameters->getFilters();
        $range = $queryParameters->getRange();

        $dm = $this->get('doctrine_mongodb')->getManager();
        $existente = $dm->getRepository('InventarioBundle:InventarioMaterial')
            ->findWhereQueryParamaters($filters, $range, ['created' => 'DSC']);

        $data = ["existente" => $existente];

        $context = new SerializationContext();
        //$context->enableMaxDepthChecks();
        return $this->createApiResponse($data, 200, $context);
    }
}