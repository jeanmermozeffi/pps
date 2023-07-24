<?php

namespace PS\GestionBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class DateTimeAvailabilityConstraint extends Constraint
{
    /*public function __construct()
    {
        //parent::__construct(['groups' => ['new', 'edit']]);
    }*/

    /**
     * @var string
     */
    public $messageNotAvailable = 'La date et l\'heure sélectionnée ont déjà étés réservées';

    public $messageDateExpired = 'Vous ne pouvez pas prendre RDV a une date antérieure';

   
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

}
