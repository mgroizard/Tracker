<?php

namespace Aleste\TrackerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Simple
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Simple extends Actividad
{
    public function excecute()
    {
        return "coto puto";
    }
    
}
