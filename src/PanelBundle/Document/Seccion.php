<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 11/26/2016
 * Time: 6:21 PM
 */

namespace PanelBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as Unique;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class Seccion
 * @package PanelBundle\Document
 * @ODM\Document(repositoryClass="PanelBundle\Repository\SeccionRepository")
 * @ODM\UniqueIndex(keys={"nombre"=1})
 * @Unique(fields={"nombre"}, message="Una aplicacion con ese nombre ya existe.")
 */
class Seccion
{
    /**
     * @var string
     * @ODM\Id(strategy="NONE")
     */
    private $id;

    /**
     * @var Aplicacion
     * @ODM\ReferenceOne(targetDocument="Aplicacion", cascade={"all"}, inversedBy="secciones")
     * @Assert\NotBlank()
     * @Serializer\Groups("aplicacion")
     */
    private $aplicacion;

    /**
     * @var string
     * @ODM\String()
     * @Assert\NotBlank()
     */
    private $nombre;

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
     * Set aplicacion
     *
     * @param Aplicacion $aplicacion
     * @return $this
     */
    public function setAplicacion(Aplicacion $aplicacion)
    {
        $this->aplicacion = $aplicacion;
        return $this;
    }

    /**
     * Get aplicacion
     *
     * @return Aplicacion $aplicacion
     */
    public function getAplicacion()
    {
        return $this->aplicacion;
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
    
}
