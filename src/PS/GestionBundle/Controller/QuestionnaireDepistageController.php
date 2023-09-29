<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Entity\QuestionnaireDepistage;
use PS\GestionBundle\Entity\PatientQuestionnaire;
use PS\GestionBundle\Entity\DonneeQuestionnaire;
use PS\GestionBundle\Entity\ValeurLigneQuestionnaire;
use PS\GestionBundle\Entity\DiagnosticQuestionnaire;
use PS\GestionBundle\Entity\LigneQuestionnaire;
use Symfony\Component\HttpFoundation\Request;
use PS\GestionBundle\Form\QuestionnaireDepistageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;
use PS\GestionBundle\Entity\Medecin;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Questionnairedepistage controller.
 *
 * @Route("/admin/gestion/questionnaire-depistage")
 */
class QuestionnaireDepistageController extends Controller
{
    /**
     * Lists all questionnaireDepistage entities.
     *
     * @Route("/", name="gestion_questionnairedepistage_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {

        $source = new Entity(QuestionnaireDepistage::class);

        $params = [];

        $grid = $this->get('grid');

        $grid->setSource($source);

        $grid->setRouteUrl($this->generateUrl('gestion_questionnairedepistage_index'));


        if (!$this->isGranted('ROLE_CUSTOMER')) {
            $rowAction = new RowAction('Détails', 'gestion_questionnairedepistage_show');
       
            $grid->addRowAction($rowAction);

            $rowAction = new RowAction('Modifier', 'gestion_questionnairedepistage_edit');
            $grid->addRowAction($rowAction);


             $rowAction = new RowAction('Questions', 'gestion_lignequestionnaire_index');
             $rowAction->setAttributes(['icon' => 'fa fa-question-circle', 'ajax' => false]);
            $grid->addRowAction($rowAction); 



             $rowAction = new RowAction('Statistiques', 'gestion_questionnairedepistage_stat');
             $rowAction->setAttributes(['icon' => 'fa fa-bar-chart', 'ajax' => false]);
            $grid->addRowAction($rowAction); 
        } else {
            $rowAction = new RowAction('Répondre', 'gestion_questionnairedepistage_donnee');
             $rowAction->setAttributes(['ajax' => false]);
            $grid->addRowAction($rowAction);
        }


       

            /*$rowAction = new RowAction('Supprimer', 'gestion_questionnairedepistage_delete');
        $rowAction->setAttributes(['data-target' => '#stack2']);
        $grid->addRowAction($rowAction);*/
    


