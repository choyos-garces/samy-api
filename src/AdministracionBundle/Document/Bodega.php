<?php
/**
 * Created by PhpStorm.
 * User: choyo
 * Date: 11/26/2016
 * Time: 8:26 AM
 */

namespace AdministracionBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContext;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class Bodega
 * @ODM\Document(repositoryClass="AdministracionBundle\Repository\BodegaRepository")
 * @ODM\HasLifecycleCallbacks()
 */
class Bodega
{
    /**
     * @var string
     * @ODM\Id()
     */
    private $id;

    /**
     * @var string
     * @ODM\String()
     * @Assert\NotBlank(message="El nombre no puede quedar en blanco")
     */
    private $nombre;

    /**
     * @var string
     * @ODM\String()
     */
    private $descripcion;

    /**
     * @var Personal
     * @ODM\ReferenceOne(targetDocument="AdministracionBundle\Document\Personal")
     * @Assert\NotNull(message="Un encargado es requerrido por bodega.")
     */
    private $encargado;

    /**
     * @var string
     * @ODM\String()
     * @Assert\NotBlank(message="El codigo es requerrido.")
     * @Serializer\MaxDepth(1)
     */
    private $codigo;

    /**
     * @var string
     * @ODM\Boolean()
     * @Assert\NotNull(message="Este campo no puede esta en blanco.", groups={"Edit"})
     */
    private $active;

    /**
     * @var \DateTime
     * @ODM\Date()
     */
    private $created;

    /**
     * @var \DateTime
     * @ODM\Date()
     */
    private $edited;

    /**
     * @ODM\PrePersist()
     */
    public function prePersist() {
        $date = new \DateTime();
        $this->created = $date;
        $this->edited = $date;
    }

    /**
     * @ODM\PreUpdate()
     */
    public function preUpdate() {
        $this->edited = new \DateTime();
    }


    /**
     * @param ExecutionContext $context
     * @param $payload
     * @Assert\Callback()
     */
    public function validate(ExecutionContext $context, $payload) {
        if($this->getEncargado()->isActive() != true) {
            $context->buildViolation('Personal no autorizado.')
                ->atPath('encargado')
                ->addViolation()
            ;
        }
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
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param string $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return Personal
     */
    public function getEncargado()
    {
        return $this->encargado;
    }

    /**
     * @param Personal $encargado
     */
    public function setEncargado($encargado)
    {
        $this->encargado = $encargado;
    }

    /**
     * Set codigo
     *
     * @param string $codigo
     * @return $this
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * Get codigo
     *
     * @return string $codigo
     */
    public function getCodigo()
    {
        return $this->codigo;
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
    public function isActive()
    {
        return $this->active;
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
     * @return \DateTime
     */
    public function getEdited()
    {
        return $this->edited;
    }

    /**
     * @param \DateTime $edited
     */
    public function setEdited($edited)
    {
        $this->edited = $edited;
    }
}
