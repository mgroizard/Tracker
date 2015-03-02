<?php

namespace Aleste\TrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EstadoTipoRequerimiento
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class EstadoTipoRequerimiento
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
     * @ORM\ManyToMany(targetEntity="EstadoTipoRequerimiento")
     * @ORM\JoinTable(name="estadotiporequerimiento_siguiente",
     *      joinColumns={@ORM\JoinColumn(name="estadotiporequerimiento_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="siguiente_id", referencedColumnName="id")}
     * )
     */
    private $siguientes;
    
    /**
     * @ORM\ManyToMany(targetEntity="GrafoTipoRequerimiento", mappedBy="estados", cascade={"all"})
     */
    private $grafos;
    
    /**
     * @var Herramienta
     *
     * @ORM\ManyToOne(targetEntity="Herramienta", inversedBy="tiposEstados",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="herramienta_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $herramienta;
           
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->siguientes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return EstadoTipoRequerimiento
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
     * Add siguientes
     *
     * @param \Aleste\TrackerBundle\Entity\EstadoTipoRequerimiento $siguientes
     * @return EstadoTipoRequerimiento
     */
    public function addSiguiente(\Aleste\TrackerBundle\Entity\EstadoTipoRequerimiento $siguientes)
    {
        $this->siguientes[] = $siguientes;

        return $this;
    }

    /**
     * Remove siguientes
     *
     * @param \Aleste\TrackerBundle\Entity\EstadoTipoRequerimiento $siguientes
     */
    public function removeSiguiente(\Aleste\TrackerBundle\Entity\EstadoTipoRequerimiento $siguientes)
    {
        $this->siguientes->removeElement($siguientes);
    }

    /**
     * Get siguientes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSiguientes()
    {
        return $this->siguientes;
    }

    /**
     * Add grafos
     *
     * @param \Aleste\TrackerBundle\Entity\Grafo $grafos
     * @return EstadoTipoRequerimiento
     */
    public function addGrafo(\Aleste\TrackerBundle\Entity\Grafo $grafos)
    {
        $this->grafos[] = $grafos;

        return $this;
    }

    /**
     * Remove grafos
     *
     * @param \Aleste\TrackerBundle\Entity\Grafo $grafos
     */
    public function removeGrafo(\Aleste\TrackerBundle\Entity\Grafo $grafos)
    {
        $this->grafos->removeElement($grafos);
    }

    /**
     * Get grafos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGrafos()
    {
        return $this->grafos;
    }

    /**
     * Set herramienta
     *
     * @param \Aleste\TrackerBundle\Entity\Herramienta $herramienta
     * @return EstadoTipoRequerimiento
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
