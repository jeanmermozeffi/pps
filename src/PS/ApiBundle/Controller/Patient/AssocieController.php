<?php

namespace PS\ApiBundle\Controller\Patient;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use PS\ApiBundle\Form\CompteAssocieType;
use PS\GestionBundle\Entity\Patient;
use PS\UtilisateurBundle\Entity\Personne;
use PS\UtilisateurBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Request\ParamFetcher;



class AssocieController extends Controller
{

    /**
     * Retourne le patient actuel
     * @param $em
     * @param Request $request
     * @return mixed
     */
    private function getPatient($em, Request $request)
    {
        //$em = $this->getDoctrine()->getEntityManager();
        $patient = $em->getRepository(Patient::class)->find($request->get('id'));

        $this->denyAccessUnlessGranted('ROLE_EDIT_PATIENT', $patient);

        return $patient;
    }

    /**
     * @Rest\View(serializerGroups={"associe", "photo"})
     * @Rest\QueryParam(name="page", requirements="\d+", default="", description="Numéro de la page")
     * @Rest\Get("patients/{id}/associes")
     */
    public function getAllAssocieAction(Request $request, ParamFetcher $paramFetcher)
    {
        $em      = $this->getDoctrine()->getEntityManager();
        $patient = $this->getPatient($em, $request);
        $user     = $patient->getPersonne()->getUtilisateur();

        $page = intval($paramFetcher->get('page'));

        $result   = 15;
        $maxPages = ceil($user->getAssocies()->count() / $result);

        if ($page <= 0 || $page > $maxPages) {
            $page = 1;
        }

        $limit  = $result;
        $offset = ($page - 1) * $result;

       
        $associes = $em->getRepository(Utilisateur::class)->associes($user, true, true, $limit, $offset);

        return ['maxPages' => $maxPages, 'results' => $associes];

    }

    /**
     * @Rest\View(serializerGroups={"associe"})
     * @Rest\Post("patients/{id}/associes")
     */
    public function postAssocieAction(Request $request)
    {
        $utilisateur = new Utilisateur();
        $form        = $this->createForm(CompteAssocieType::class, $utilisateur);
        $user        = $this->getUser();

        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getEntityManager();

            $personne = new Personne();

            $personne->setContact($user->getPersonne()->getContact());

            $utilisateur->setPersonne($personne);
            $utilisateur->setParent($user);
            $utilisateur->setEnabled(true);
            $utilisateur->setEmail($user->getEmail());
            $utilisateur->setRoles(['ROLE_CUSTOMER']);

            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($utilisateur, false);

            $patient = new Patient();
            $patient->setPersonne($utilisateur->getPersonne());

            $em->persist($patient);
            $em->flush();

            return $utilisateur;
        }

        return $form;
    }

    /**
     * @Rest\View(serializerGroups={"associe"})
     * @Rest\Patch("patients/{id}/associes/{associe}")
     */
    public function patchAssocieAction(Request $request)
    {
        $em          = $this->getDoctrine()->getEntityManager();
        $userManager = $this->get('fos_user.user_manager');
        $utilisateur = $userManager->findUserBy(['id' => $request->get('associe')]);

        $user = $this->getUser();
        if ($utilisateur->getParent() != $user) {
            throw $this->createAccessDeniedException('Vous n\'avez pas les permissions pour accéder à cette ressource');
        }

        $form = $this->createForm(CompteAssocieType::class, $utilisateur);
        $form->submit($request->request->all(), $request->getMethod() != 'PATCH');

        if ($form->isValid()) {
            $utilisateur->getPersonne()->setContact($user->getPersonne()->getContact());
            $em->merge($utilisateur);
            $em->flush();

            return $utilisateur;
        }

        return $form;

    }
}
