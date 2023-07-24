<?php


namespace PS\UtilisateurBundle\Security;


use PS\UtilisateurBundle\Entity\Utilisateur;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AssocieVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW = 'view';
   

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::VIEW))) {
            return false;
        }



        // only vote on Consultation objects inside this voter
        if (!$subject instanceof Utilisateur) {
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
        $utilisateur = $subject;


        return $this->isOwner($utilisateur, $user);
    }


    private function isOwner(Utilisateur $utilisateur, Utilisateur $user)
    {
        return $utilisateur->getParent() == $user;
    }

}