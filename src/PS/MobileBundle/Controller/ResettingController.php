<?php



namespace PS\MobileBundle\Controller;



use DateTime;

use FOS\RestBundle\Controller\Annotations as Rest;

use FOS\RestBundle\View\View;

use FOS\UserBundle\Event\FormEvent;

use FOS\UserBundle\FOSUserEvents;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\UserBundle\Event\GetResponseNullableUserEvent;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use FOS\UserBundle\Model\UserInterface;

use FOS\UserBundle\Event\GetResponseUserEvent;



class ResettingController extends Controller

{



    use ApiTrait;

    /**

     * @Rest\View()

     * @Rest\Post("/reset-password")

     */

    public function postResetAction(Request $request)
    {

        $eventDispatcher = $this->get('debug.event_dispatcher');

        $userManager = $this->get('fos_user.user_manager');

        $username = $request->request->get('username');

        $email = $request->request->get('email');

        $mailer = $this->get('fos_user.mailer');

        $tokenGenerator = $this->get('fos_user.util.token_generator');



        $user = $userManager->findUserBy(compact('username', 'email'));



        $event = new GetResponseNullableUserEvent($user, $request);

        $eventDispatcher->dispatch(FOSUserEvents::RESETTING_SEND_EMAIL_INITIALIZE, $event);

        $retryTtl = $this->getParameter('fos_user.resetting.retry_ttl');



        if (null !== $event->getResponse()) {

            return $event->getResponse();

        }



        if (null !== $user && !$user->isPasswordRequestNonExpired($retryTtl)) {

            $event = new GetResponseUserEvent($user, $request);

            $eventDispatcher->dispatch(FOSUserEvents::RESETTING_RESET_REQUEST, $event);



            if (null !== $event->getResponse()) {

                return $event->getResponse();

            }



            if (null === $user->getConfirmationToken()) {

                $user->setConfirmationToken($tokenGenerator->generateToken());

            }



            $event = new GetResponseUserEvent($user, $request);

            $eventDispatcher->dispatch(FOSUserEvents::RESETTING_SEND_EMAIL_CONFIRM, $event);



            if (null !== $event->getResponse()) {

                return $event->getResponse();

            }



            $code = $this->get('app.psm_util')->random(8);

            $expireAt = (new \DateTime())->modify('+15 minutes');



            $user->setPasswordRequestedAt(new \DateTime());

            $user->setSmsCode($code);

            $user->setSmsCodeExpiredAt($expireAt);



            $userManager->updateUser($user);





            $this->sendResettingEmailMessage($user);

            

           



            return ['message' => $this->get('translator')->trans('resetting.check_email', ['tokenLifetime' => ceil($retryTtl / 3600)], 'FOSUserBundle')];

        }





        return $this->notFound('Compte utilisateur inexistant ou lien déjà envoyé via mail');



    }





    /**

     * {@inheritdoc}

     */

    public function sendResettingEmailMessage(UserInterface $user)

    {

        $mailer = $this->get('fos_user.mailer');

        //$template = $this->parameters['resetting.template'];

        $url = $this->generateUrl('fos_user_resetting_reset', array('token' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);

        $rendered = $this->renderView('MobileBundle:Default:email_mobile.txt.twig', array(

            'user' => $user,

            'confirmationUrl' => $url,

        ));

        $this->sendEmailMessage($rendered, 'noreply@pass-sante.net', (string) $user->getEmail());

    }





    public function sendEmailMessage($renderedTemplate, $fromEmail, $toEmail)
    {
        $transport = (new \Swift_SmtpTransport('mail42.lwspanel.com', 465, 'ssl'))
        ->setUsername('info@pass-sante.net')
        ->setPassword(urldecode('hF4@fVAc*j'));

        // Render the email, use the first line as the subject, and the rest as the body

        $renderedLines = explode("\n", trim($renderedTemplate));

        $subject = array_shift($renderedLines);

        $body = implode("\n", $renderedLines);

        $mailer = new \Swift_Mailer($transport);

        $message = (new \Swift_Message())

            ->setSubject($subject)

            ->setFrom($fromEmail)

            ->setTo($toEmail)

            ->setBody($body);



        $mailer->send($message);

    }





    /**

     * @Rest\View()

     * @Rest\Post("/update-password")

     */

    public function resetAction(Request $request)

    {

        $eventDispatcher = $this->get('debug.event_dispatcher');

       

        $userManager = $this->get('fos_user.user_manager');

        $username = $request->request->get('username');

        $email = $request->request->get('email');

        $smsCode = $request->request->get('code');





        $user = $userManager->findUserBy(['smsCode' => $smsCode, 'username' => $username, 'email' => $email]);



        if (null === $user)  {

            return $this->notFound('api.account_not_exists');

        }



        if ($user->getSmsCodeExpiredAt() < new \DateTime()) {

            return $this->notFound('api.account_sms_code_expired');

        }



        $user->setEncoder('new');



        $event = new GetResponseUserEvent($user, $request);

        $eventDispatcher->dispatch(FOSUserEvents::RESETTING_RESET_INITIALIZE, $event);



        if (null !== $event->getResponse()) {

            return $event->getResponse();

        }



        $form = $this->get('fos_user.resetting.form.factory')->createForm([

            'csrf_protection' => false

        ]);

        $form->setData($user);



        $form->submit(array_only($request->request->all(), 'plainPassword'));



        if ($form->isValid()) {

            $event = new FormEvent($form, $request);

            $eventDispatcher->dispatch(FOSUserEvents::RESETTING_RESET_SUCCESS, $event);



            $user->setSmsCode('');

            $user->setSmsCodeExpiredAt(null);



            $userManager->updateUser($user);



            return ['message' => $this->get('translator')->trans('resetting.flash.success', [], 'FOSUserBundle')];

        }



        return $form;

    }

}

