<?php

namespace PS\UtilisateurBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
use PS\GestionBundle\Entity\Abonnement;
use PS\GestionBundle\Entity\Patient;
use PS\ParametreBundle\Entity\Pass;
use PS\ParametreBundle\Entity\Pack;
use PS\UtilisateurBundle\Entity\Personne;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RegistrationController extends BaseController
{
    /**
     * @return mixed
     */
    public function registerAction()
    {
        $form                = $this->container->get('fos_user.registration.form');
        $formHandler         = $this->container->get('fos_user.registration.form.handler');
        $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');
        $em                  = $this->container->get('doctrine.orm.entity_manager');

        $process = $formHandler->process($confirmationEnabled, false, $data);
        $errors  = [];

        $repPatient = $em->getRepository(Patient::class);
        $repPass = $em->getRepository(Pass::class);
        $repPack = $em->getRepository(Pack::class);

        //die();
        if ($process) {
            $user = $form->getData();

            $formData = current($data);

            $contact = $formData['contact'];

            /**************************************************************/
            /*******CREATION DE PATIENT ET MISE A JOUR DU ROLE*************/
            $personne = new Personne();
            $personne->setContact($contact);
            $patient  = new Patient();
            $patient->setPersonne($personne);
            /*$pass     = new Pass();
            $pack     = $repPack->findOneByOrdre(0);

            $patient->setPersonne($personne);

            $passData = $repPass->generateIdPin();
            
            $pin      = $passData[1] ?? 0;
            $id       = $passData[0] ?? 0;

            if (!$pin || !$id) {
                for ($i = 0; $i < 5; $i++) {
                    $passData = $repPass->generateIdPin();
                    if (isset($passData[1], $passData[0])) {
                        $pin = $passData[1];
                        $id = $passData[0];
                        break;
                    }
                }
            }


            if (!$pin || !$id) {
                $passData = $repPass->generateIdPin(5);
                $pin      = $passData[1] ?? 0;
                $id       = $passData[0] ?? 0;

            }


            if ($pin && $id) {
                $pass->setPin($pin);
                $pass->setIdentifiant($id);
                $pass->setActif(true);
                $em->persist($pass);
                $patient->setIdentifiant($id);
                $patient->setPin($pin);

                $abonnement = new Abonnement();
                $abonnement->setPack($pack);
                $abonnement->setDateFin((new \DateTime())->modify('+' . $pack->getFullDuree()));
                $abonnement->setPass($pass);

                $patient->addAbonnement($abonnement);
            }*/
            

            $user->setPersonne($personne);

            $user->setRoles(['ROLE_CUSTOMER']);

            $userManager = $this->container->get('fos_user.user_manager');

            /*if (($parent = $userManager->findUserByEmail($user->getEmail())) && ($parent->getId() != $user->getId())) {
            $user->setParent($parent);
            }*/

            $userManager->updateUser($user);

            $patient->setPersonne($user->getPersonne());
            $em = $this->container->get('doctrine')->getManager();
            $em->persist($patient);
            $em->flush();

            /**************************************************************/

            $authUser = false;
            $params   = [];
            if ($confirmationEnabled) {
                $this->container->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());
                $route = 'fos_user_registration_check_email';
            } else {
                $authUser = true;

                $this->sendSMS($contact/*, $pass*/, $user);

                /*if ($contact && $pin && $pass) {
                    $this->sendSMS($contact, $pass);
                }*/
                //$route = 'fos_user_registration_confirmed';
                $route        = 'admin_gestion_patient_modifier';
                $params['id'] = $patient->getId();
            }

            $this->setFlash('fos_user_success', 'registration.flash.user_created');
            $url      = $this->container->get('router')->generate($route, $params);
            $response = new RedirectResponse($url);

            if ($authUser) {
                $this->authenticateUser($user, $response);
            }

            return $response;
        } else {
            $errors = $this->container->get('app.form_error')->all($form);

        }

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register.html.' . $this->getEngine(), [
            'form'   => $form->createView(),
            'errors' => $errors,
        ]);
    }



     /**
     * @param $message
     * @param $email
     */
    public function sendSMS($contact/*, $pass*/, $user)
    {
        $message = "Votre compte PASS SANTE a été crée avec succès";           
        $this->container->get('app.ps_sms')->send($message, $contact, $user);              
    }
}
