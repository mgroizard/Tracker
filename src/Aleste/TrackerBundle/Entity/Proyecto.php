<?php

namespace Aleste\TrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Proyecto
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Proyecto
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
     * @ORM\Column(name="nombre", type="string", length=100, nullable=false)
     */
    private $nombre;
 
    /**
     * @var string $fecha
     *
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;
    
    /**
     * @ORM\ManyToMany(targetEntity="Prioridad", inversedBy="proyectos")
     * @ORM\JoinTable(name="proyectos_prioridad",
     *      joinColumns={@ORM\JoinColumn(name="proyecto_id", referencedColumnName="id", onDelete="RESTRICT")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="prioridad_id", referencedColumnName="id",onDelete="RESTRICT")}
     *      )
     */
    private $prioridades;
    
    /**
     * @ORM\OneToMany(targetEntity="Requerimiento", mappedBy="proyecto")  
     */
    private $requerimientos;
    
    /**
     * @ORM\OneToOne(targetEntity="GrafoTipoRequerimiento", mappedBy="proyecto")
     **/
    private $grafoTipoRequerimiento;
    
    /**
     * @var Herramienta
     *
     * @ORM\ManyToOne(targetEntity="Herramienta", inversedBy="proyectos",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="herramienta_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $herramienta;
    
    /**
     * @var Periodo
     *
     * @ORM\ManyToOne(targetEntity="Periodo", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="periodo_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $miembros;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->prioridades = new \Doctrine\Common\Collections\ArrayCollection();
        $this->requerimientos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fecha = new \DateTime();
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
     * @return Proyecto
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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Proyecto
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Add prioridades
     *
     * @param \Aleste\TrackerBundle\Entity\Prioridad $prioridades
     * @return Proyecto
     */
    public function addPrioridade(\Aleste\TrackerBundle\Entity\Prioridad $prioridades)
    {
        $this->prioridades[] = $prioridades;

        return $this;
    }

    /**
     * Remove prioridades
     *
     * @param \Aleste\TrackerBundle\Entity\Prioridad $prioridades
     */
    public function removePrioridade(\Aleste\TrackerBundle\Entity\Prioridad $prioridades)
    {
        $this->prioridades->removeElement($prioridades);
    }

    /**
     * Get prioridades
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPrioridades()
    {
        return $this->prioridades;
    }

    /**
     * Add requerimientos
     *
     * @param \Aleste\TrackerBundle\Entity\Requerimiento $requerimientos
     * @return Proyecto
     */
    public function addRequerimiento(\Aleste\TrackerBundle\Entity\Requerimiento $requerimientos)
    {
        $this->requerimientos[] = $requerimientos;

        return $this;
    }

    /**
     * Remove requerimientos
     *
     * @param \Aleste\TrackerBundle\Entity\Requerimiento $requerimientos
     */
    public function removeRequerimiento(\Aleste\TrackerBundle\Entity\Requerimiento $requerimientos)
    {
        $this->requerimientos->removeElement($requerimientos);
    }

    /**
     * Get requerimientos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRequerimientos()
    {
        return $this->requerimientos;
    }

    /**
     * Set grafoTipoRequerimiento
     *
     * @param \Aleste\TrackerBundle\Entity\GrafoTipoRequerimiento $grafoTipoRequerimiento
     * @return Proyecto
     */
    public function setGrafoTipoRequerimiento(\Aleste\TrackerBundle\Entity\GrafoTipoRequerimiento $grafoTipoRequerimiento = null)
    {
        $this->grafoTipoRequerimiento = $grafoTipoRequerimiento;

        return $this;
    }

    /**
     * Get grafoTipoRequerimiento
     *
     * @return \Aleste\TrackerBundle\Entity\GrafoTipoRequerimiento 
     */
    public function getGrafoTipoRequerimiento()
    {
        return $this->grafoTipoRequerimiento;
    }

    /**
     * Set miembros
     *
     * @param \Aleste\TrackerBundle\Entity\Periodo $miembros
     * @return Proyecto
     */
    public function setMiembros(\Aleste\TrackerBundle\Entity\Periodo $miembros)
    {
        $this->miembros = $miembros;

        return $this;
    }

    /**
     * Get miembros
     *
     * @return \Aleste\TrackerBundle\Entity\Periodo 
     */
    public function getMiembros()
    {
        return $this->miembros;
    }

    /**
     * Set herramienta
     *
     * @param \Aleste\TrackerBundle\Entity\Herramienta $herramienta
     * @return Proyecto
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
