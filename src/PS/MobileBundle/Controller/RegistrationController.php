<?php

namespace PS\MobileBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use PS\GestionBundle\Entity\Medecin;
use PS\MobileBundle\DTO\MedecinDTO;
use PS\MobileBundle\Form\MedecinType;
use PS\UtilisateurBundle\Entity\Personne;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends Controller
{

    /**
     * @Rest\View()
     * @Rest\Post("/register")
     */
    public function postRegisterAction(Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user        = $userManager->createUser();
        $user->setEnabled(true);

        $form = $this->get('fos_user.registration.form.factory')->createForm([
            'csrf_protection' => false
        ]);
        $form->setData($user);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $event = new FormEvent($form, $request);
            $this->get('debug.event_dispatcher')->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

            $userManager->updateUser($user);

            $jwtManager = $this->get('lexik_jwt_authentication.jwt_manager');

            return ['token' => $jwtManager->create($user)];
        }

        return $form;

    }


     /**
     * @Rest\View()
     * @Rest\Post("/medecin/register")
     */
    public function postMedecinRegisterAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userManager = $this->get('fos_user.user_manager');
        $user        = $userManager->createUser();
        $user->setEnabled(true);

        $medecinDTO = new MedecinDTO();

        $form = $this->createForm(MedecinType::class, $medecinDTO, [
            'csrf_protection' => false
        ]);
        
       

        $form->submit($request->request->all());

        if ($form->isValid()) {
            
            $user->setUsername($medecinDTO->getUsername());
            $user->setPlainPassword($medecinDTO->getPassword());
            $user->setEmail($medecinDTO->getEmail());
            $user->setRoles(['ROLE_MEDECIN']);

            //$hopital = 

            $personne = new Personne();
            $personne->setNom($medecinDTO->getNom());
            $personne->setPrenom($medecinDTO->getPrenom());
            $personne->setContact($medecinDTO->getContact());

            $user->setPersonne($personne);

            $medecin = new Medecin();
            $medecin->setHopital($medecinDTO->getHopital());
            $medecin->setPersonne($personne);
            $medecin->setMatricule($medecinDTO->getMatricule());
            foreach ($medecinDTO->getSpecialites() as $specialite) {
                $medecin->addSpecialite($specialite);
            }
            $em->persist($medecin);
            $userManager->updateUser($user);

            $jwtManager = $this->get('lexik_jwt_authentication.jwt_manager');

            return ['token' => $jwtManager->create($user)];
        }

        return $form;

    }
}
