<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 12/15/2016
 * Time: 4:32 PM
 */

namespace InventarioBundle\Document;

use AdministracionBundle\Document\Bodega;
use AppBundle\Document\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class MovimientoInventario
 * @ODM\Document(repositoryClass="InventarioBundle\Repository\MovimientoInventarioRepository")
 * @ODM\HasLifecycleCallbacks()
 */
class MovimientoInventario
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
     * @Assert\NotNull()
     * @ODM\Boolean()
     */
    private $accion;

    /**
     * @var Bodega
     * @Assert\NotNull()
     * @ODM\ReferenceOne(targetDocument="AdministracionBundle\Document\Bodega")
     * @Serializer\MaxDepth(1)
     */
    private $bodega;

    /**
     * @var MovimientoDetalle
     * @Assert\NotNull()
     * @ODM\ReferenceOne(targetDocument="InventarioBundle\Document\MovimientoDetalle", cascade={"persist"})
     */
    private $detalle;

    /**
     * @var ArrayCollection | MovimientoMaterial[]
     * @Assert\NotNull()
     * @ODM\ReferenceMany(targetDocument="MovimientoMaterial", mappedBy="movimiento", cascade={"persist"})
     */
    private $materiales;

    /**
     * @var string;
     * @ODM\String()
     */
    private $observaciones;

    /**
     * @var \DateTime
     * @Assert\DateTime()
     * @ODM\Date()
     */
    private $created;

    /**
     * @var User
     * @ODM\ReferenceOne(targetDocument="AppBundle\Document\User")
     */
    private $createdBy;

    public function __construct()
    {
        $this->materiales = new ArrayCollection();
    }

    /**
     * @ODM\PrePersist()
     */
    public function prePersist()
    {
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
     * Get accion
     *
     * @return boolean $accion
     */
    public function getAccion()
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
     * @return MovimientoDetalle
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * @param MovimientoDetalle $detalle
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;
    }

    /**
     * @return string
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * @param string $observaciones
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
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
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param User $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * Add materiale
     *
     * @param MovimientoMaterial $materiale
     */
    public function addMateriale(MovimientoMaterial $materiale)
    {
        $this->materiales[] = $materiale;
    }

    /**
     * Remove materiale
     *
     * @param MovimientoMaterial $materiale
     */
    public function removeMateriale(MovimientoMaterial $materiale)
    {
        $this->materiales->removeElement($materiale);
    }

    /**
     * Get materiales
     *
     * @return \Doctrine\Common\Collections\Collection | MovimientoMaterial[] $materiales
     */
    public function getMateriales()
    {
        return $this->materiales;
    }
}
