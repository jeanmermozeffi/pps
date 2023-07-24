<?php

namespace PS\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PS\SiteBundle\Form\PatientRechercherForm;
use PS\SiteBundle\Entity\Enquiry;
use PS\SiteBundle\Form\EnquiryType;
use PS\SiteBundle\Form\ContactForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {


        $lang = $request->getLocale();
        
        $form = $this->createForm(PatientRechercherForm::class);

        return $this->render('SiteBundle:Default:index.html.twig', ['form' => $form->createView(), 'lang' => $lang]);
    }

    public function demoAction()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://billmap.mtn.ci:8443/WebServices/BillPayment.asmx/ProcessOnlinePayment_V1.4",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 360,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,                        
          CURLOPT_SSL_VERIFYPEER => 0,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "Code=MCMPSM&Password=Demo@2018*&MSISDN=22565986832&Reference=123456&Amount=100&MetaData=",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded"
          ),
        ));
        set_time_limit(360);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          echo $response;
        }
    }


    public function paiementAction(Request $request)
    {
        return new Response($request->query->get('ResponseMessage'));
    }

    public function aproposAction()
    {
        return $this->render('SiteBundle:Default:apropos.html.twig');
    }

    public function langAction(Request $request)
    {
        return $this->redirectToRoute('homepage', ['locale' => $request->query->get('_locale')]);
    }

    public function contactAction(Request $request)
    {
        $form = $this->createForm(ContactForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $message = (new \Swift_Message($data['sujet']))
               ->setFrom($data['email'], $data['nom'])
               ->setTo('info@santemousso.net')
               ->setBody($data['message'], 'text/plain');

            if ($this->get('mailer')->send($message)) {
                $this->addFlash('success', 'Votre mail a été envoyé. Nous vous repondrons dans les plus brefs délais!');
            } else {
                $this->addFlash('warning', 'Votre mail n\'a pas été envoyé. Merci réessayer!');
            }

            return $this->redirect($this->generateUrl('homepage')."#contact");
        }

        return $this->render('SiteBundle:Default:contact.html.twig', array('form' => $form->createView()));
    }
    /*public function contactAction()
    {
		$enquiry = new Enquiry();
		$form = $this->createForm(new EnquiryType(), $enquiry);
	
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->bind($request);
	
			if ($form->isValid()) {//var_dump($request->request->get('contact')['email']);die("A. Michael");
				// Perform some action, such as sending an email
				$message = \Swift_Message::newInstance()
					->setSubject('Demande de contact SANTEMOUSSO ')
					->setFrom($request->request->get('contact')['email'])
					->setTo('info@santemousso.net')
					->setBody($this->renderView('SiteBundle:Default:contactEmail.txt.twig', array('enquiry' => $enquiry)));
				$this->get('mailer')->send($message);
		
				$this->get('session')->getFlashBag()->Add('notice', 'Votre demande de contact a été envoyé avec succès. Merci!');
				// Redirect - This is important to prevent users re-posting
				// the form if they refresh the page
                return $this->redirect($this->generateUrl('homepage')."#contact");
			}
			else{
                $this->get('session')->getFlashBag()->Add('notice', 'Votre mail n\'a pas été envoyé. Merci réessayer!');
                return $this->redirect($this->generateUrl('homepage')."#contact");            }
		}

        //return $this->redirect($this->generateUrl('homepage')."#contact");
        return $this->render('SiteBundle:Default:contact.html.twig',array('form' => $form->createView()));
		
    }*/
}
