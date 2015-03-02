<?php

namespace Aleste\TrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Prioridad
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Prioridad
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
     * @var integer
     *
     * @ORM\Column(name="valor", type="integer")
     * @Assert\NotBlank()     
     */
    private $valor;
    
    /**
     * @ORM\ManyToMany(targetEntity="Proyecto", mappedBy="prioridades", cascade={"all"})
     */ 
    private $proyectos;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->proyectos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Prioridad
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
     * Set valor
     *
     * @param integer $valor
     * @return Prioridad
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return integer 
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Add proyectos
     *
     * @param \Aleste\TrackerBundle\Entity\Proyecto $proyectos
     * @return Prioridad
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
}
