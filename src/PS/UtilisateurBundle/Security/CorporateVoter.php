<?php


namespace PS\UtilisateurBundle\Security;


use PS\UtilisateurBundle\Entity\Utilisateur;
use PS\GestionBundle\Entity\Corporate;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CorporateVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW = 'ROLE_SAME_CORPORATE';
   

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::VIEW))) {
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
        //$utilisateur = $subject;


        return $this->isInSameCorporate($user, $subject);
    }


    private function isInSameCorporate($user,$subject)
    {
        //$isCorporate = false;
        $currentCorporate = $user->getPersonne()->getCorporate();
        if ($subject instanceof Corporate) {
            $corporate = $subject;
            //$isCorporate = true;
        } else {
            $corporate = $subject->getCorporate();
        }

        return  $currentCorporate == $corporate || $corporate->getCorporateParent() == $currentCorporate;
    }

}