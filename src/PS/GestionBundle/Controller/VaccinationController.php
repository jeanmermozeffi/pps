<?php

namespace PS\GestionBundle\Controller;

use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Model\Patient as MPatient;
use PS\GestionBundle\Form\Campagne;
use PS\GestionBundle\Entity\Consultation;
use PS\GestionBundle\Form\SearchType;
use PS\ParametreBundle\Entity\PatientVaccin;
use PS\ParametreBundle\Form\PatientVaccinType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use PS\GestionBundle\Entity\ConsultationVaccin;
use  PS\GestionBundle\Form\ListePatientVaccinType;
use PS\GestionBundle\Service\RowAction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * Examen controller.
 *
 * @Route("/admin/gestion/vaccination")
 */
class VaccinationController extends Controller
{
    

    /** 
     * @Route("/search", name="gestion_vaccination_search")
     * @Method({"GET", "POST"})
     * Lists all consultation entities.
     * @Security("is_granted('ROLE_MEDECIN') or is_granted('ROLE_INFIRMIER')")
     */
    public function searchAction(Request $request)
    {
         $session = $request->getSession();
        $session->remove('patient');
        $form = $this->createForm(SearchType::class, [
            'action' => $this->generateUrl('gestion_vaccination_search'),
            'method' => 'POST',
            'with_reference' => true
        ]);

        $form->add('submit', SubmitType::class, ['label' => 'Rechercher']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $identifiant = $form->get('identifiant')->getData();

            $pin = $form->get('pin')->getData();

            $patient = $em->getRepository(Patient::class)->findOneBy(compact('identifiant', 'pin'));

            //var_pmp($count[0][1]);die();

            if (!$patient) {
                $this->addFlash(
                    'patient',
                    'Ce patient n\'existe pas dans la base de données!'
                );
            } else {

                $session->set('patient', $patient->getId());
                $user = $this->getUser();

                if ($contact = $patient->getPersonne()->getSmsContact()) {

                    $smsManager = $this->get('app.ps_sms');
                    $message    = "Accès à votre profil pour vaccination par le Medecin/Infirmier %s";

                    $user     = $this->getUser();
                    $personne = $user->getPersonne();
                    $nom      = $user->getUsername();
                    $hopital  = $user->getHopital();

                    if ($personne->getNomComplet()) {
                        $nom .= '(' . $personne->getNomComplet() . ')';
                    }

                    $this->get('app.action_logger')->add('Page de vaccination', $patient, true);

                    //$smsManager->send(sprintf($message, $nom, $label), $contact);
                }

                return $this->redirectToRoute('gestion_vaccination_index');
            }
        }

        return $this->render('gestion/patient/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/", name="gestion_vaccination_index")
     * @Route("/{id}", name="gestion_vaccination_index_patient")
     * @Method({"GET", "POST"})
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function indexAction(Request $request, Patient $patient = null)
    {
        $em         = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $patient = $patient ?: $em->getRepository(Patient::class)->find($session->get('patient'));

        if (!$patient) {
            $this->addFlash('patient', 'Veuillez renseigner un ID/PIN pour  continuer');

            return $this->redirect($this->generateUrl('gestion_vaccination_search'));
        }


        $patient = $em->getRepository(Patient::class)->find($patient);

        
        $repository = $em->getRepository(PatientVaccin::class);
        $source     = new Entity(PatientVaccin::class);
        $user       = $this->getUser();
        $id         = $user->getPersonne()->getId();

        $grid = $this->get('grid');

        $source->manipulateQuery(function ($qb) use ($patient, $user) {

            if (!$this->isGranted('ROLE_CUSTOMER') && !$patient) {
                $qb->andWhere('_a.hopital = :hopital');
                $qb->setParameter('hopital', $user->getHopital());
            } else {
                $qb->andWhere('_a.patient = :patient');
                $qb->setParameter('patient', $patient);
            }
            
            $qb->orderBy('_a.date', 'DESC');

        });

        $grid->setSource($source);



        /*$rowAction = new RowAction('Rappel', 'gestion_vaccination_new');
        $grid->addRowAction($rowAction);*/

        return $grid->getGridResponse('gestion/vaccination/index.html.twig', [
            'patient' => $patient
        ]);

    }


    public function getPatient(Request $request)
    {
        $session = $request->getSession();
        $em         = $this->getDoctrine()->getManager();
        return $this->getUser()->getPatient() ?: $em->getRepository(Patient::class)->find($session->get('patient'));
    }


    /**
     * @Route("/liste", name="gestion_vaccination_ls")
     * @Method({"GET", "POST"})
     */
    public function listeAction(Request $request)
    {
        $em         = $this->getDoctrine()->getManager();
       
        $source     = new Entity(ConsultationVaccin::class);
        $patient = $this->getPatient($request);

        $grid = $this->get('grid');

        $source->manipulateQuery(function ($qb) use ($patient, $user) {
            $qb->join('_a.consultation', 'c');
            
            $qb->andWhere('c.patient = :patient');

            $qb->setParameter('patient', $patient);
           

        });

        $grid->setSource($source);


        $rowAction = new RowAction('Rappel', 'gestion_vaccination_new');
        $grid->addRowAction($rowAction);


        return $grid->getGridResponse('gestion/vaccination/index.html.twig', [
            //'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="gestion_vaccination_new", methods={"GET", "POST"})
     * @Route("/new/{id}", name="gestion_vaccination_new_patient", methods={"GET", "POST"})
     */
    public function newAction(Request $request,  Patient $patient = null)
    {
        $patient = $this->getPatient($request);
        //$associes = $patient

        if (!$patient) {
            $this->addFlash('patient', 'Veuillez renseigner un ID/PIN pour  continuer');

            return $this->redirect($this->generateUrl('gestion_vaccination_search'));
        }

        $user = $this->getUser();

        $data = new MPatient();

        $data->setPatient($patient);




        $form = $this->createForm(ListePatientVaccinType::class, $data, [
            'action' => $this->generateUrl('gestion_vaccination_new_patient', ['id' => $patient->getId()]),
            'method' => 'POST',
            'patient' => $patient
        ]);

        $form->handleRequest($request);

        $statut  = 0;
        if ($form->isSubmitted()) {
            $response      = [];
            $redirectRoute = 'gestion_vaccination_index';
            $redirect      = $this->generateUrl($redirectRoute/*, compact('id')*/);


            
                
               
            if ($form->isValid()) {
                 foreach ($data->getVaccinations() as $vaccin) {
                    $ligne = new PatientVaccin();
                    $ligne->setPersonne($user->getPersonne());
                    //$ligne->setPatient($patient);
                    $ligne->setVaccin($vaccin->getVaccin());
                    $ligne->setDate($vaccin->getDate());
                    $ligne->setRappel($vaccin->getRappel());
                    $ligne->setHopital($user->getHopital());
                    $patient->addVaccination($ligne);
                }


                $em = $this->getDoctrine()->getManager();
                $em->persist($patient);
                $em->flush();
                $message = 'Opération effectuée avec succès';
                $statut  = 1;
                $this->addFlash('success', $message);
            } else {
                if ($request->isXmlHttpRequest()) {
                    $message = $this->get('app.form_error')->all($form);
                    $statut  = 0;
                    $this->addFlash('warning', $message);
                }
            }

            if ($request->isXmlHttpRequest()) {
                $response = compact('statut', 'message', 'redirect');

                return new JsonResponse($response);
            } else {
                if ($statut == 1) {
                    return $this->redirect($redirect);
                }
            }

        }

        return $this->render('gestion/vaccination/new.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/rappel/{id}", name="gestion_vaccination_rappel", methods={"GET", "POST"})
     * @param Request $request
     * @param PatientVaccin $vaccin
     */
    public function rappelAction(Request $request, PatientVaccin $vaccin)
    {
        $patient = $this->getPatient($request);

        if (!$patient) {
            $this->addFlash('patient', 'Veuillez renseigner un ID/PIN pour  continuer');

            return $this->redirect($this->generateUrl('gestion_vaccination_search'));
        }

        $user = $this->getUser();

        $vaccination = new PatientVaccin();
        $vaccination->setPatient($patient);
        $vaccination->setVaccin($vaccin->getVaccin());
        $vaccination->setPersonne($user->getPersonne());
        $vaccination->setHopital($user->getHopital());
        $form = $this->createForm(PatientVaccinType::class, $vaccination, [
            'action' => $this->generateUrl('gestion_vaccination_rappel', ['id' => $vaccin->getId()]),
            'method' => 'POST',
            'rappel' => true,
        ]);

        return $this->render('gestion/vaccination/rappel.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
