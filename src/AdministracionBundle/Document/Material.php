<?php
namespace AdministracionBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use PanelBundle\Document\Categoria;
use PanelBundle\Document\Recurso;
use PanelBundle\Document\Seccion;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Context\ExecutionContext;

/**
 * Class Material
 * @ODM\Document(repositoryClass="AdministracionBundle\Repository\MaterialRepository")
 * @ODM\HasLifecycleCallbacks()
 */
class Material
{
    /**
     * @var string
     * @ODM\Id()
     */
    protected $id;

    /**
     * @var Recurso
     * @ODM\ReferenceOne("PanelBundle\Document\Recurso")
     * @Assert\NotNull(message="Tipo de material no assignado.")
     * @Serializer\MaxDepth(2)
     */
    protected $tipoMaterial;

    /**
     * @var Categoria
     * @ODM\ReferenceOne("PanelBundle\Document\Categoria")
     * @Assert\NotNull(message="Categoria no assignada.")
     * @Serializer\MaxDepth(2)
     */
    protected $categoria;

    /**
     * @var string
     * @ODM\Field(type="string")
     * @Assert\NotBlank(message="EL nombre no puede quedar en blanco.")
     */
    protected $nombre;

    /**
     * @var string
     * @ODM\Field(type="string")
     * @Assert\NotBlank(message="Un codigo tiene que ser generado.")
     */
    protected $codigo;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $descripcion;

    /**
     * @var boolean
     * @ODM\Field(type="boolean")
     * @Assert\NotNull(message="Un valor es requerrido.", groups={"Edit"})
     */
    protected $active;

    /**
     * @var \DateTime
     * @ODM\Field(type="date")
     */
    protected $created;

    /**
     * @var \DateTime
     * @ODM\Field(type="date")
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
        if($this->getTipoMaterial()->getNombre() == "Tipo Material") {
            $context->buildViolation('El material no pertenece a la recurso solicitado.')
                ->atPath('tipoMaterial')
                ->addViolation()
            ;
        }

        if($this->getTipoMaterial()->getActive() == false) {
            $context->buildViolation('Este tipo de material a sido descontinuado.')
                ->atPath('tipoMaterial')
                ->addViolation()
            ;
        }

        if($this->getCategoria()->getActive() == false){
            $context->buildViolation('Esta categoria a sido descontinuada.')
                ->atPath('categoria')
                ->addViolation()
            ;
        }

        /** @var Categoria[] $categorias */
        $categorias = $this->getTipoMaterial()->getCategorias();
        $categoriaExist = false;

        foreach ($categorias as $categoria) {
            if( $categoria->getId() == $this->categoria->getId()) {
                $categoriaExist = true;
            }
        }

        if( !$categoriaExist ) {
            $context->buildViolation('Categoria assignada no pertenece al Tipo de material.')
                ->atPath('categoria')
                ->addViolation()
            ;
        }
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Recurso
     */
    public function getTipoMaterial()
    {
        return $this->tipoMaterial;
    }

    /**
     * @param Seccion $tipoMaterial
     */
    public function setTipoMaterial($tipoMaterial)
    {
        $this->tipoMaterial = $tipoMaterial;
    }

    /**
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param string $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return Categoria
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * @param Seccion $categoria
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
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
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
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
     * @param /DateTime $created
     * @return $this
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEdited()
    {
        return $this->edited;
    }

    /**
     * @param mixed $edited
     * @return $this
     */
    public function setEdited($edited)
    {
        $this->edited = $edited;
        return $this;
    }


}
