<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 12/13/2016
 * Time: 1:19 PM
 */

namespace InventarioBundle\Document;

use AdministracionBundle\Document\Bodega;
use AdministracionBundle\Document\Productor;
use AdministracionBundle\Document\Proveedor;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class MovimientoDetalle
 * @package InventarioBundle\Document
 * @ODM\Document(repositoryClass="InventarioBundle\Repository\MovimientoDetalleRepository")
 */
class MovimientoDetalle
{

    const SALDO_INICIAL = "Saldo Inicial";
    const BODEGA = "Bodega";
    const PRODUCTOR = "Productor";
    const PROVEEDOR = "Proveedor";

    /**
     * @var string;
     * @ODM\Id()
     */
    private $id;

    /**
     * @var string;
     * @ODM\String()
     */
    private $documento;

    /**
     * @var string;
     * @Serializer\Exclude();
     */
    private $referencia;

    /**
     * @var string;
     * @ODM\String()
     */
    private $adicional;

    /**
     * @var Bodega
     * @ODM\ReferenceOne("AdministracionBundle\Document\Bodega")
     * @Serializer\MaxDepth(1)
     */
    private $bodega;

    /**
     * @var Proveedor
     * @ODM\ReferenceOne("AdministracionBundle\Document\Proveedor")
     * @Serializer\MaxDepth(1)
     */
    private $proveedor;

    /**
     * @var Productor
     * @ODM\ReferenceOne("AdministracionBundle\Document\Productor")
     * @Serializer\MaxDepth(1)
     */
    private $productor;

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getDocumento()
    {
        return $this->documento;
    }

    /**
     * @param string $documento
     */
    public function setDocumento($documento)
    {
        $this->documento = $documento;
    }

    /**
     * @return string
     */
    public function getReferencia()
    {
        return $this->referencia;
    }

    /**
     * @param string $referencia
     */
    public function setReferencia($referencia)
    {
        $this->referencia = $referencia;
    }

    /**
     * @return string
     */
    public function getAdicional()
    {
        return $this->adicional;
    }

    /**
     * @param string $adicional
     */
    public function setAdicional($adicional)
    {
        $this->adicional = $adicional;
    }

    /**
     * @return Bodega
     */
    public function getBodega()
    {
        return $this->bodega;
    }

    /**
     * @param Bodega $bodega
     */
    public function setBodega($bodega)
    {
        $this->bodega = $bodega;
    }

    /**
     * @return Proveedor
     */
    public function getProveedor()
    {
        return $this->proveedor;
    }

    /**
     * @param Proveedor $proveedor
     */
    public function setProveedor($proveedor)
    {
        $this->proveedor = $proveedor;
    }

    /**
     * @return Productor
     */
    public function getProductor()
    {
        return $this->productor;
    }

    /**
     * @param Productor $productor
     */
    public function setProductor($productor)
    {
        $this->productor = $productor;
    }


    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }
}
