<?php

namespace Aleste\TrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Periodo
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Periodo
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
     * @var string $fechaInicio
     *
     * @ORM\Column(name="fecha_inicio", type="date")
     */
    private $fechaIncio;
    
    /**
     * @var string $fechaFin
     *
     * @ORM\Column(name="fecha_fin", type="date")
     */
    private $fechaFin;
    
    /**
     * @var Usuario
     *
     * @ORM\ManyToOne(targetEntity="Aleste\SeguridadBundle\Entity\Usuario", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $usuario;
    
    /**
     * @var Rol
     *
     * @ORM\ManyToOne(targetEntity="Aleste\SeguridadBundle\Entity\Rol", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rol_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $rol;

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
     * Set fechaIncio
     *
     * @param \DateTime $fechaIncio
     * @return Periodo
     */
    public function setFechaIncio($fechaIncio)
    {
        $this->fechaIncio = $fechaIncio;

        return $this;
    }

    /**
     * Get fechaIncio
     *
     * @return \DateTime 
     */
    public function getFechaIncio()
    {
        return $this->fechaIncio;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     * @return Periodo
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime 
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Set usuario
     *
     * @param \Aleste\TrackerBundle\Entity\Usuario $usuario
     * @return Periodo
     */
    public function setUsuario(\Aleste\TrackerBundle\Entity\Usuario $usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \Aleste\TrackerBundle\Entity\Usuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set rol
     *
     * @param \Aleste\TrackerBundle\Entity\Rol $rol
     * @return Periodo
     */
    public function setRol(\Aleste\TrackerBundle\Entity\Rol $rol)
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Get rol
     *
     * @return \Aleste\TrackerBundle\Entity\Rol 
     */
    public function getRol()
    {
        return $this->rol;
    }
}
