<?php

namespace PS\UtilisateurBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Alphanumeric extends Constraint
{
    /**
     * @var string
     */
    public $message = 'username.alphanumeric';

    public $emptyMessage = "Cette valeur ne peut être vide";

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }
   
}
