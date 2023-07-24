<?php



// src/Acme/UserBundle/EventListener/PasswordResettingListener.php



namespace PS\UtilisateurBundle\EventListener;



use Doctrine\ORM\EntityManagerInterface;

use FOS\UserBundle\Event\FilterUserResponseEvent;

use FOS\UserBundle\Event\FormEvent;

use FOS\UserBundle\FOSUserEvents;

use FOS\UserBundle\Model\UserManagerInterface;

use PS\GestionBundle\Entity\Abonnement;

use PS\GestionBundle\Entity\Patient;

use PS\GestionBundle\Manager\SmsManagerInterface;

use PS\ParametreBundle\Entity\Pack;

use PS\ParametreBundle\Entity\Pass;

use PS\UtilisateurBundle\Entity\Personne;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;



/**

 * Listener responsible to change the redirection at the end of the password resetting

 */

class UserRegistrationListener implements EventSubscriberInterface

{

    /**

     * @var mixed

     */

    private $router;



    /**

     * @var mixed

     */

    private $userManager;



    /**

     * @var mixed

     */

    private $em;



    /**

     * @var mixed

     */

    private $smsManager;



    /**

     * @param UrlGeneratorInterface $router

     * @param UserManagerInterface $userManager

     */

    public function __construct(UrlGeneratorInterface $router, UserManagerInterface $userManager, EntityManagerInterface $em, SmsManagerInterface $smsManager)

    {

        $this->router = $router;



        $this->userManager = $userManager;



        $this->em = $em;



        $this->smsManager = $smsManager;

    }



    /**

     * {@inheritdoc}

     */

    public static function getSubscribedEvents()

    {

        return [

            FOSUserEvents::REGISTRATION_SUCCESS   => 'onUserRegistrationSuccess',

            FOSUserEvents::REGISTRATION_COMPLETED => 'onUserRegistrationCompleted',

        ];

    }



    /**

     * @param FormEvent $event

     */

    public function onUserRegistrationSuccess(FormEvent $event)

    {

        $request = $event->getRequest();

        $data = $request->request->all();

        $form = $event->getForm();


        $user = $form->getData();        



        $contact = $data['contact'] ?? $data[$form->getName()]['contact'];

       

        $personne = new Personne();

        $personne->setContact($contact);

        $patient = new Patient();

        $patient->setPersonne($personne);

        $personne->setPatient($patient);

        

        $user->setPersonne($personne);

        $user->setRoles(['ROLE_CUSTOMER']);

        $this->em->persist($personne);
        $this->em->persist($patient);
        $this->em->flush();

        $this->sendSMS($contact, $user);

        $url = $this->router->generate('admin_gestion_patient_modifier', ['id' => $user->getPersonne()->getPatient()->getId()]);

        $event->setResponse(new RedirectResponse($url));

    }



    /**

     * @param FilterUserResponseEvent $event

     */

    public function onUserRegistrationCompleted(FilterUserResponseEvent $event)

    {

        $user = $event->getUser();

       


        $url = $this->router->generate('admin_gestion_patient_modifier', ['id' => $user->getPersonne()->getPatient()->getId()]);

       

        $event->setResponse(new RedirectResponse($url));

    }



    /**

     * @param $message

     * @param $email

     */

    private function sendSMS($contact, $user)

    {

        $message = "Votre compte PASS SANTE a été crée avec succès\n";

        

        $message .= "\nConnectez-vous pour modifier votre profil. https://santemousso.net/login";

        

        return $this->smsManager->send($message, $contact, $user);

    }

}

