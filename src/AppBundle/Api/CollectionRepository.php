<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 12/22/2016
 * Time: 3:38 PM
 */

namespace AppBundle\Api;


use Doctrine\ODM\MongoDB\DocumentRepository;

class CollectionRepository extends DocumentRepository
{
    public function findWhereQueryParamaters($filters, $range, $sort, $limit = null)
    {

        $qb= $this->createQueryBuilder();

        foreach ($filters as $key => $value ){
            $qb->field($key)->equals($value);
        }

        foreach ($sort as $key => $value ){
            $qb->sort($key, $value);
        }

        if(!empty($range)) {
            if( array_key_exists('desde', $range) ) $qb->field("created")->gte($range["desde"]);
            if( array_key_exists('hasta', $range) ) $qb->field("created")->lte($range["hasta"]);
        }

        if(!empty($limit)) {
            $qb->limit($limit);
        }

        $result = $qb->getQuery()->execute();

        return iterator_to_array($result, false);
    }
}