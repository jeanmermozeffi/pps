<?php

namespace PS\GestionBundle\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use PS\GestionBundle\Entity\RendezVous;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;



class DateTimeAvailabilityConstraintValidator extends ConstraintValidator
{

    /**
     * @var mixed
     */
    private $em;
   
    /**
     * 
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * 
     */
    public function validate($rendezVous, Constraint $constraint)
    {

        $datetime = $rendezVous->getDateRendezVous();
        $medecin = $rendezVous->getMedecin();

        if ($datetime < new \DateTime()) {
            $message = $constraint->messageDateExpired;
        } else if (!$this->em->getRepository(RendezVous::class)->checkDateAvailability($datetime, $medecin, $rendezVous)) {
           $message = $constraint->messageNotAvailable;
        }


        //dump($medecin, $datetime);exit;

    

        

        if (isset($message)) {
            $this->context->buildViolation($message)
                ->atPath('dateRendezVous')
                ->addViolation();
        }

    }
}
