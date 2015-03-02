<?php

namespace Aleste\TrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoRequerimiento
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class TipoRequerimiento
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
     * @ORM\ManyToMany(targetEntity="GrafoTipoRequerimiento", mappedBy="tipos", cascade={"all"})
     */
    private $grafos;
    
    /**
     * @var Herramienta
     *
     * @ORM\ManyToOne(targetEntity="Herramienta",inversedBy="tiposRequerimientos", cascade={"persist"})
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
     * @return TipoRequerimiento
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
        $this->grafos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add grafos
     *
     * @param \Aleste\TrackerBundle\Entity\Grafo $grafos
     * @return TipoRequerimiento
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
     * @return TipoRequerimiento
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
