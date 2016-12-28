<?php
namespace AdministracionBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as Unique;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class Personal
 * @package AdministracionBundle\Document
 * @ODM\Document(repositoryClass="AdministracionBundle\Repository\PersonalRepository")
 * @ODM\UniqueIndex(keys={"cedula"=1})
 * @Unique(fields={"cedula"}, message="Personal con esta cedula ya existe.")
 * @ODM\HasLifecycleCallbacks()
 */
class Personal
{
    /**
     * @var string
     * @ODM\Id()
     */
    private $id;

    /**
     * @var string
     * @ODM\Field(type="string") 
     * @Assert\NotBlank(message="El nombre no puede quedar en blanco.")
     */
    private $nombre;

    /**
     * @var string
     * @ODM\Field(type="string")
     * @Assert\NotBlank(message="El apellido no puede quedar en blanco.")
     */
    private $apellido;

    /**
     * @var string
     * @ODM\Field(type="string")
     * @Assert\Regex(pattern="/\d{10}$/", message="El numero de cedula no es valido.")
     */
    private $cedula;

    /**
     * @var string
     * @ODM\Field(type="string")
     * @Assert\Email(message="El correo no es valido.")
     */
    private $correo;

    /**
     * @var string
     * @ODM\Field(type="string")
     * @Assert\Regex(pattern="/^[A-Z]{1}\d{4}$/", message="El codigo no es valido.")
     */
    private $codigo;

    /**
     * @var string
     * @ODM\Field(type="string")
     * @Assert\Regex(pattern="/\d{6,10}$/", message="El numero de telefono no es valido.")
     */
    private $telefono;

    /**
     * @var string
     * @ODM\Field(type="string")
     * @Assert\Regex(pattern="/\d{10}$/", message="El numero de celular no es valido.")
     */
    private $celular;

    /**
     * @var boolean
     * @ODM\Field(type="boolean")
     * @Assert\NotNull(message="Este campo no puede esta en blanco.", groups={"Edit"})
     */
    private $active;

    /**
     * @var \DateTime
     * @ODM\Field(type="date")
     */
    private $created;

    /**
     * @var \DateTime
     * @ODM\Field(type="date")
     */
    private $edited;

    /**
     * @ODM\PrePersist()
     */
    public function prePersist() {
        $this->created = new \DateTime();
        $this->edited = new \DateTime();
    }

    /**
     * @ODM\PreUpdate()
     */
    public function preUpdate() {
        $this->edited = new \DateTime();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
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
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * @param string $apellido
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }

    /**
     * @return string
     */
    public function getCedula()
    {
        return $this->cedula;
    }

    /**
     * @param string $cedula
     */
    public function setCedula($cedula)
    {
        $this->cedula = $cedula;
    }

    /**
     * @return string
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * @param string $correo
     */
    public function setCorreo($correo)
    {
        $this->correo = $correo;
    }

    /**
     * @return mixed
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param mixed $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param string $telefono
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    /**
     * @return string
     */
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * @param string $celular
     */
    public function setCelular($celular)
    {
        $this->celular = $celular;
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
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
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