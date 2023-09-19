<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Entity\Paiement;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Form\PaiementType;
use PS\GestionBundle\Repository\PaiementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class PaiementController extends Controller
{
    /**
     * @Route("timbre/{id}/carnet-sante/pass/poste/sante", name="app_stamp_patient", methods={"GET", "POST"})
     */
    public function newAction(Request $request, Patient $patient)
    {
        $sender = 'COMPTE PSM';
        $identifiant = 76567;
        $pin = 76567;
        $smsMtarget  = $this->get('app.mtarget_sms');
        $msgID = sprintf("Veuillez garder ces ID\PIN à la proté de toute personne !\nID:%s\nPIN:%s", $identifiant, $pin);
        $rest = $smsMtarget->sendSms('0021624374693', $msgID, $sender);
        dump($rest);
        die();


        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $repositotyPatient = $em->getRepository(Patient::class);
        $repositotyPaiement = $em->getRepository(Paiement::class);
        $apiPaiement = $this->get("app.paiement_pro_api");

        $paiement = new Paiement();

        if ($patient === null) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $patient = $repositotyPatient->find($patient);
        $personne = $patient->getPersonne();
        $nom = $personne->getNom();
        $prenoms = $personne->getPrenom();
        $email = 'mangoua.effi@uvci.edu.ci';

        $paiement
            ->setTypeDemande(true)
            ->setNom($nom)
            ->setPrenoms($prenoms)
            ->setEmailPaiement($email)
            ->setMontant(2000);

        $form = $this->createForm(PaiementType::class, $paiement, [
            'action' => $this->generateUrl('app_stamp_patient', [
                'id' => $patient ? $patient->getId() : null
            ]),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $paiement->setDescription("Acheter un timbre | Carnet Santé Medical");

            $data = [
                'amount' => $formData->getMontant(),
                'channel' => $formData->getMoyenPayement(),
                'customerEmail' => $formData->getEmailPaiement(),
                'customerFirstName' => $formData->getNom(),
                'customerLastname' => $formData->getPrenoms(),
                'customerPhoneNumber' => 00225 . $formData->getContact(),
                'referenceNumber' => $formData->getReferencePayement(),
                'description' => $formData->getDescription(),
            ];

            $response = $apiPaiement->setTransact($data);

            if (is_string($response)) 
            {
                $em->persist($paiement);
                // Permet de mettre les données en session
                $session->set('transaction', $data);
                $em->flush();
                return $this->redirect($response, 303);
            }
        }

        return $this->render('GestionBundle:Paiement:new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("print/invoice", name="print_invoice_stamp_patient")
     */
    public function printInvoiceAction()
    {
        return $this->render('GestionBundle:Paiement:print_invoice.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("transcation-succes/carnet-sante/pass/poste/sante", name="app_payement_notif", methods={"GET", "POST"})
     */
    public function payementNotifAction(Request $request): Response
    {
        $transaction = null;

        $transaction = $transaction ? $transaction : $request->getSession()->get('transaction');

        // if (!$transaction) {
        //     return $this->redirectToRoute('app_payement_notif', [], Response::HTTP_SEE_OTHER);
        // }
        return $this->render('payement/notification.html.twig', [
            'transaction' => $transaction,
        ]);
    }

    /**
     * @Route("transcation-reponse/carnet-sante/pass/poste/sante", name="app_payement_redirect", methods={"GET", "POST"})
     */
    public function pretTraitementAction(Request $request): Response
    {
        $dataReponse = [];
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $repositoryPaiement = $em->getRepository(Paiement::class);
        $responseCode = intval($_GET['responsecode']);

        $transaction = $repositoryPaiement->findOneByReference($_GET['referenceNumber']);

        if ($transaction) {
            $date = $transaction->getDatePayement();

            if (isset($_GET['transactiondt']))
            {
                $oldDate = strtotime($_GET['transactiondt']);
                // Nous transformons la date de transcation en un Object DateTime
                $date = new \DateTime(date('Y-m-d H:i:s', $oldDate));
            }


            // On obtient le Tableau de Transcation avec les types
            $dataReponse = [
                'id' => $transaction->getId(),
                'nom' => $transaction->getNom(),
                'prenoms' => $transaction->getPrenoms(),
                'email' => $transaction->getEmailPaiement(),
                'merchantId' => $_GET['merchantId'],
                'montant' => $transaction->getMontant(),
                'devise' => $_GET['countryCurrencyCode'],
                'referencePayement' => $_GET['referenceNumber'],
                'dateTranscation' => $date,
                'IdClient' => intval($_GET['customerId']),
                'TexteContext' => $_GET['returnContext'],
                'reponseCode' => $responseCode,
                'moyenPayement' => $_GET['channel'],
            ];
        }

        // Confirmation du statut de payement
        if ($responseCode === 0) {
            $transaction->setStatutPayement(true);
            $em->persist($transaction);
            $em->flush();
        }

        // Permet de mettre les données en session
        $session->set('transaction', $dataReponse);

        return $this->redirectToRoute('app_payement_return_csm');
    }


    /**
     * @Route("transcation-valider/carnet-sante/pass/poste/sante", name="app_payement_return_csm", methods={"GET", "POST"})
     */
    public function payementSuccess(Request $request, $transaction = null): Response
    {
        $session = $request->getSession();
        $transaction = $session->get('transaction');

        return $this->render('GestionBundle:Paiement:notification.html.twig', [
            'transaction' => $transaction,
        ]);
    }

    /**
     * @Route("imprimer-recue/{id}/carnet-sante/pass/poste/sante", name="app_payement_recu", methods={"GET", "POST"})
     */
    public function imprimerTransactRecu($id): Response
    {
        $em = $this->getDoctrine()->getManager();

        $transaction = $em->getRepository(Paiement::class)->find($id);
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'orientation' => $options['orientation'] ?? 'L',
            'mode' => 'utf-8',
            'format' => 'A4',
            // 'format' => [86, 54],
            'fontDir' => array_merge($fontDirs, [
                $options['fontDir'] ?? []
            ]),
            'fontdata' => $fontData + [
                'comfortaa' => [
                    'B' => 'Comfortaa-Bold.ttf',
                    'R' => 'Comfortaa-Regular.ttf',
                    'L' => 'Comfortaa-Light.ttf',
                ]
            ],
        ]);

        $html = $this->renderView('GestionBundle:Paiement:print-recu-csm.html.twig', [
            'transaction' => $transaction,
        ]);
        $mpdf->WriteHTML($html);
        $mpdf->showImageErrors = true;


        // dd($transaction->getReferencePayement());
        $mpdf->Output('RECU-TIMBRE-CSM-' . $transaction->getReferencePayement() . '.pdf', 'I');

        return new Response();
    }

    // Permet d'obtenir l'utilisateur courant passer dans la session
    public function getCurrentTransact(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $repositotyPaiement = $em->getRepository(Patient::class);
        $paiemment =  $repositotyPaiement->find($session->get('transaction'));

        return $paiemment;
    }
}
