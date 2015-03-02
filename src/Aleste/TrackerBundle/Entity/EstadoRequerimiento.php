<?php

namespace Aleste\TrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EstadoRequerimiento
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class EstadoRequerimiento
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
     * @var EstadoTipoRequerimiento
     *
     * @ORM\ManyToOne(targetEntity="EstadoTipoRequerimiento", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $tipo;
       
    /**
     * @ORM\ManyToMany(targetEntity="Requerimiento", mappedBy="estados", cascade={"all"})
     */
    private $requerimientos;
    
    /**
     * @var Periodo
     *
     * @ORM\ManyToOne(targetEntity="Periodo", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="periodo_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $miembros;
    
    /**
     * @var \Periodo
     *
     * @ORM\ManyToOne(targetEntity="Periodo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="periodo_id", referencedColumnName="id", nullable=false)
     * })     
     */
    private $responsable;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->requerimientos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return EstadoRequerimiento
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
     * Set tipo
     *
     * @param \Aleste\TrackerBundle\Entity\EstadoTipoRequerimiento $tipo
     * @return EstadoRequerimiento
     */
    public function setTipo(\Aleste\TrackerBundle\Entity\EstadoTipoRequerimiento $tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return \Aleste\TrackerBundle\Entity\EstadoTipoRequerimiento 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Add requerimientos
     *
     * @param \Aleste\TrackerBundle\Entity\Requerimiento $requerimientos
     * @return EstadoRequerimiento
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
     * Set miembros
     *
     * @param \Aleste\TrackerBundle\Entity\Periodo $miembros
     * @return EstadoRequerimiento
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
     * Set responsable
     *
     * @param \Aleste\TrackerBundle\Entity\Periodo $responsable
     * @return EstadoRequerimiento
     */
    public function setResponsable(\Aleste\TrackerBundle\Entity\Periodo $responsable)
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get responsable
     *
     * @return \Aleste\TrackerBundle\Entity\Periodo 
     */
    public function getResponsable()
    {
        return $this->responsable;
    }
}
