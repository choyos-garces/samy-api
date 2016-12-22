<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 12/21/2016
 * Time: 9:26 AM
 */

namespace AppBundle\Api;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class CollectionQueryParameters
{
    /**
     * @var array
     */
    private $filters = [];

    /**
     * @var array
     */
    private $range = [];

    /**
     * @var int
     */
    private $limit = [];

    /**
     * @var int
     */
    private $offset = 0;
    /**
     * @var Request
     */
    private $request;

    function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        /** @var array $requestFilters */
        $requestFilters = $this->request->get('filters');
        if( !empty($requestFilters) ) {
            foreach ($requestFilters as $key => &$filter) {
                if( strpos($key, 'active') !== false ) {
                    $filter = ( $filter == "true" )? true  : false;
                }

                if( strpos($key, 'id') !== false ) {
                    if( strpos($filter, "_") != 0) $filter = new \MongoId($filter);
                }
            }

            $this->filters = $requestFilters;
        }

        return $this->filters;
    }

    /**
     * @return array
     */
    public function getRange()
    {
        $requestRange = $this->request->get('range');

        if(!empty($requestRange)) {
            foreach ($requestRange as $key => $value) {
                if( !empty($value) ) {
                    $this->range[$key] = new \DateTime($value);
                }
            }
        }

        return $this->range;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        $this->limit = (!empty($this->request->get('limit'))) ?: null;

        return $this->limit;
    }
}