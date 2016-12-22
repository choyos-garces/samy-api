<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 11/30/2016
 * Time: 9:06 AM
 */

namespace PanelBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class Categoria
 * @package ResourcesBundle\Document
 * @ODM\Document(repositoryClass="PanelBundle\Repository\CategoriaRepository")
 * @Unique(fields={"nombre", "recurso"}, message="Una categoria con este nombre ya existe en esta Seccion.")
 */
class Categoria
{
    /**
     * @var string
     * @ODM\Id()
     */
    private $id;

    /**
     * @var string
     * @ODM\String()
     * @Assert\NotBlank()
     */
    private $nombre;

    /**
     * @var boolean
     * @ODM\Boolean()
     * @Assert\NotNull(message="Este campo no puede esta en blanco.", groups={"Edit"})
     */
    private $active;

    /**
     * @var Recurso
     * @ODM\ReferenceOne(targetDocument="Recurso", inversedBy="categorias")
     */
    private $recurso;

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
     * Set active
     *
     * @param boolean $active
     * @return $this
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * Get active
     *
     * @return boolean $active
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set recurso
     *
     * @param Recurso $recurso
     * @return $this
     */
    public function setRecurso(Recurso $recurso)
    {
        $this->recurso = $recurso;
        return $this;
    }

    /**
     * Get recurso
     *
     * @return Recurso $recurso
     */
    public function getRecurso()
    {
        return $this->recurso;
    }
}
