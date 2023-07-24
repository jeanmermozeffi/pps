<?php

namespace PS\UtilisateurBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PhoneValidator extends ConstraintValidator
{
    /**
     * @param $value
     * @param Constraint $constraint
     * @return null
     */
    public function validate($value, Constraint $constraint)
    {
        if (!preg_match('#^((0{2}|\+)(225))?(\d{8})$#', $value, $matches)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
