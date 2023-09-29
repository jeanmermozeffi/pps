<?php

namespace PS\SiteBundle\Controller;

use PS\GestionBundle\Entity\Pharmacie;
use PS\GestionBundle\Entity\Theme;
use PS\ParametreBundle\Entity\Hopital;
use PS\SiteBundle\Form\ContactForm;
use PS\SiteBundle\Form\PatientRechercherForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {



        $lang = $request->getLocale();

        $csrfToken = $this->get('form.csrf_provider')->generateCsrfToken('authenticate') ?? null;


        $form = $this->createForm(PatientRechercherForm::class, null, ['label_id' => 'home.form.matricule']);

        return $this->render('SiteBundle:Default:index.html.twig', ['form' => $form->createView(), 'lang' => $lang, 'csrf_token' => $csrfToken]);
    }



    public function langAction(Request $request)
    {
        return $this->redirectToRoute('homepage', ['locale' => $request->query->get('_locale')]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function contactAction(Request $request)
    {
        $lang = $request->getLocale();
        $form = $this->createForm(ContactForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $categorie = $data['categorie'];



            if ($categorie == 0) {
                $to = 'wkouassi@santemousso.net';
            } else if (in_array($categorie, [2, 3])) {
                $to = 'dg@mcm-ci.com';
            } else {
                $to = ['info@passpostesante.ci', 'dg@mcm-ci.com'];
            }

            
        $transport = (new \Swift_SmtpTransport('mail.pass-sante.net', 465, 'ssl'))
        ->setUsername('info@pass-sante.net')
        ->setPassword(urldecode('{k7S^8Fu=.XN'));



            $mailer = new \Swift_Mailer($transport);


            $to = 'info@pass-sante.net';

            $message = (new \Swift_Message($transport))
                    ->setSubject($data['sujet'])
                    ->setFrom($data['email'], $data['nom'])
                    ->setTo($to)
            
                ->setBody($data['message'], 'text/plain');

            if ($mailer->send($message)) {
                $this->addFlash('contact.success', $this->get('translator')->trans('home.form.contact.form_message.success'));
            } else {
                $this->addFlash('contact.warning', $this->get('translator')->trans('home.form.contact.form_message.error'));
            }

            return $this->redirect($this->generateUrl('homepage') . "#contact");
        }

        return $this->render('SiteBundle:Default:contact.html.twig', ['form' => $form->createView(), 'lang' => $lang]);
    }



    public function distributionAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $pharmacies = $em->getRepository(Pharmacie::class)->points();
        $hopitaux = $em->getRepository(Hopital::class)->points();
        return $this->render('SiteBundle:Default:distribution.html.twig', [
            'title' => 'home.menu.distrib',
            'pharmacies' => $pharmacies,
            'hopitaux' => $hopitaux
        ]);
    }


    public function franchiseAction(Request $request)
    {
        return $this->render('SiteBundle:Default:franchise.html.twig', ['title' => 'home.menu.franchise']);
    }

    public function sensibilisationAction(Request $request)
    {
        return $this->render('SiteBundle:Default:sensibilisation.html.twig', ['title' => 'home.menu.sensibilisation']);
    }



    public function paiementAction(Request $request)
    {
        /*$transport = (new \Swift_SmtpTransport('mail42.lwspanel.com', 465, 'ssl'))
            ->setUsername('info@pass-sante.net')
            ->setPassword(urldecode('hF4@fVAc*j'));



        $mailer = new \Swift_Mailer($transport);


        $message = (new \Swift_Message('DEMO'))
            ->setFrom('info@pass-sante.net', 'Le blog du PSM')
            ->setTo('wilfried2010w@gmail.com')
            ->setBody(
                'DEMOOOOO',
                'text/html'
            );
        $mailer->send($message, $errors);
        dump($errors);exit;*/
        $smsManager  = $this->get('app.ps_sms');
        $smsManager->send("Message",  "0748649690");
        return new Response();
    }


    public function validationPaiementAction(Request $request)
    {
    }

    public function menuAction(Request $request)
    {
        $lang = $request->getLocale();

        $csrfToken = $this->get('form.csrf_provider')->generateCsrfToken('authenticate') ?? null;

        $form = $this->createForm(PatientRechercherForm::class, null, ['label_id' => 'home.form.matricule']);

        return $this->render('SiteBundle:Default:menu.html.twig', ['form' => $form->createView(), 'lang' => $lang, 'csrf_token' => $csrfToken]);
    }

    public function faqAction(){
        return $this->render('SiteBundle:Default:faq.html.twig', [

        ]);
    }
}
