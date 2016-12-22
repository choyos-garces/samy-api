<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 12/15/2016
 * Time: 6:24 PM
 */

namespace InventarioBundle\Document;


use AdministracionBundle\Document\Bodega;
use AdministracionBundle\Document\Material;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class InventarioMaterial
 * @package InventarioBundle\Document
 * @ODM\Document(repositoryClass="InventarioBundle\Repository\InventarioBodegaRepository")
 * @ODM\HasLifecycleCallbacks()
 */
class InventarioBodega
{
    /**
     * @var string
     * @ODM\Id()
     */
    private $id;

    /**
     * @var Bodega
     * @ODM\ReferenceOne(targetDocument="AdministracionBundle\Document\Bodega")
     */
    private $bodega;

    /**
     * @var Material
     * @ODM\ReferenceOne(targetDocument="AdministracionBundle\Document\Material")
     */
    private $material;

    /**
     * @var float
     * @ODM\Float()
     */
    private $cantidad;

    /**
     * @var InventarioMaterial
     * @ODM\ReferenceOne(targetDocument="InventarioMaterial", inversedBy="inventarioBodegas", cascade={"persist"})
     * @Serializer\MaxDepth(1)
     */
    private $inventarioMaterial;

    /**
     * @var boolean
     * @ODM\Boolean()
     * @Serializer\Exclude()
     */
    private $saldoInicial;

    /**
     * @var \DateTime
     * @ODM\Date()
     */
    private $created;

    /**
     * @var \DateTime
     * @ODM\Date()
     */
    private $updated;

    /**
     * @ODM\PrePersist()
     */
    public function prePersist()
    {
        $date = new \DateTime();
        $this->created = $date;
        $this->updated = $date;
    }

    /**
     * @ODM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->updated = new \DateTime();
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
     * @return Material
     */
    public function getMaterial()
    {
        return $this->material;
    }

    /**
     * @param Material $material
     */
    public function setMaterial($material)
    {
        $this->material = $material;
    }

    /**
     * @return float
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * @param float $cantidad
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    }

    public function hasSaldoInicial()
    {
        return $this->saldoInicial;
    }

    /**
     * Get saldoInicial
     *
     * @return boolean $saldoInicial
     */
    public function getSaldoInicial()
    {
        return $this->saldoInicial;
    }

    /**
     * Set saldoInicial
     *
     * @param boolean $saldoInicial
     * @return $this
     */
    public function setSaldoInicial($saldoInicial)
    {
        $this->saldoInicial = $saldoInicial;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * Get id
     *
     * @return string $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get inventarioMaterial
     *
     * @return InventarioMaterial $inventarioMaterial
     */
    public function getInventarioMaterial()
    {
        return $this->inventarioMaterial;
    }

    /**
     * Set inventarioMaterial
     *
     * @param InventarioMaterial $inventarioMaterial
     * @return $this
     */
    public function setInventarioMaterial(InventarioMaterial $inventarioMaterial)
    {
        $this->inventarioMaterial = $inventarioMaterial;
        return $this;
    }
}
