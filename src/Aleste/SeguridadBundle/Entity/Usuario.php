<?php

namespace Aleste\SeguridadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchy;
use Symfony\Component\Security\Core\Role\Role;

/**
 * Aleste\SeguridadBundle\Entity\Usuario
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Aleste\SeguridadBundle\Entity\Repository\UsuarioRepository")
 */

class Usuario implements UserInterface, \Serializable
{  
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var string $usuario
     *
     * @ORM\Column(name="usuario", type="string", length=50, unique=true)
     */
    protected $usuario;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    protected $password;

    /**
     * @var string $salt
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;
    
    /**
     * @var \Persona
     *
     * @ORM\ManyToOne(targetEntity="Aleste\TrackerBundle\Entity\Persona", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="persona_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $persona;
    
    /**
     * @ORM\ManyToMany(targetEntity="Rol", inversedBy="usuarios")
     * @ORM\JoinTable(name="usuario_rol",
     *      joinColumns={@ORM\JoinColumn(name="usuario_id", referencedColumnName="id", onDelete="RESTRICT")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="rol_id", referencedColumnName="id",onDelete="RESTRICT")}
     *      )
     */
   
    private $roles;
        
    /**
     * @var ContainerInterface
     */
    private $container;
    
    /**
     * @var Herramienta
     *
     * @ORM\ManyToOne(targetEntity="Aleste\TrackerBundle\Entity\Herramienta", inversedBy="usuarios", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="herramienta_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $herramienta;
    
    /**
     * Set usuario
     *
     * @param string $usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * Get usuario
     *
     * @return string 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
       $this->password = $password;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function __construct()
    {
        $this->salt = md5(uniqid(null, true));
    }
    
    public function __toString()
    {
        return  $this->getUsername();
    }
    
    public function getUsername()
    {
        return $this->getUsuario();
    }
    
    /**
     * Set salt
     *
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }
    
    public function getSalt()
    {
        return $this->salt;
    }
    
    public function equals(UserInterface $user)
    {
             
       if (!$user instanceof Usuario) {
            return false;
       }
       
        if ($this->password !== ($user->getPassword())) {
            return false;
        }

        if ($this->usuario !== $user->getUsername()) {
            return false;
        }
    
        return true;
    }
    
    public function eraseCredentials()
    {
    }

    public function serialize()
    {
        return serialize(array($this->getId(),$this->getUsername()));
    }
    
    public function unserialize($data)
    {
        list($this->id,$this->usuario)= unserialize($data);
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function setContainer($container)
    {
        $this->container = $container;
    }
    
    public function getRoles()
    {
        if($this->roles)
        {
            return $this->roles->toArray();
        }
        
        return $this->roles;
    }

    /**
     * Set persona
     *
     * @param \Aleste\TrackerBundle\Entity\Persona $persona
     * @return Usuario
     */
    public function setPersona(\Aleste\TrackerBundle\Entity\Persona $persona = null)
    {
        $this->persona = $persona;

        return $this;
    }

    /**
     * Get persona
     *
     * @return \Aleste\TrackerBundle\Entity\Persona 
     */
    public function getPersona()
    {
        return $this->persona;
    }

    /**
     * Add roles
     *
     * @param \Aleste\SeguridadBundle\Entity\Rol $roles
     * @return Usuario
     */
    public function addRole(\Aleste\SeguridadBundle\Entity\Rol $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \Aleste\SeguridadBundle\Entity\Rol $roles
     */
    public function removeRole(\Aleste\SeguridadBundle\Entity\Rol $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Set herramienta
     *
     * @param \Aleste\TrackerBundle\Entity\Herramienta $herramienta
     * @return Usuario
     */
    public function setHerramienta(\Aleste\TrackerBundle\Entity\Herramienta $herramienta)
    {
        $this->herramienta = $herramienta;

        return $this;
    }

    /**
     * Get herramienta
     *
     * @return \Aleste\TrackerBundle\Entity\Herramienta 
     */
    public function getHerramienta()
    {
        return $this->herramienta;
    }
}
