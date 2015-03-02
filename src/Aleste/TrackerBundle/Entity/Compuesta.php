<?php

namespace Aleste\TrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Compuesta
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Compuesta extends Actividad
{
    public function excecute()
    {
    }
    
    /**
     * @ORM\OneToMany(targetEntity="Actividad", mappedBy="compuesta", cascade={"all"})  
     */
    private $actividades;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->actividades = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add actividades
     *
     * @param \Aleste\TrackerBundle\Entity\Actividad $actividades
     * @return Compuesta
     */
    public function addActividade(\Aleste\TrackerBundle\Entity\Actividad $actividades)
    {
        $this->actividades[] = $actividades;

        return $this;
    }

    /**
     * Remove actividades
     *
     * @param \Aleste\TrackerBundle\Entity\Actividad $actividades
     */
    public function removeActividade(\Aleste\TrackerBundle\Entity\Actividad $actividades)
    {
        $this->actividades->removeElement($actividades);
    }

    /**
     * Get actividades
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActividades()
    {
        return $this->actividades;
    }
}
