<?php

namespace Aleste\TrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Herramienta
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Herramienta
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
     * @ORM\OneToMany(targetEntity="Aleste\SeguridadBundle\Entity\Usuario", mappedBy="herramienta", cascade={"all"})  
     */
    private $usuarios;
    
    /**
     * @ORM\OneToMany(targetEntity="Proyecto", mappedBy="herramienta", cascade={"all"})  
     */
    private $proyectos;
    
    /**
     * @ORM\OneToMany(targetEntity="TipoRequerimiento", mappedBy="herramienta", cascade={"all"})  
     */
    private $tiposRequerimientos;
    
    /**
     * @ORM\OneToMany(targetEntity="Actividad", mappedBy="herramienta", cascade={"all"})  
     */
    private $actividades;
    
    /**
     * @ORM\OneToMany(targetEntity="EstadoTipoRequerimiento", mappedBy="herramienta", cascade={"all"})  
     */
    private $tiposEstados;
    
    /**
     * @ORM\OneToMany(targetEntity="Aleste\SeguridadBundle\Entity\Rol", mappedBy="herramienta", cascade={"all"})  
     */
    private $roles;
    
    public function __toString(){
        return (String)$this->getId();
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usuarios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->proyectos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tiposRequerimientos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->actividades = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tiposEstados = new \Doctrine\Common\Collections\ArrayCollection();
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add usuarios
     *
     * @param \Aleste\SeguridadBundle\Entity\Usuario $usuarios
     * @return Herramienta
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

    /**
     * Add proyectos
     *
     * @param \Aleste\TrackerBundle\Entity\Proyecto $proyectos
     * @return Herramienta
     */
    public function addProyecto(\Aleste\TrackerBundle\Entity\Proyecto $proyectos)
    {
        $this->proyectos[] = $proyectos;

        return $this;
    }

    /**
     * Remove proyectos
     *
     * @param \Aleste\TrackerBundle\Entity\Proyecto $proyectos
     */
    public function removeProyecto(\Aleste\TrackerBundle\Entity\Proyecto $proyectos)
    {
        $this->proyectos->removeElement($proyectos);
    }

    /**
     * Get proyectos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProyectos()
    {
        return $this->proyectos;
    }

    /**
     * Add tiposRequerimientos
     *
     * @param \Aleste\TrackerBundle\Entity\TipoRequerimiento $tiposRequerimientos
     * @return Herramienta
     */
    public function addTiposRequerimiento(\Aleste\TrackerBundle\Entity\TipoRequerimiento $tiposRequerimientos)
    {
        $this->tiposRequerimientos[] = $tiposRequerimientos;

        return $this;
    }

    /**
     * Remove tiposRequerimientos
     *
     * @param \Aleste\TrackerBundle\Entity\TipoRequerimiento $tiposRequerimientos
     */
    public function removeTiposRequerimiento(\Aleste\TrackerBundle\Entity\TipoRequerimiento $tiposRequerimientos)
    {
        $this->tiposRequerimientos->removeElement($tiposRequerimientos);
    }

    /**
     * Get tiposRequerimientos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTiposRequerimientos()
    {
        return $this->tiposRequerimientos;
    }

    /**
     * Add actividades
     *
     * @param \Aleste\TrackerBundle\Entity\Actividad $actividades
     * @return Herramienta
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

    /**
     * Add tiposEstados
     *
     * @param \Aleste\TrackerBundle\Entity\EstadoTipoRequerimiento $tiposEstados
     * @return Herramienta
     */
    public function addTiposEstado(\Aleste\TrackerBundle\Entity\EstadoTipoRequerimiento $tiposEstados)
    {
        $this->tiposEstados[] = $tiposEstados;

        return $this;
    }

    /**
     * Remove tiposEstados
     *
     * @param \Aleste\TrackerBundle\Entity\EstadoTipoRequerimiento $tiposEstados
     */
    public function removeTiposEstado(\Aleste\TrackerBundle\Entity\EstadoTipoRequerimiento $tiposEstados)
    {
        $this->tiposEstados->removeElement($tiposEstados);
    }

    /**
     * Get tiposEstados
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTiposEstados()
    {
        return $this->tiposEstados;
    }

    /**
     * Add roles
     *
     * @param \Aleste\SeguridadBundle\Entity\Rol $roles
     * @return Herramienta
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
     * Get roles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRoles()
    {
        return $this->roles;
    }
}
