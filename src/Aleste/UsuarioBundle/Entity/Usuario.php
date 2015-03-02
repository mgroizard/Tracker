<?php

namespace Aleste\UsuarioBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Aleste\UsuarioBundle\Entity\Repository\UsuarioRepository")
 * @Gedmo\Loggable
 * @UniqueEntity(fields="username", message="Este nombre de Usuario ya está en uso!")
 * @UniqueEntity(fields="email", message="Este Email ya está en uso!")
 */
class Usuario extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Aleste\UsuarioBundle\Entity\Grupo")
     * @ORM\JoinTable(name="usuario_grupo",
     *      joinColumns={@ORM\JoinColumn(name="usuario_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="grupo_id", referencedColumnName="id")}
     * )
     */
    protected $grupos;

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
     * @var boolean
     *
     * @ORM\Column(name="hasToChangePassword", type="boolean", nullable=true)          
     */
    private $hasToChangePassword;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->grupos = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Returns the user roles
     *
     * @return array The roles
     */
    public function getRoles()
    {
        $roles = $this->roles;

        foreach ($this->getGrupos() as $group) {
            $roles = array_merge($roles, $group->getRoles());
        }

        // we need to make sure to have at least one role
        $roles[] = static::ROLE_DEFAULT;

        return array_unique($roles);
    }


    /**
     * Add grupos
     *
     * @param \Aleste\UsuarioBundle\Entity\Grupo $grupos
     * @return Usuario
     */
    public function addGrupo(\Aleste\UsuarioBundle\Entity\Grupo $grupos)
    {
        $this->grupos[] = $grupos;

        return $this;
    }

    /**
     * Remove grupos
     *
     * @param \Aleste\UsuarioBundle\Entity\Grupo $grupos
     */
    public function removeGrupo(\Aleste\UsuarioBundle\Entity\Grupo $grupos)
    {
        $this->grupos->removeElement($grupos);
    }

    /**
     * Get grupos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGrupos()
    {
        return $this->grupos;
    }

    /**
     * Set grupos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function setGrupos(\Aleste\UsuarioBundle\Entity\Grupo $grupos)
    {
        return $this->grupos = $grupos; 
    }    

    /**
     * Set hasToChangePassword
     *
     * @param boolean $hasToChangePassword
     * @return Usuario
     */
    public function setHasToChangePassword($hasToChangePassword)
    {
        $this->hasToChangePassword = $hasToChangePassword;

        return $this;
    }

    /**
     * Get hasToChangePassword
     *
     * @return boolean 
     */
    public function getHasToChangePassword()
    {
        return $this->hasToChangePassword;
    }

    /**
     * Set persona
     *
     * @param \Aleste\GestionBundle\Entity\Persona $persona
     * @return Usuario
     */
    public function setPersona(\Aleste\GestionBundle\Entity\Persona $persona = null)
    {
        $this->persona = $persona;

        return $this;
    }

    /**
     * Get persona
     *
     * @return \Aleste\GestionBundle\Entity\Persona 
     */
    public function getPersona()
    {
        return $this->persona;
    }
}
