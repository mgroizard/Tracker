<?php

namespace Aleste\SeguridadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Aleste\SeguridadBundle\Entity\Rol
 * @ORM\Table()
 * @ORM\Entity()
 */
class Rol implements RoleInterface
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
     * @var string $nombre
     *
     * @ORM\Column(name="nombre", type="string", length=255, unique=true)
     */
    private $nombre;

    /**
     * @ORM\ManyToMany(targetEntity="Usuario", mappedBy="roles", cascade={"all"})
     */
    private $usuarios;
  
    /**
     * @ORM\ManyToMany(targetEntity="Aleste\TrackerBundle\Entity\Actividad", mappedBy="roles", cascade={"all"})
     */
    private $actividades;
    
    /**
     * @var Herramienta
     *
     * @ORM\ManyToOne(targetEntity="Aleste\TrackerBundle\Entity\Herramienta", inversedBy="roles", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="herramienta_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $herramienta;
  
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
     * Set nombre
     *
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }
    
    public function getRole()
    {
        return $this->getNombre();
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usuarios = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add usuarios
     *
     * @param \Aleste\SeguridadBundle\Entity\Usuario $usuarios
     * @return Rol
     */
    public function addUsuario(\Aleste\SeguridadBundle\Entity\Usuario $usuarios)
    {
        $this->usuarios[] = $usuarios;

        return $this;
    }

    /**
     * Remove usuarios
     *
     * @param \Aleste\SeguridadBundle\Entity\Usuario $usuarios
     */
    public function removeUsuario(\Aleste\SeguridadBundle\Entity\Usuario $usuarios)
    {
        $this->usuarios->removeElement($usuarios);
    }

    /**
     * Get usuarios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }
    
    public function __toString()
    {
        return $this->getNombre();
    }

    /**
     * Add actividades
     *
     * @param \Aleste\TrackerBundle\Entity\Actividad $actividades
     * @return Rol
     */
    public function addActividade(\Aleste\TrackerBundle\Entity\Actividad $actividades)
    {
        $this->actividades[] = $actividades;

        return $this;
    }

    /**
     * Remove actividades
     *
     * @param \Aleste\TrackerBundle\Entity\Actividad $actividades
     */
    public function removeActividade(\Aleste\TrackerBundle\Entity\Actividad $actividades)
    {
        $this->actividades->removeElement($actividades);
    }

    /**
     * Get actividades
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActividades()
    {
        return $this->actividades;
    }
}
