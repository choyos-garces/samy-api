<?php
namespace PanelBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as Unique;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Context\ExecutionContext;

/**
 * Class Recurso
 * @package ResourcesBundle\Document\Inventario
 * @ODM\Document(repositoryClass="PanelBundle\Repository\RecursoRepository")
 * @ODM\HasLifecycleCallbacks()
 * @ODM\UniqueIndex(keys={"nombre"=1})
 * @Unique(fields={"nombre"}, message="Un recurso con ese nombre ya existe.")
 */
class Recurso
{
    /**
     * @var string
     * @ODM\Id()
     */
    private $id;

    /**
     * @var string
     * @ODM\String()
     * @Assert\NotBlank(message="El nombre del recurso es requerrido.")
     */
    private $nombre;
    
    /**
     * @var Aplicacion
     * @ODM\ReferenceOne(targetDocument="Aplicacion")
     * @Assert\NotBlank()
     * @Serializer\MaxDepth(1)
     */
    private $aplicacion;

    /**
     * @var Seccion
     * @ODM\ReferenceOne(targetDocument="Seccion")
     * @Assert\NotBlank()
     * @Serializer\MaxDepth(1)
     */
    private $seccion;

    /**
     * @var string
     * @ODM\String()
     */
    private $detalle;

    /**
     * @var ArrayCollection
     * @ODM\ReferenceMany(targetDocument="Categoria", mappedBy="recurso", cascade={"all"})
     */
    private $categorias;

    /**
     * @var boolean
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
     * @ODM\PrePersist()
     */
    public function prePersist() {
        $this->created = new \DateTime();
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
     * @return Aplicacion
     */
    public function getAplicacion()
    {
        return $this->aplicacion;
    }

    /**
     * @param Aplicacion $aplicacion
     */
    public function setAplicacion($aplicacion)
    {
        $this->aplicacion = $aplicacion;
    }


    /**
     * @return Seccion
     */
    public function getSeccion()
    {
        return $this->seccion;
    }

    /**
     * @param Seccion $seccion
     */
    public function setSeccion($seccion)
    {
        $this->seccion = $seccion;
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
     * Set detalle
     *
     * @param string $detalle
     * @return $this
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;
        return $this;
    }

    /**
     * Get detalle
     *
     * @return string $detalle
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * @param ExecutionContext $context
     * @param $payload
     * @Assert\Callback()
     */
    public function validate(ExecutionContext $context, $payload) {
        if($this->getAplicacion() !=  null ) {
            if($this->getAplicacion()->getId() != $this->getSeccion()->getAplicacion()->getId()) {
                $context->buildViolation('La seccion y no pertenece a la aplicacion solicitada.')
                    ->atPath('aplicacion')
                    ->addViolation()
                ;
            }
        }
    }
    public function __construct()
    {
        $this->categorias = new ArrayCollection();
    }
    
    /**
     * Add categoria
     *
     * @param Categoria $categoria
     */
    public function addCategoria(Categoria $categoria)
    {
        $this->categorias[] = $categoria;
    }

    public function setCategorias(array $categorias) {
        $this->categorias = $categorias;
    }

    /**
     * Remove categoria
     *
     * @param Categoria $categoria
     */
    public function removeCategoria(Categoria $categoria)
    {
        $this->categorias->removeElement($categoria);
    }

    /**
     * Get categorias
     *
     * @return \Doctrine\Common\Collections\Collection $categorias
     */
    public function getCategorias()
    {
        return $this->categorias;
    }
}
