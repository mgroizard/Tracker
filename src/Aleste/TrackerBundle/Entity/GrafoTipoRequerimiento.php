<?php

namespace Aleste\TrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GrafoTipoRequerimiento
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class GrafoTipoRequerimiento
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
     * @ORM\ManyToMany(targetEntity="EstadoTipoRequerimiento", inversedBy="grafos")
     * @ORM\JoinTable(name="grafo_estadotiporequerimiento",
     *      joinColumns={@ORM\JoinColumn(name="grafo_id", referencedColumnName="id", onDelete="RESTRICT")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="estado_id", referencedColumnName="id", onDelete="RESTRICT")}
     *      )
     */
    private $estados;
    
    /**
     * @ORM\ManyToMany(targetEntity="TipoRequerimiento", inversedBy="grafos")
     * @ORM\JoinTable(name="grafo_tiporequerimiento",
     *      joinColumns={@ORM\JoinColumn(name="grafo_id", referencedColumnName="id", onDelete="RESTRICT")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tipo_id", referencedColumnName="id", onDelete="RESTRICT")}
     *      )
     */
    private $tipos;
    
    /**
     * @ORM\OneToOne(targetEntity="Proyecto", inversedBy="grafoTipoRequerimiento")
     **/
    private $proyecto;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->estados = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add estados
     *
     * @param \Aleste\TrackerBundle\Entity\EstadoTipoRequerimiento $estados
     * @return GrafoTipoRequerimiento
     */
    public function addEstado(\Aleste\TrackerBundle\Entity\EstadoTipoRequerimiento $estados)
    {
        $this->estados[] = $estados;

        return $this;
    }

    /**
     * Remove estados
     *
     * @param \Aleste\TrackerBundle\Entity\EstadoTipoRequerimiento $estados
     */
    public function removeEstado(\Aleste\TrackerBundle\Entity\EstadoTipoRequerimiento $estados)
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
     * Add tipos
     *
     * @param \Aleste\TrackerBundle\Entity\TipoRequerimiento $tipos
     * @return GrafoTipoRequerimiento
     */
    public function addTipo(\Aleste\TrackerBundle\Entity\TipoRequerimiento $tipos)
    {
        $this->tipos[] = $tipos;

        return $this;
    }

    /**
     * Remove tipos
     *
     * @param \Aleste\TrackerBundle\Entity\TipoRequerimiento $tipos
     */
    public function removeTipo(\Aleste\TrackerBundle\Entity\TipoRequerimiento $tipos)
    {
        $this->tipos->removeElement($tipos);
    }

    /**
     * Get tipos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTipos()
    {
        return $this->tipos;
    }

    /**
     * Set proyecto
     *
     * @param \Aleste\TrackerBundle\Entity\Proyecto $proyecto
     * @return GrafoTipoRequerimiento
     */
    public function setProyecto(\Aleste\TrackerBundle\Entity\Proyecto $proyecto = null)
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
