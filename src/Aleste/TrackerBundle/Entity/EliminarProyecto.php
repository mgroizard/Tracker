<?php

namespace Aleste\TrackerBundle\Entity;

/**
 * EliminarProyecto
 *
 */
class EliminarProyecto extends Simple
{
    /**
     * Constructor
     */
    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function execute(\Aleste\TrackerBundle\Entity\Proyecto $proyecto)
    {
        $this->em->remove($proyecto);
        $this->em->flush();
    }
        
}
