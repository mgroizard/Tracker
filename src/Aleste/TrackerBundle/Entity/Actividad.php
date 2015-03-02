<?php

namespace Aleste\TrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Aleste\TrackerBundle\Entity\Actividad
 *
 * @ORM\Table()
 * @ORM\Entity()
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *                        "actividad"              = "Actividad",
 *                        "simple"                 = "Simple",
 *                        "compuesta"              = "Compuesta",
 *                        "agregarProyecto"        = "AgregarProyecto",
 *                        "agregarRequerimiento"   = "AgregarRequerimiento",
 *                        "agregarUsuario"         = "AgregarUsuario" ,
 *                        "borrarUsuario"          = "BorrarUsuario",
 *                        "agregarMiembroProyecto" = "AgregarMiembroProyecto",
 *                        "asignarRolMiembro"      = "AsignarRolMiembro",
 *                        "cambiarTipReqEstado"    = "CambiarTipoRequerimientoEstado",
 *                        "borrarRequerimiento"    = "BorrarRequerimiento",
 *                        "borrarProyecto"         = "BorrarProyecto",
 *                        })
 */
class Actividad implements ActividadInterface
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100)
     * @Assert\NotBlank()     
     */
    private $nombre;
    
    /**
     * @ORM\ManyToMany(targetEntity="Aleste\SeguridadBundle\Entity\Rol", inversedBy="actividades")
     * @ORM\JoinTable(name="actividades_rol",
     *      joinColumns={@ORM\JoinColumn(name="actividad_id", referencedColumnName="id", onDelete="RESTRICT")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="rol_id", referencedColumnName="id",onDelete="RESTRICT")}
     *      )
     */
    private $roles;
    
    /**
     * @ORM\ManyToOne(targetEntity="Compuesta", inversedBy="actividades")
     */ 
    private $compuesta;
    
    /**
     * @var Herramienta
     *
     * @ORM\ManyToOne(targetEntity="Herramienta", inversedBy="actividades", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="herramienta_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $herramienta;
    
    public function excecute(){}
    
    /**
     * Constructor
     */
    public function __construct()
    {
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
     * Set nombre
     *
     * @param string $nombre
     * @return Actividad
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
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

    /**
     * Add roles
     *
     * @param \Aleste\SeguridadBundle\Entity\Rol $roles
     * @return Actividad
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

    /**
     * Set compuesta
     *
     * @param \Aleste\TrackerBundle\Entity\Compuesta $compuesta
     * @return Actividad
     */
    public function setCompuesta(\Aleste\TrackerBundle\Entity\Compuesta $compuesta = null)
    {
        $this->compuesta = $compuesta;

        return $this;
    }

    /**
     * Get compuesta
     *
     * @return \Aleste\TrackerBundle\Entity\Compuesta 
     */
    public function getCompuesta()
    {
        return $this->compuesta;
    }

    /**
     * Set herramienta
     *
     * @param \Aleste\TrackerBundle\Entity\Herramienta $herramienta
     * @return Actividad
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
