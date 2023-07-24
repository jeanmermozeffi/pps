<?php

namespace PS\GestionBundle\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use PS\GestionBundle\Entity\RendezVous;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use PS\GestionBundle\Validator\Constraints as PSAssert;


class AssuranceConstraintValidator extends ConstraintValidator
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
    public function validate($consultation, Constraint $constraint)
    {
        $listeAssurances = $consultation->getHopital()->getAssurances();
        $currentAssurance = $consultation->getAssurance();

        if ($currentAssurance && !$listeAssurances->contains($currentAssurance)) {
            $this->context->buildViolation($constraint->message)
                ->atPath('assurance')
                ->addViolation();
        }
    }
}
