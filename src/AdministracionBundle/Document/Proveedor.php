<?php
namespace AdministracionBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as Unique;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class Productor
 * @package AdministracionBundle\Document
 * @ODM\Document(repositoryClass="AdministracionBundle\Repository\ProveedorRepository")
 * @ODM\UniqueIndex(keys={"ruc"=1})
 * @Unique(fields={"ruc"}, message="Personal con esta cedula o R.U.C. ya existe.")
 * @ODM\HasLifecycleCallbacks()
 */
class Proveedor
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
     * @Assert\Regex(pattern="/\d{10,13}$/", message="Cedula o R.U.C. invalido.")
     */
    private $ruc;

    /**
     * @var string
     * @ODM\Field(type="string")
     * @Assert\Email(message="El correo no es valido.")
     */
    private $correo;

    /**
     * @var string
     * @ODM\Field(type="string")
     * @Assert\Regex(pattern="/\d{6,10}$/", message="El numero de telefono no es valido.")
     */
    private $telefono;

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
    public function getRuc()
    {
        return $this->ruc;
    }

    /**
     * @param string $ruc
     */
    public function setRuc($ruc)
    {
        $this->ruc = $ruc;
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