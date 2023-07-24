<?php


namespace PS\UtilisateurBundle\Security;

use PS\GestionBundle\Entity\Patient;
use PS\UtilisateurBundle\Entity\Utilisateur;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class PatientVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW = 'ROLE_EDIT_PATIENT';
    const PREMIUM = 'ROLE_IS_PREMIUM';

    



    protected function supports($attribute, $subject)
    {

        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::VIEW))) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof Patient) {
            return false;
        }



        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
       
        $user = $token->getUser();
       
        if (!$user instanceof Utilisateur) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Post object, thanks to supports
        /** @var Post $post */
        $patient = $subject;


        return $this->isCurrent($user, $patient);
    }


    private function isCurrent(Utilisateur $user, Patient $patient = null)
    {
       
        
        $_patient = $user->getPersonne()->getPatient();
        return !$patient 
            || $user->hasRole('ROLE_ADMIN_SUP')
            || ($user->hasRole('ROLE_CUSTOMER') && 
                ($patient->getPersonne() == $user->getPersonne()) || ($_patient && $_patient->isParentOf($patient))
            )
            || ($user->getHopital() && ($user->getHopital() == $patient->getPersonne()->getHopital()))
            || ($user->hasRole('ROLE_ADMIN_CORPORATE') && ($user->getPersonne()->getCorporate() == $patient->getPersonne()->getCorporate()));
    }

}