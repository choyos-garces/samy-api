<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 12/19/2016
 * Time: 1:16 AM
 */

namespace InventarioBundle\Document;


use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class GuiaTransferencia
 * @package InventarioBundle\Document
 * @ODM\Document(repositoryClass="InventarioBundle\Repository\GuiaTransferenciaRepository")
 * @ODM\HasLifecycleCallbacks()
 */
class GuiaTransferencia
{

    const STATUS_WAITING = "waiting";
    const STATUS_DONE = "done";
    const STATUS_ERROR = "error";

    /**
     * @var string
     * @ODM\String()
     * @ODM\Id(
     *     strategy="NONE"
     * )
     */
    private $numeroGuia;

    /**
     * @var MovimientoInventario
     * @ODM\ReferenceOne(targetDocument="MovimientoInventario")
     * @Serializer\MaxDepth(3)
     */
    private $movimientoOrigen;

    /**
     * @var MovimientoInventario
     * @ODM\ReferenceOne(targetDocument="MovimientoInventario")
     * @Serializer\MaxDepth(3)
     */
    private $movimientoDestino;

    /**
     * @var string
     * @ODM\String()
     */
    private $status;

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
     * Set numeroGuia
     *
     * @param string $numeroGuia
     * @return $this
     */
    public function setNumeroGuia($numeroGuia)
    {
        $this->numeroGuia = $numeroGuia;
        return $this;
    }

    /**
     * Get numeroGuia
     *
     * @return string $numeroGuia
     */
    public function getNumeroGuia()
    {
        return $this->numeroGuia;
    }

    /**
     * Set movimientoOrigen
     *
     * @param MovimientoInventario $movimientoOrigen
     * @return $this
     */
    public function setMovimientoOrigen(MovimientoInventario $movimientoOrigen)
    {
        $this->movimientoOrigen = $movimientoOrigen;
        return $this;
    }

    /**
     * Get movimientoOrigen
     *
     * @return MovimientoInventario $movimientoOrigen
     */
    public function getMovimientoOrigen()
    {
        return $this->movimientoOrigen;
    }

    /**
     * Set movimientoDestino
     *
     * @param MovimientoInventario $movimientoDestino
     * @return $this
     */
    public function setMovimientoDestino(MovimientoInventario $movimientoDestino)
    {
        $this->movimientoDestino = $movimientoDestino;
        return $this;
    }

    /**
     * Get movimientoDestino
     *
     * @return MovimientoInventario $movimientoDestino
     */
    public function getMovimientoDestino()
    {
        return $this->movimientoDestino;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return string $status
     */
    public function getStatus()
    {
        return $this->status;
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
