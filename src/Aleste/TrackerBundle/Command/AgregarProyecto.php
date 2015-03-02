<?php

namespace Aleste\TrackerBundle\Command;

use Aleste\TrackerBundle\Entity\Simple;

/**
 * AgregarProyecto
 *
 */
class AgregarProyecto extends Simple
{
    private $proyecto;
    
    /**
     * Constructor
     */
    public function __construct(\Doctrine\ORM\EntityManager $em,$objeto = NULL)
    {
        $this->em = $em;
        $this->proyecto = $objeto;
    }
    
    public function execute()
    {
        //object es un Proyecto
        $herramienta = $this->em->getReference('TrackerBundle:Herramienta', 1);
        $this->proyecto->setHerramienta($herramienta);
        $this->em->persist($this->proyecto);
    }
        
}
