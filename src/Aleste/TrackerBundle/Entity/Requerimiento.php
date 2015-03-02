<?php

namespace Aleste\TrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Requerimiento
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Requerimiento
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
     * @var Prioridad
     *
     * @ORM\ManyToOne(targetEntity="Prioridad", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="prioridad_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $prioridad;
    
    /**
     * @var TipoRequerimiento
     *
     * @ORM\ManyToOne(targetEntity="TipoRequerimiento", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $tipo;
    
    /**
     * @ORM\ManyToMany(targetEntity="EstadoRequerimiento", inversedBy="requerimientos")
     * @ORM\JoinTable(name="requerimiento_estado",
     *      joinColumns={@ORM\JoinColumn(name="requerimiento_id", referencedColumnName="id", onDelete="RESTRICT")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="estado_id", referencedColumnName="id", onDelete="RESTRICT")}
     *      )
     */
    private $estados;
    
     /**
     * @var Proyecto
     *
     * @ORM\ManyToOne(targetEntity="Proyecto", cascade={"persist"}, inversedBy="requerimientos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="proyecto_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $proyecto;
    
    

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
     * @return Requerimiento
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
     * Constructor
     */
    public function __construct()
    {
        $this->estados = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set prioridad
     *
     * @param \Aleste\TrackerBundle\Entity\Prioridad $prioridad
     * @return Requerimiento
     */
    public function setPrioridad(\Aleste\TrackerBundle\Entity\Prioridad $prioridad)
    {
        $this->prioridad = $prioridad;

        return $this;
    }

    /**
     * Get prioridad
     *
     * @return \Aleste\TrackerBundle\Entity\Prioridad 
     */
    public function getPrioridad()
    {
        return $this->prioridad;
    }

    /**
     * Set tipo
     *
     * @param \Aleste\TrackerBundle\Entity\TipoRequerimiento $tipo
     * @return Requerimiento
     */
    public function setTipo(\Aleste\TrackerBundle\Entity\TipoRequerimiento $tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return \Aleste\TrackerBundle\Entity\TipoRequerimiento 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Add estados
     *
     * @param \Aleste\TrackerBundle\Entity\EstadoRequerimiento $estados
     * @return Requerimiento
     */
    public function addEstado(\Aleste\TrackerBundle\Entity\EstadoRequerimiento $estados)
    {
        $this->estados[] = $estados;

        return $this;
    }

    /**
     * Remove estados
     *
     * @param \Aleste\TrackerBundle\Entity\EstadoRequerimiento $estados
     */
    public function removeEstado(\Aleste\TrackerBundle\Entity\EstadoRequerimiento $estados)
    {
        $this->estados->removeElement($estados);
    }

    /**
     * Get estados
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstados()
    {
        return $this->estados;
    }

    /**
     * Set proyecto
     *
     * @param \Aleste\TrackerBundle\Entity\Proyecto $proyecto
     * @return Requerimiento
     */
    public function setProyecto(\Aleste\TrackerBundle\Entity\Proyecto $proyecto)
    {
        $this->proyecto = $proyecto;

        return $this;
    }

    /**
     * Get proyecto
     *
     * @return \Aleste\TrackerBundle\Entity\Proyecto 
     */
    public function getProyecto()
    {
        return $this->proyecto;
    }
}
