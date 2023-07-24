<?php

namespace PS\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use PS\ApiBundle\Form\IdentificationType;
use PS\ApiBundle\Form\ProfilType;
use PS\GestionBundle\Entity\Patient;
use PS\ParametreBundle\Entity\Pass;
use PS\GestionBundle\Entity\Abonnement;
use PS\ParametreBundle\Entity\Pack;
use PS\UtilisateurBundle\Entity\Personne;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse; // Utilisation de la vue de FOSRestBundle
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{

    /**
     * @Rest\View()
     * @Rest\Post("/login")
     */
    public function postLoginAction(Request $request)
    {
        
    }

     /**
     * @Rest\View()
     * @Rest\Post("/reset-password")
     */
    public function resetPasswordAction(Request $request)
    {
        $identity = $request->get('identity');

        /** @var $user UserInterface */
        $user = $this->get('fos_user.user_manager')->findUserByUsername($identity);

        if (null === $user) {
            throw $this->createNotFoundException('Ce compte est inexistant');
        }

        if ($user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
            return new JSONResponse([
                'message' => $this->get('translator')->trans('resetting.password_already_requested', [], 'FOSUserBundle')
                ]
                , Response::HTTP_GONE
            ); 
        }

        if (null === $user->getConfirmationToken()) {
            /** @var $tokenGenerator \FOS\UserBundle\Util\TokenGeneratorInterface */
            $tokenGenerator = $this->container->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
        }

        $this->get('session')->set('fos_user_send_resetting_email/email', $this->getObfuscatedEmail($user));
        $this->get('fos_user.mailer')->sendResettingEmailMessage($user);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->get('fos_user.user_manager')->updateUser($user);

        return new JSONResponse(['message' => 'Un mail de confirmation a été envoyé à votre adresse'], Response::HTTP_OK);  
    }


    /**
     * Get the truncated email displayed when requesting the resetting.
     *
     * The default implementation only keeps the part following @ in the address.
     *
     * @param \FOS\UserBundle\Model\UserInterface $user
     *
     * @return string
     */
    protected function getObfuscatedEmail(UserInterface $user)
    {
        $email = $user->getEmail();
        if (false !== $pos = strpos($email, '@')) {
            $email = '...' . substr($email, $pos);
        }

        return $email;
    }
    

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/register")
     */
    public function postRegisterAction(Request $request)
    {

        $form = $this->container->get('fos_user.registration.form');

        $formHandler = $this->container->get('fos_user.registration.form.handler');
        $em                  = $this->container->get('doctrine.orm.entity_manager');

        $process = $formHandler->process(false, true);

        $repPatient = $em->getRepository(Patient::class);
        $repPass = $em->getRepository(Pass::class);
        $repPack = $em->getRepository(Pack::class);

        //return $request->request->all();

        if ($process) {
            $user = $form->getData();

             $personne = new Personne();
            $patient  = new Patient();
            /*$pass     = new Pass();
            $pack     = $repPack->findOneByOrdre(0);*/

            $patient->setPersonne($personne);

            /*$passData = $repPass->generateIdPin();
            $pin      = $passData[1];
            $id       = $passData[0];
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
            //$em = $this->container->get('doctrine')->getManager();
            $em->persist($patient);
            $em->flush();

            $jwtManager = $this->container->get('lexik_jwt_authentication.jwt_manager');

            return ['token' => $jwtManager->create($user)];
        }

        return $form;
    }

    /**
     * @Rest\View()
     * @Rest\Get("/authenticate")
     */
    public function getAuthenticateAction()
    {

    }

    /**
     * @Rest\View()
     * @Rest\Get("/refresh-token/{id}")
     */
    public function getRefreshTokenAction()
    {

    }


    /**
     * @Rest\View(serializerGroups={"patient", "user", "personne", "patient-assurance", "photo", "patient-allergie", "patient-vaccin", "patient-affection", "ville", "groupeSanguin", "rdv", "patient-contact", "patient-medecin", "specialite"})
     * @Rest\Post("/identification")
     */
    public function postIdentificationAction(Request $request)
    {
        
        $em   = $this->getDoctrine()->getEntityManager();
        $form = $this->createForm(IdentificationType::class);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $patient = $em->getRepository(Patient::class)
                ->findByPinPass(
                    $request->get('identifiant')
                    , $request->get('pin')
                );

           
            if ($patient) {
                return $patient;
            }

            return $this->profileNotFound('Profil inexistant');
        }

        return $form;
    }

    /**
    * @Rest\View(serializerGroups={"patient", "personne", "patient-assurance", "photo", "patient-allergie", "patient-vaccin", "patient-affection", "ville", "groupeSanguin", "patient-contact", "patient-medecin", "patient-attribut", "pays"})
     * @Rest\Post("/profil")
     */
    public function postProfilAction(Request $request)
    {
        $em   = $this->getDoctrine()->getEntityManager();
        $form = $this->createForm(ProfilType::class);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $patient = $em->getRepository(Patient::class)
            ->findByPinPass($request->get('identifiant'), $request->get('pin'));



        
            if ($patient) {
                return $patient;
            }

            return $this->profileNotFound('Profil inexistant');
        }

        return $form;
    }

    /**
     * Message d'erreur
     * @param  string $message
     * @return mixed
     */
    private function userNotFound($message)
    {
        return \FOS\RestBundle\View\View::create(['message' => $message], Response::HTTP_NOT_FOUND);
    }

    /**
     * Message d'erreur
     * @param  string $message
     * @return mixed
     */
    private function profileNotFound($message)
    {
        return \FOS\RestBundle\View\View::create(['message' => $message], Response::HTTP_NOT_FOUND);
    }

}
