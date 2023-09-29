<?php

namespace PS\UtilisateurBundle\EventListener;

use FOS\UserBundle\Doctrine\UserManager;
use PS\GestionBundle\Manager\SmsManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

/**
 * Class AfterLoginRedirection
 *
 * @package AppBundle\AppListener
 */
class AfterLoginRedirect implements AuthenticationSuccessHandlerInterface
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
    private $smsManager;

    /**
     * @var mixed
     */
    private $session;

    /**
     * @var mixed
     */
    private $mailer;

    /**
     * AfterLoginRedirection constructor.
     *
     * @param RouterInterface $router
     */
    public function __construct(
        RouterInterface $router
        , UserManager $userManager
        , SmsManager $smsManager
        , SessionInterface $session
        , \Swift_Mailer $mailer) {
        $this->router      = $router;
        $this->userManager = $userManager;
        $this->smsManager  = $smsManager;
        $this->session     = $session;
        $this->mailer      = $mailer;
        //$this->tokenStorage = $tokenStorage;
    }

    /**
     * @param Request        $request
     *
     * @param TokenInterface $token
     *
     * @return RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $user  = $token->getUser();
        $roles = $token->getRoles();

        /*$smsCode  = '';
        $alphabet = '0123456789';
        $alphamax = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; ++$i) {
        $smsCode .= $alphabet[random_int(0, $alphamax)];
        }*/
        $smsCode = $this->getSmsCode();

        //dump($user->getEncoder());


         // Migrate the user to the new hashing algorithm if is using the legacy one
        if ($user->getEncoder() == 'old') {
            // Credentials can be retrieved thanks to the false value of
            // the erase_credentials parameter in security.yml
            $plainPassword = $token->getCredentials();

           
            
            $user->setEncoder('new');
            $user->setPlainPassword($plainPassword);
           
            
            //$this->userManager->updateUser($user);
        }

         $this->userManager->updateUser($user);

      

        // We don't need any more credentials
        $token->eraseCredentials();


        



         return new RedirectResponse($this->router->generate('gestion_homepage'));


        /*$rolesTab = array_map(function ($role) {
            if ($role->getRole() != 'ROLE_SMS') {
                return $role->getRole();
            }
            
        }, $roles);

        $rolesTab = array_filter($rolesTab);



        //dump($this->getContact($rolesTab, $user));exit;


        if (!in_array('ROLE_MEDECIN', $rolesTab, true)) {
            return new RedirectResponse($this->router->generate('gestion_homepage'));
        }


        $redirection = new RedirectResponse(
            $this->router->generate('admin_config_utilisateur_sms_verif'
                , ['id' => $user->getId()]
            )
        );


        if ($user->getSmsCode()) {
            if ($user->getSmsCodeExpiredAt() > new \DateTime()) {
                $user->setSmsCode(null);
                $user->setSmsCodeExpireAt(null);
                $this->userManager->updateUser($user);
                return new RedirectResponse($this->router->generate('fos_user_security_logout'));
            } else {
                return $redirection;
            }
        }


        $contact = $this->getContact($rolesTab, $user);
        $email   = $user->getEmail();
        $label   = $this->getLabel($rolesTab);

        /*$this->session->set('OLD_ROLES', $rolesTab);

        foreach ($rolesTab as $role) {
            $user->removeRole($role);
        }

        $user->addRole("ROLE_SMS");

        if (!$contact) {
    
            $contact = '08289006';
            /*$message = sprintf(
                "Tentative d'accès au compte %s (%s) PASS. Le code de vérification est  %s",
                $label,
                $user->getUsername(),
                $smsCode
            );
        }


        $message = sprintf('Tentative de connexion: Le code de vérification de votre compte medecin PASS MOUSSO est %s. Il expirera dans 15min dès réception', $smsCode);

        $this->sendMail($email, $message);

        //$message = sprintf('Votre code de vérification PASS MOUSSO est %s', $smsCode);
        $this->smsManager->send($message, $contact);
        $user->setSmsCode($smsCode);
        $user->setSmsCodeExpireAt((new \DateTime())->modify('+15 minute'));
        $this->userManager->updateUser($user);

        $redirection = new RedirectResponse(
            $this->router->generate('admin_config_utilisateur_sms_verif'
                , ['id' => $user->getId()]
            )
        );

        return $redirection;*/
    }

    /**
     * @return mixed
     */
    private function getSmsCode()
    {
        $smsCode  = '';
        $alphabet = '0123456789';
        $alphamax = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; ++$i) {
            $smsCode .= $alphabet[random_int(0, $alphamax)];
        }

        return $smsCode;
    }

    /**
     * @param $contact
     * @param $smsCode
     */
    public function getSmsMessage($contact, $smsCode)
    {

    }

    /**
     * @param $rolesTab
     * @return mixed
     */
    public function getLabel($rolesTab)
    {
        $label = 'admin';

        if (in_array('ROLE_MEDECIN', $rolesTab, true)) {
            $label = 'medecin';
        } else if (in_array('ROLE_CUSTOMER', $rolesTab, true)) {
            $label = 'patient';
        } else if (in_array('ROLE_PHARMACIE', $rolesTab, true)) {
            $label = 'pharmacien';
        } else if (in_array('ROLE_INFIRMIER', $rolesTab, true)) {
            $label = 'infirmier';
        }

        return $label;
    }

    /**
     * @param $message
     * @param $email
     */
    public function sendMail($email, $message)
    {

        $message = (new \Swift_Message($message))
            ->setSubject('Code de vérification accès PASS SANTE')
            ->setFrom('info@passpostesante.ci')
            ->setTo($email)
            ->setBody($message, 'text/plain');

        $this->mailer->send($message);

    }

    /**
     * @param $rolesTab
     * @param $user
     * @return mixed
     */
    public function getContact($rolesTab, $user)
    {
        return $user->getPersonne()->getSmsContact();
    }
}
