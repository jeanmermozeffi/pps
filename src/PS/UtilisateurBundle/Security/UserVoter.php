<?php


namespace PS\UtilisateurBundle\Security;

use PS\GestionBundle\Entity\Patient;
use PS\UtilisateurBundle\Entity\Utilisateur;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UserVoter extends Voter
{
    // these strings are just invented: you can use anything
    const ROLE_VIEW_USER = 'ROLE_VIEW_USER';
    const ROLE_DELETE_USER = 'ROLE_DELETE_USER';
    const ROLE_MANAGE_HOPITAL = 'ROLE_MANAGE_HOPITAL';
    const ROLE_MANAGE_CORPORATE = 'ROLE_MANAGE_CORPORATE';
    const ROLE_CREATE_PATIENT = 'ROLE_CREATE_PATIENT';
    const ROLE_IS_MEDICAL = 'ROLE_IS_MEDICAL';
    const ROLE_MANAGE_RDV = 'ROLE_MANAGE_RDV';
    const ROLE_LOCAL_ASSURANCE = 'ROLE_LOCAL_ASSURANCE';
    const ROLE_MEDECIN_CONSEIL = 'ROLE_MEDECIN_CONSEIL';
    const ROLE_IS_PREMIUM = 'ROLE_IS_PREMIUM';
    const ROLE_VIEW_RDV = 'ROLE_VIEW_RDV';


    protected function supports($attribute, $subject)
    {

        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(
            self::ROLE_VIEW_USER
            , self::ROLE_DELETE_USER
            , self::ROLE_MANAGE_HOPITAL
            , self::ROLE_CREATE_PATIENT
            , self::ROLE_IS_MEDICAL
            , self::ROLE_MANAGE_RDV
            , self::ROLE_LOCAL_ASSURANCE
            , self::ROLE_IS_PREMIUM
            , self::ROLE_MEDECIN_CONSEIL
            , self::ROLE_VIEW_RDV
        ))) {
            return false;
        }

        


        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        //dump($token);exit;
        $currentUser = $token->getUser();
        
       
        if (!$currentUser instanceof Utilisateur) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Post object, thanks to supports
        /** @var Post $post */
        $viewedUser = $subject ?: $currentUser;

       
        switch ($attribute) {
            case self::ROLE_VIEW_USER:
                return $this->canView($viewedUser, $currentUser);
                break;
            case self::ROLE_DELETE_USER:
                return $this->canDelete($viewedUser, $currentUser);
                break;
            case self::ROLE_MANAGE_HOPITAL:
                return $this->canManageHospital($currentUser);
                break;
            case self::ROLE_MANAGE_CORPORATE:
                return $this->canManageCorporate($currentUser);
                break;
            case self::ROLE_CREATE_PATIENT:
                return $this->canCreatePatient($currentUser);
                break;
            case self::ROLE_IS_MEDICAL:
                return $this->hasMedicalRole($currentUser);
                break;
            case self::ROLE_LOCAL_ASSURANCE:
                return $this->hasLocalAssurance($currentUser);
                break;
            case self::ROLE_MEDECIN_CONSEIL:
                return $this->hasMedecinAssurance($currentUser);
                break;
            case self::ROLE_IS_PREMIUM:
                return $this->isPremium($currentUser);
                break;
            case self::ROLE_VIEW_RDV:
                return $this->canViewRdv($currentUser);
                break;
        }


        //return $this->canView($viewedUser, $currentUser);
    }


    public function canCreatePatient($currentUser)
    {
        return $currentUser->hasRoles(['ROLE_RECEPTION', 'ROLE_ASSISTANT', 'ROLE_INFIRMIER']);
    }



    public function canManageHospital($currentUser)
    {
         return $currentUser->hasRoles(['ROLE_ADMIN', 'ROLE_SUPER_ADMIN', 'ROLE_ADMIN_CORPORATE']);
    }


    public function canManageCorporate($currentUser)
    {
       return $currentUser->hasRoles(['ROLE_ADMIN_LOCAL', 'ROLE_ADMIN_CORPORATE']);
    }


    public function hasLocalAssurance($currentUser)
    {
       
        return $currentUser->hasRole('ROLE_ADMIN_LOCAL') && $currentUser->getAssurance();
    }

    public function hasMedecinAssurance($currentUser)
    {  
        return $currentUser->hasRole('ROLE_MEDECIN') && $currentUser->getAssurance();
    }


    private function canView(Utilisateur $viewedUser, Utilisateur $currentUser)
    {
        return $currentUser->hasRoles(['ROLE_ADMIN', 'ROLE_SUPER_ADMIN'])
                || ($viewedUser->getId() == $currentUser->getId()) 
                || ($currentUser->hasRoles(['ROLE_RECEPTION', 'ROLE_INFIRMIER', 'ROLE_ADMIN_LOCAL']) && $viewedUser->getHopital() == $currentUser->getHopital())
                || ($currentUser->hasRole('ROLE_ADMIN_LOCAL') && $viewedUser->getAssurance() == $currentUser->getAssurance())
                || ($currentUser->hasRoles(['ROLE_ADMIN_CORPORATE']) && $viewedUser->getPersonne()->getCorporate() == $currentUser->getPersonne()->getCorporate());
    }


    private function canDelete(Utilisateur $viewedUser, Utilisateur $currentUser)
    {
        return $currentUser->hasRoles(['ROLE_ADMIN', 'ROLE_SUPER_ADMIN', 'ROLE_ADMIN_CORPORATE']) || ($viewedUser->getId() == $currentUser->getId());
    }


    private function hasMedicalRole(Utilisateur $currentUser)
    {
        return $currentUser->hasRoles(['ROLE_MEDECIN', 'ROLE_INFIRMIER', 'ROLE_PHARMACIE', 'ROLE_RECEPTION']);
    }

    private function isPremium(Utilisateur $currentUser)
    {
       
        if ($patient = $currentUser->getPatient()) {
            return $patient->getIdentifiant() && $patient->getPin();
        }
        return false;
    }


    private function canViewRdv(Utilisateur $currentUser)
    {
        if ($currentUser->hasRole('ROLE_CUSTOMER')) {
            return $this->isPremium($currentUser);
        }

        return $currentUser->hasRole('ROLE_MEDECIN');
    }

}