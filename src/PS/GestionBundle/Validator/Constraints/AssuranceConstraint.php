<?php

namespace PS\GestionBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class AssuranceConstraint extends Constraint
{

    /**
     * @var string
     */
    public $message = 'Cette assurance n\'est pas accepté dans ce centre de santé';

   

   
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

}