        return $grid->getGridResponse('gestion/questionnairedepistage/index.html.twig');
    }

    /**
     * Creates a new questionnaireDepistage entity.
     *
     * @Route("/new", name="gestion_questionnairedepistage_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $questionnaireDepistage = new QuestionnaireDepistage();

        $form = $this->createForm(QuestionnaireDepistageType::class, $questionnaireDepistage, [
                'action' => $this->generateUrl('gestion_questionnairedepistage_new'),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);

         

        
        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'gestion_questionnairedepistage_index';
            $redirect = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($questionnaireDepistage);
                //dump($questionnaireDepistage);exit;
                $em->flush();
                $message       = 'Opération effectuée avec succès';
                $statut = 1;
                $this->addFlash('success', $message);
            } else {
                $message = $this->get('app.form_error')->all($form);
                $statut = 0;
                $this->addFlash('warning', $message);
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


        return $this->render('gestion/questionnairedepistage/new.html.twig', [
            'questionnaireDepistage' => $questionnaireDepistage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a questionnaireDepistage entity.
     *
     * @Route("/{id}/show", name="gestion_questionnairedepistage_show")
     * @Method("GET")
     */
    public function showAction(Request $request, QuestionnaireDepistage $questionnaireDepistage)
    {
            $deleteForm = $this->createDeleteForm($questionnaireDepistage);
        $showForm = $this->createForm(QuestionnaireDepistageType::class, $questionnaireDepistage);
    $showForm->handleRequest($request);


    return $this->render('gestion/questionnairedepistage/show.html.twig', [
            'questionnaireDepistage' => $questionnaireDepistage,
            'show_form' => $showForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ]);
    }


     /**
     * Finds and displays a questionnaireDepistage entity.
     *
     * @Route("/{id}/map", name="gestion_questionnairedepistage_map")
     * @Method("GET")
     */
    public function mapAction(Request $request, QuestionnaireDepistage $questionnaireDepistage)
    {
        $em = $this->getDoctrine()->getManager();
        $minYear = $em->getRepository(PatientQuestionnaire::class)->minYear($questionnaireDepistage);
        $mois = [1=>'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        $annees = range($minYear, date('Y'));
        $_mois = intval($request->query->get('mois', date('m')));
        $annee = intval($request->query->get('annee', date('Y')));
        //dump($_mois, $annee);exit;
        return $this->render('gestion/questionnairedepistage/map.html.twig', [
            'questionnaire' => $questionnaireDepistage,
            'annees' => $annees,
            'mois' => $mois,
            '_mois' => $_mois,
            'annee' => $annee
        ]);
    }



     /**
     * Finds and displays a questionnaireDepistage entity.
     *
     * @Route("/{id}/stat", name="gestion_questionnairedepistage_stat")
     * @Method("GET")
     */
    public function statAction(Request $request, QuestionnaireDepistage $questionnaireDepistage)
    {
        $em = $this->getDoctrine()->getManager();
        $minYear = $em->getRepository(PatientQuestionnaire::class)->minYear($questionnaireDepistage);
        $mois = [1=>'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        $annees = range($minYear, date('Y'));
        $_mois = intval($request->query->get('mois', date('m')));
        $annee = intval($request->query->get('annee', date('Y')));
        //dump($_mois, $annee);exit;
        return $this->render('gestion/questionnairedepistage/stat.html.twig', [
            'questionnaire' => $questionnaireDepistage,
            'annees' => $annees,
            'mois' => $mois,
            '_mois' => $_mois,
            'annee' => $annee
        ]);
    }


    /**
     * Finds and displays a questionnaireDepistage entity.
     *
     * @Route("/{id}/donnee-stat", name="gestion_questionnairedepistage_donnee_stat")
     * @Method("GET")
     */
    public function donneeStatAction(Request $request, QuestionnaireDepistage $questionnaireDepistage)
    {
        $_mois = $request->query->get('mois', date('m'));
        $annee = $request->query->get('annee', date('Y'));
    }

    /**
     * Displays a form to edit an existing questionnaireDepistage entity.
     *
     * @Route("/{id}/edit", name="gestion_questionnairedepistage_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, QuestionnaireDepistage $questionnaireDepistage)
    {
        $redirect = $request->query->get('redirect');
        //$deleteForm = $this->createDeleteForm($questionnaireDepistage);
        $form = $this->createForm(QuestionnaireDepistageType::class, $questionnaireDepistage, [
                'action' => $this->generateUrl('gestion_questionnairedepistage_edit', ['id' => $questionnaireDepistage->getId(), 'redirect' => $redirect]),
                'method' => 'POST',
            ]);
        $form->handleRequest($request);
       
        if ($form->isSubmitted()) {
            $response = [];
            $params = [];
            $redirectRoute = 'gestion_questionnairedepistage_index';
            $redirect = $redirect ?: $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $message       = 'Opération effectuée avec succès';
                $statut = 1;
                $this->addFlash('success', $message);
            } else {
                $message = $this->get('app.form_error')->all($form);
                $statut = 0;
                $this->addFlash('warning', $message);
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

        return $this->render('gestion/questionnairedepistage/edit.html.twig', [
            'questionnaireDepistage' => $questionnaireDepistage,
            'form' => $form->createView()
        ]);
    }

    /**
     * Deletes a questionnaireDepistage entity.
     *
     * @Route("/{id}/delete", name="gestion_questionnairedepistage_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, QuestionnaireDepistage $questionnaireDepistage)
    {
        $form = $this->createDeleteForm($questionnaireDepistage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($questionnaireDepistage);
            $em->flush();

            $redirect = $this->generateUrl('gestion_questionnairedepistage_index');

            $message = 'Opération effectuée avec succès';

            $response = [
                'statut'   => 1,
                'message'  => $message,
                'redirect' => $redirect,
            ];

            $this->addFlash('success', $message);

            if (!$request->isXmlHttpRequest()) {
                return $this->redirect($redirect);
            } else {
                return new JsonResponse($response);
            }
        }

        return $this->render('gestion/questionnairedepistage/delete.html.twig', [
            'questionnaireDepistage' => $questionnaireDepistage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a questionnaireDepistage entity.
     *
     * @param QuestionnaireDepistage $questionnaireDepistage The questionnaireDepistage entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(QuestionnaireDepistage $questionnaireDepistage)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                'gestion_questionnairedepistage_delete'
                ,   [
                        'id' => $questionnaireDepistage->getId()
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm()
        ;
    }



    private function getForm(QuestionnaireDepistage $questionnaire)
    {
        $lignes = $questionnaire->getLignes()->filter(function ($l) {
            return $l->getStatut();
        });
        $builder = $this->createFormBuilder();
        $namespace = 'Symfony\\Component\\Form\\Extension\\Core\\Type\\';


        foreach ($lignes as $ligne) {
            $type = $ligne->getTypeChamp();
            //$valeursPossible = $ligne->getValeurPossible();
            $valeurs = $ligne->getValeurs();
            //$valeurs = $valeursPossible ? array_combine($valeursPossible, $valeursPossible): [];
            $multiple = $ligne->getMultiple();
            $props = [
                'label' => $ligne->getQuestion(),
                'required' => $ligne->getRequis()
            ];
            if ($type == 'ChoiceType') {
                $props = array_merge($props, [
                    'expanded' => true,
                    'multiple' => false,
                    'choices' => $valeurs
                ]);
            } elseif ($type == 'SelectType') {
                $type = 'ChoiceType';
                $props = array_merge($props, [
                    'expanded' => false,
                    'multiple' => false,
                    'choices' => $valeurs
                ]);
            } elseif ($type == 'TextareaType') {
                $props = array_merge($props, [
                    'attr' => ['rows' => 3]
                ]);
            } elseif ($type == 'CheckboxType') {
                
                 $type = 'ChoiceType';
               $props = array_merge($props, [
                    'expanded' => true,
                    'multiple' => $multiple,
                    'choices' => $valeurs
                ]);
            }

             if (in_array($type, ['ChoiceType', 'SelectType', 'CheckboxType'])) {
                $fullType = "Symfony\\Bridge\\Doctrine\\Form\\Type\\EntityType";
                $props['class'] = ValeurLigneQuestionnaire::class;
                $props['choice_label'] = function ($e) {
                    return $e->getLibelle();
                };
            } else {
                 $fullType = $namespace . $type;
            }

            //$fullType = $namespace.$type;
            $builder->add('question_'.$questionnaire->getId().'_'.$ligne->getId(), $fullType, $props);
        }

        return $builder->getForm();
    }


     /**
     * Deletes a questionnaireDepistage entity.
     *
     * @Route("/{id}/formulaire", name="gestion_questionnairedepistage_formulaire")
     * @Method({"GET"})
     */
    public function formulaireAction(Request $request, QuestionnaireDepistage $questionnaire)
    {
       

        $form = $this->getForm($questionnaire);
        /*$form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $response = [];

            foreach ($data as $key => $value) {
                [,,$ligne] = explode('_', $key);
                $response[$ligne] = $value;
            }

            dump($response);exit;
        }*/


         return $this->render('gestion/questionnairedepistage/formulaire.html.twig', [
            'questionnaire' => $questionnaire,
            'form'     => $form->createView(),
            
        ]);
    } 




     /**
     * Deletes a questionnaireDepistage entity.
     *
     * @Route("/{id}/donnee", name="gestion_questionnairedepistage_donnee")
     */
    public function donneeAction(Request $request, QuestionnaireDepistage $questionnaire)
    {
        
       $em = $this->getDoctrine()->getManager();


        $form = $this->getForm($questionnaire);
        $form->handleRequest($request);
        $_patient = $this->getUser()->getPatient();

        $specialites = $questionnaire->getSpecialites();

        $medecins = [];

        foreach ($specialites as $specialite) {
            $medecins[] = $em->getRepository(Medecin::class)->findBySpecialite($specialite);
        }


        $medecins = array_flatten($medecins);
        $contacts = [];
        
        foreach ($medecins as $medecin) {
            $contacts[] = $medecin->getPersonne()->getSmsContact();
        }

        $pourcentage = 0;

        $contacts = array_unique($contacts);

        $contacts = array_slice($contacts, 0, 2);

        if ($form->isValid()) {
            $data = $form->getData();

            $patient = new PatientQuestionnaire();
            $patient->setDate(new \DateTime());
            $patient->setPatient($_patient);
            $patient->setQuestionnaire($questionnaire);
            //$patient->setDetails('');


            $response = [];

            $count = 0;

            foreach ($data as $key => $value) {
                [,,$ligneId] = explode('_', $key);
                $ligne = $em->getRepository(LigneQuestionnaire::class)->find($ligneId);
                if ($ligne) {
                    $donnee = new DonneeQuestionnaire();
                    $donnee->setLigne($ligne);
                    $reponse = [];
                    if ($value instanceof ArrayCollection) {
                        $reponse = $value->map(function($v) use(&$pourcentage) {
                            $pourcentage += $v->getPourcentage();
                            return $v->getId();
                        })->toArray();
                    } else {
                        if ($value instanceof ValeurLigneQuestionnaire) {
                            $value = $value->getId();
                        }
                        if ($value) {
                            $pourcentage += $ligne->getPourcentage();
                        }
                        $reponse = (array)$value;
                    }

                    
                    
                    $donnee->setReponse($reponse);

                     $patient->addDonnee($donnee);

                     $count += 1;
                }  
            }

            if ($count > 0) {
                 $diagnostic = $em->getRepository(DiagnosticQuestionnaire::class)->diagnostic($questionnaire, $patient->getPourcentage());

                $patient->setPourcentage($pourcentage);
                $patient->setDiagnostic($diagnostic);
                $em->persist($patient);
                $em->flush();


                $smsManager = $this->get('app.ps_sms');
                $_message    = "Bonjour\nVous avez une demande de dépistage en cours pour le COVID-19: \nVeuillez vous connectez à votre espace Médecin\nPPS(https://santemousso.net)";
                

                $this->get('app.action_logger')->add('Soumission questionnaire', $patient, true);

                $smsManager->send($_message, $contacts);

                $this->addFlash('success', 'Opération effectuée avec succès. Votre demande sera traitée et vous recevrez un SMS');


                return $this->redirectToRoute('gestion_patientquestionnaire_index', ['id' => $patient->getId()]);

            }




             
        }


         return $this->render('gestion/questionnairedepistage/donnee.html.twig', [
            'questionnaire' => $questionnaire,
            'form'     => $form->createView(),
            
        ]);
    } 




     /**
     * Deletes a questionnaireDepistage entity.
     *
     * @Route("/{id}/patient", name="gestion_questionnairedepistage_patient")
     * @Method({"GET"})
     */
    public function patientAction(Request $request, QuestionnaireDepistage $questionnaire)
    {
       

        $patients = $questionnaire->getPatients();
        


         return $this->render('gestion/questionnairedepistage/patient.html.twig', [
            'questionnaire' => $questionnaire,
        ]);
    } 
}
