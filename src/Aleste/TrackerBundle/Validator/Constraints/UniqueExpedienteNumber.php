<?php

namespace Aleste\TrackerBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/** @Annotation */
class UniqueExpedienteNumber extends Constraint
{
    public function validatedBy()
    {
        return 'unique_expediente_number';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
