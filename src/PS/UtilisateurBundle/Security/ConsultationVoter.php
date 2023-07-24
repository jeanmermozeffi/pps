<?php


namespace PS\UtilisateurBundle\Security;

use PS\GestionBundle\Entity\Consultation;
use PS\UtilisateurBundle\Entity\Utilisateur;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ConsultationVoter extends Voter
{
    // these strings are just invented: you can use anything
    const ROLE_EDIT_CONSULTATION = 'ROLE_EDIT_CONSULTATION';
   

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::ROLE_EDIT_CONSULTATION))) {
            return false;
        }



        // only vote on Consultation objects inside this voter
        if (!$subject instanceof Consultation) {
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
        $consultation = $subject;


        return $this->isOwner($consultation, $user);
    }


    private function isOwner(Consultation $consultation, Utilisateur $user)
    {
        return $consultation->getMedecin()->getPersonne() == $user->getPersonne();
    }

}