<?php

namespace PS\UtilisateurBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class AlphanumericValidator extends ConstraintValidator
{

     /**
     * @param $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value === '') {
            $this->context->buildViolation($constraint->emptyMessage)
                ->addViolation();
        } else {
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $value, $matches)) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ string }}', $value)
                    ->addViolation();
            }
        }
        
        
    }
}
