<?php

namespace PS\GestionBundle\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use PS\GestionBundle\Entity\Patient;
use PS\ParametreBundle\Entity\Pass;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class PassValidator extends ConstraintValidator
{

    /**
     * @var mixed
     */
    private $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Patient $patient
     * @param Constraint $constraint
     */
    public function validate($data, Constraint $constraint)
    {
        $identifiant = $data->getIdentifiant();
        $pin         = $data->getPin();
        $contact     = $data->getContact();

        $patient = $this->em->getRepository(Patient::class)->findOneBy(compact('identifiant', 'pin'));
        $pass = $this->em->getRepository(Pass::class)->findOneBy(compact('identifiant', 'pin'));


       


        if (!$pass) {
            $message = 'ID/PIN inexistant';
        } else {
            if ($patient && $patient->getContact() != $contact) {
                $message = 'ID/PIN déjà associé à un patient avec le numéro '.$patient->getContact();
            }
        }


    
        if (isset($message)) {
            $this->context->buildViolation($message)
                ->atPath('identifiant')
                ->addViolation();
        }
  
    }
}
