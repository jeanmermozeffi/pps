<?php

namespace PS\UtilisateurBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Phone extends Constraint
{
    /**
     * @var string
     */
    public $message = 'Votre numéro de téléphone n\'est pas correct. Format attendu XXXXXXXX ou +225XXXXXXXX';

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }
   
}
