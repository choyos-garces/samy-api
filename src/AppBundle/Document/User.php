<?php
namespace AppBundle\Document;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as ODMUnique;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ODM\Document(repositoryClass="AppBundle\Repository\UserRepository")
 * @ODM\UniqueIndex(keys={"email"=1, "username"=1})
 * @ODMUnique(fields={"email", "username"}, message="Usuario debe ser unico")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @var string $id
     * @ODM\Id(strategy="auto")
     * @Serializer\Exclude()
     */
    private $id;

    /**
     * @var string
     * @ODM\Field(type="string")
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @var string
     * @ODM\Field(type="string")
     * @Serializer\Exclude()
     */
    private $password;

    /**
     * @Assert\NotBlank(groups={"Registration"})
     * @Serializer\Exclude()
     */
    private $plainPassword;

    /**
     * @var string
     * @ODM\Field(type="string")
     * @Assert\Email()
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @var array
     * @ODM\Field(type="collection")
     * @Serializer\Exclude()
     */
    private $roles;

    /**
     * @ODM\ReferenceMany(name="Client", cascade="all")
     * @Serializer\Exclude()
     */
    private $clients;

    /**
     * Get id
     *
     * @return string $id
     */
    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param $roles array
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    public function getSalt() { /** Not necesary **/ }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        $this->password = null;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }
    public function __construct()
    {
        $this->clients = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add client
     *
     * @param $client
     */
    public function addClient($client)
    {
        $this->clients[] = $client;
    }

    /**
     * Remove client
     *
     * @param $client
     */
    public function removeClient($client)
    {
        $this->clients->removeElement($client);
    }

    /**
     * Get clients
     *
     * @return \Doctrine\Common\Collections\Collection $clients
     */
    public function getClients()
    {
        return $this->clients;
    }
}
