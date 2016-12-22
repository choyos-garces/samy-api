<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 11/26/2016
 * Time: 5:25 PM
 */

namespace PanelBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as Unique;

/**
 * Class Aplicacion
 * @package PanelBundle\Document
 * @ODM\Document(repositoryClass="PanelBundle\Repository\AplicacionRepository")
 * @ODM\UniqueIndex(keys={"nombre"=1})
 * @Unique(fields={"nombre"}, message="Una aplicacion con ese nombre ya existe.")
 */
class Aplicacion
{
    /**
     * @var string
     * @ODM\Id(strategy="NONE")
     */
    protected $id;

    /**
     * @var string
     * @ODM\String()
     * @Assert\NotBlank()
     */
    protected $nombre;
    
    /**
     * @var ArrayCollection
     * @ODM\ReferenceMany(targetDocument="Seccion", mappedBy="aplicacion", cascade="persist")
     */
    protected $secciones;

    public function __construct()
    {
        $this->secciones = new ArrayCollection();
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * Set nombre
     *
     * @param string $nombre
     * @return $this
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string $nombre
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Add seccione
     *
     * @param Seccion $seccion
     */
    public function addSeccion(Seccion $seccion)
    {
        $this->secciones[] = $seccion;
    }

    /**
     * Remove secciones
     *
     * @param Seccion $seccion
     */
    public function removeSeccion(Seccion $seccion)
    {
        $this->secciones->removeElement($seccion);
    }

    /**
     * Get secciones
     *
     * @return \Doctrine\Common\Collections\Collection $secciones
     */
    public function getSecciones()
    {
        return $this->secciones;
    }
}
