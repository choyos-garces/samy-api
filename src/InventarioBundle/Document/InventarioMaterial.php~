<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 12/17/2016
 * Time: 5:56 PM
 */

namespace InventarioBundle\Document;

use AdministracionBundle\Document\Material;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class InventarioMaterial
 * @package InventarioBundle\Document
 * @ODM\Document(repositoryClass="InventarioBundle\Repository\InventarioMaterialRepository")
 * @ODM\HasLifecycleCallbacks()
 */
class InventarioMaterial
{
    /**
     * @var string;
     * @ODM\Id()
     */
    private $id;

    /**
     * @var Material
     * @ODM\ReferenceOne(targetDocument="AdministracionBundle\Document\Material")
     */
    private $material;

    /**
     * @var ArrayCollection | InventarioBodega[]
     * @ODM\ReferenceMany(targetDocument="InventarioBodega", mappedBy="inventarioMaterial", cascade={"persist"})
     * @Serializer\MaxDepth(1)
     */
    private $inventarioBodegas;

    /**
     * @var float
     * @ODM\Float()
     */
    private $cantidad;

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

    public function __construct()
    {
        $this->inventarioBodegas = new ArrayCollection();
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
     * Set material
     *
     * @param Material $material
     * @return $this
     */
    public function setMaterial(Material $material)
    {
        $this->material = $material;
        return $this;
    }

    /**
     * Get material
     *
     * @return Material $material
     */
    public function getMaterial()
    {
        return $this->material;
    }

    /**
     * Add inventarioBodega
     *
     * @param InventarioBodega $inventarioBodega
     */
    public function addInventarioBodega(InventarioBodega $inventarioBodega)
    {
        $this->inventarioBodegas[] = $inventarioBodega;
    }

    /**
     * Remove inventarioBodega
     *
     * @param InventarioBodega $inventarioBodega
     */
    public function removeInventarioBodega(InventarioBodega $inventarioBodega)
    {
        $this->inventarioBodegas->removeElement($inventarioBodega);
    }

    /**
     * Get inventarioBodegas
     *
     * @return ArrayCollection | InventarioBodega[] $inventarioBodegas
     */
    public function getInventarioBodegas()
    {
        return $this->inventarioBodegas;
    }

    /**
     * Set cantidad
     *
     * @param float $cantidad
     * @return $this
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
        return $this;
    }

    /**
     * Get cantidad
     *
     * @return float $cantidad
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return $this
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime $created
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return $this
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime $updated
     */
    public function getUpdated()
    {
        return $this->updated;
    }
}
