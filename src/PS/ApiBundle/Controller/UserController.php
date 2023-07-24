<?php

namespace PS\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use PS\ApiBundle\Form\CompteType;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Entity\Medecin;
use PS\UtilisateurBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Patch("/users/{id}")
     */
    public function patchUserAction(Request $request)
    {

        $em   = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository(Utilisateur::class)->find($request->get('id'));

        $form = $this->createForm(new CompteType(), $user);

        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $em->persist($user);
            $em->flush();
            $jwtManager = $this->container->get('lexik_jwt_authentication.jwt_manager');

            return ['token' => $jwtManager->create($user), 'user' => $user];
        }

        return $form;
    }


     /**
     * @Rest\View()
     * @Rest\Get("/users/{id}")
     */
    public function getUserAction(Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user        = $userManager->findUserBy(['id' => $request->get('id')]);
        return $user;
    }

    /**
     * Retourne le patient actuel
     * @param $em
     * @param Request $request
     * @return mixed
     */
    private function getPatient($em, Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');
        $em          = $this->getDoctrine()->getEntityManager();
        $user        = $userManager->findUserBy(['id' => $request->get('id')]);
        return $em->getRepository(Patient::class)->findPatient($user->getPersonne()->getId());
    }

   
    /**
     * @Rest\View()
     * @Rest\Get("/users/{id}/patient")
     */
    public function getPatientAction(Request $request)
    {
        $em          = $this->getDoctrine()->getEntityManager();
        $patient = $this->getPatient($em, $request);
        if ($patient) {
            return $patient;
        }

        throw $this->createNotFoundException('Profil inexistant');
    
    }



    /**
     * @Rest\View()
     * @Rest\Patch("/users/{id}/patient")
     */
    public function patchPatientAction(Request $request)
    {
        
    }


     /**
     * @Rest\View()
     * @Rest\Get("/users/{id}/medecin")
     */
    public function getMedecinAction(Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');
        $em          = $this->getDoctrine()->getEntityManager();
        $user        = $userManager->findUserBy(['id' => $request->get('id')]);

        if ($user) {
            $patient = $em->getRepository(Patient::class)->findPersoByParam($user->getPersonne()->getId());
            return $patient;
        }

        throw $this->createNotFoundException('utilisateur introuvable');
    
    }

}
