<?php

namespace Aleste\TrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Persona
 *
 * @ORM\Table()
 * @ORM\Entity()
 * @UniqueEntity(fields = {"numeroDoc", "tipoDocumento"},
 *               message = "La combinación Tipo, Número de Documento ya existe")                            
 */
class Persona
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
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
     * @var datetime
     *
     * @ORM\Column(name="fechaNacimiento", type="date")
     * @Assert\NotBlank()     
     */
    private $fechaNacimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido", type="string", length=100)
     * @Assert\NotBlank()     
     */
    private $apellido;

    /**
     * @var \TipoDocumento
     *
     * @ORM\ManyToOne(targetEntity="TipoDocumento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_documento_id", referencedColumnName="id", nullable=false)
     * })     
     */
    private $tipoDocumento;

    /**
     * @var integer
     *
     * @ORM\Column(name="numeroDoc", type="integer")
     * @Assert\NotBlank()     
     */
    private $numeroDoc;

 
    public function __toString()
    {
        return $this->apellido .", ".$this->nombre;
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
     * @return Persona
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
     * Set apellido
     *
     * @param string $apellido
     * @return Persona
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string 
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set tipoDocumento
     *
     * @param \stdClass $tipoDocumento
     * @return Persona
     */
    public function setTipoDocumento(\Aleste\TrackerBundle\Entity\TipoDocumento $tipoDocumento)
    {
        $this->tipoDocumento = $tipoDocumento;

        return $this;
    }

    /**
     * Get tipoDocumento
     *
     * @return \stdClass 
     */
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    /**
     * Set nroDoc
     *
     * @param integer $nroDoc
     * @return Persona
     */
    public function setNroDoc($nroDoc)
    {
        $this->nroDoc = $nroDoc;

        return $this;
    }

    /**
     * Get nroDoc
     *
     * @return integer 
     */
    public function getNroDoc()
    {
        return $this->nroDoc;
    }


    /**
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     * @return Persona
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return \DateTime 
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * Set numeroDoc
     *
     * @param integer $numeroDoc
     * @return Persona
     */
    public function setNumeroDoc($numeroDoc)
    {
        $this->numeroDoc = $numeroDoc;

        return $this;
    }

    /**
     * Get numeroDoc
     *
     * @return integer 
     */
    public function getNumeroDoc()
    {
        return $this->numeroDoc;
    }
}
