<?php

namespace PS\GestionBundle\Validator\Constraints;

use Doctrine\ORM\EntityManager;
use PS\GestionBundle\Entity\Patient;
use PS\ParametreBundle\Entity\Pass;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * @Annotation
 */
class PassConstraintValidator extends ConstraintValidator
{

    /**
     * @var mixed
     */
    private $em;

    private $token;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, TokenStorage $token)
    {
        $this->em = $em;
        $this->token = $token;
    }

    /**
     * @param Patient $patient
     * @param Constraint $constraint
     */
    public function validate($patient, Constraint $constraint)
    {
        $identifiant = $patient->getIdentifiant();
        $pin         = $patient->getPin();
        $corporate = $patient->getPersonne()->getCorporate();
        $repPass = $this->em->getRepository(Pass::class);
        if ($this->token->getToken()->getUser() instanceof UserInterface) {
            $currentCorporate = $this->token->getToken()->getUser()->getPersonne()->getCorporate();
        }

        

        
        //$exists = $repPass->isExists($identifiant, $pin, $patient->getId(), $corporate);
        //$isInSameCorporate = $corporate == $r


        if ($identifiant && $pin) {
            $passCorporate = $repPass->corporateByIdPin($identifiant, $pin);

            

            //dump($passCorporate, $corporate->getId());exit;
            /*if ($currentCorporate && !$repPass->exists($identifiant, $pin, $patient->getId())) {
                 $message = $constraint->messageNotSameCorporate;
            } elseif ($corporate && $passCorporate != $corporate->getId()) {
                $message = $constraint->messagePassNotSameCorporate;
            } else*/if (!$repPass->exists($identifiant, $pin, $patient->getId())) {
                 $message = 'Couple PIN + PASS inexistant ou déjà attribué'/*$constraint->messagePassNotFound*/;
            }

            if (isset($message)) {
                $this->context->buildViolation($message)
                    ->addViolation();
            }
            
            /*if (!$exists) {
               
            }*/
        }
    }
}
