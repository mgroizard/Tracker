<?php

namespace Aleste\TrackerBundle\Entity;

/**
 * AgregarProyecto
 *
 */
class AgregarProyecto extends Simple
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
        $herramienta = $this->em->getReference('TrackerBundle:Herramienta', 1);
        $proyecto->setHerramienta($herramienta);
        $this->em->persist($proyecto);
        $this->em->flush();
    }
        
}
