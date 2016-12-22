<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 12/13/2016
 * Time: 1:18 PM
 */

namespace InventarioBundle\Document;

use AdministracionBundle\Document\Bodega;
use AdministracionBundle\Document\Material;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class Movimiento
 * @ODM\Document(repositoryClass="InventarioBundle\Repository\MovimientoMaterialRepository")
 * @ODM\HasLifecycleCallbacks()
 */
class MovimientoMaterial
{
    const INVENTARIO_INGRESO = true;
    const INVENTARIO_EGRESO = false;
    
    /**
     * @var string
     * @ODM\Id()
     */
    private $id;

    /**
     * @var boolean
     * @ODM\Boolean()
     */
    private $accion;

    /**
     * @var Bodega
     * @ODM\ReferenceOne(targetDocument="AdministracionBundle\Document\Bodega")
     * @Serializer\MaxDepth(1)
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
     * @var float
     * @ODM\Float()
     */
    private $existente;

    /**
     * @var MovimientoInventario
     * @ODM\ReferenceOne(targetDocument="MovimientoInventario", inversedBy="materiales")
     */
    private $movimiento;
    
    /**
     * @var \DateTime
     * @ODM\Date()
     */
    private $created;
    
    /**
     * @ODM\PrePersist()
     */
    public function prePersist() {
        $this->created = new \DateTime();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isAccion()
    {
        return $this->accion;
    }

    /**
     * @param bool $accion
     */
    public function setAccion($accion)
    {
        $this->accion = $accion;
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
     * @return mixed
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * @param mixed $cantidad
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    }

    /**
     * @return float
     */
    public function getExistente()
    {
        return $this->existente;
    }

    /**
     * @param float $existente
     */
    public function setExistente($existente)
    {
        $this->existente = $existente;
    }

    /**
     * @return MovimientoInventario
     */
    public function getMovimiento()
    {
        return $this->movimiento;
    }

    /**
     * @param MovimientoInventario $movimiento
     */
    public function setMovimiento($movimiento)
    {
        $this->movimiento = $movimiento;
    }
    
    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Get accion
     *
     * @return boolean $accion
     */
    public function getAccion()
    {
        return $this->accion;
    }

    /**
     * Set created
     *
     * @param date $created
     * @return $this
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }
}
