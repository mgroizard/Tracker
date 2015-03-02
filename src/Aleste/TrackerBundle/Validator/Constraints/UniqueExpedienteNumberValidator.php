<?php

namespace Aleste\TrackerBundle\Validator\Constraints;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Doctrine\ORM\EntityManager;

class UniqueExpedienteNumberValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function validate($expediente, Constraint $constraint)
    {        
        if($expediente->isExpediente()){
            $expedienteExistente = $this->em->getRepository('TrackerBundle:Documento')
                        ->findOneBy(
                            array(
                                'expCaracteristica' => $expediente->getExpCaracteristica(),
                                'expNumero' => $expediente->getExpNumero(),
                                'expAnio' => $expediente->getExpAnio(),
                                'expAlcance' => $expediente->getExpAlcance(),
                                'expCuerpo' => $expediente->getExpCuerpo(),
                                )
                        );

            if ($expedienteExistente && ($expediente->getId() != $expedienteExistente->getId() )) {
                    $this->context->addViolation('Ya existe un expediente con el número: '. $expediente->getNumeroExpediente());
            }
        }

        
         

    }
}
