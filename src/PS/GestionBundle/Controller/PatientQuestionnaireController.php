<?php

namespace PS\GestionBundle\Controller;

use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Entity\PatientQuestionnaire;
use PS\GestionBundle\Entity\QuestionnaireDepistage;

use PS\GestionBundle\Entity\TraitementQuestionnaire;
use PS\GestionBundle\Form\PatientQuestionnaireType;
use PS\GestionBundle\Entity\DiagnosticQuestionnaire;
use PS\GestionBundle\Form\TraitementQuestionnaireType;
use PS\GestionBundle\Service\RowAction;
use PS\GestionBundle\Entity\Urgence;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use PS\GestionBundle\Entity\ValeurLigneQuestionnaire;

/**
 * Patientquestionnaire controller.
 *
 * @Route("admin/gestion/patient-questionnaire")
 */
class PatientQuestionnaireController extends Controller
{
    /**
     * Lists all patientQuestionnaire entities.
     *
     * @Route("/", name="gestion_patientquestionnaire_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {

        $source = new Entity(PatientQuestionnaire::class);

        $params = [];

        $grid = $this->get('grid');

        $source->manipulateQuery(function ($qb) {
            if ($this->isGranted('ROLE_CUSTOMER')) {
                $qb->andWhere('_a.patient = :patient')->setParameter('patient', $this->getUser()->getPatient());
            }

            return $qb->orderBy('_a.date', 'DESC');

        });


        $grid->setSource($source);


        /*if ($this->isGranted('ROLE_CUSTOMER')) {
            $grid->getColumn('patient_nom_complet')->setVisible(false);
        }*/

        $grid->setRouteUrl($this->generateUrl('gestion_patientquestionnaire_index'));

        $rowAction = new RowAction('Détails', 'gestion_patientquestionnaire_show');
        $rowAction->setAttributes(['ajax' => false]);
        $grid->addRowAction($rowAction);

        if ($this->isGranted('ROLE_MEDECIN')) {
            $rowAction = new RowAction('Traitement', 'gestion_patientquestionnaire_traitement');
            $rowAction->setAttributes(['icon' => 'fa fa-check', 'ajax' => false, 'class' => 'btn btn-success']);
            $grid->addRowAction($rowAction);
        }

        return $grid->getGridResponse('gestion/patientquestionnaire/index.html.twig');
    }

    /**
     * Creates a new patientQuestionnaire entity.
     *
     * @Route("/new", name="gestion_patientquestionnaire_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $patientQuestionnaire = new PatientQuestionnaire();
        $form                 = $this->createForm(PatientQuestionnaireType::class, $patientQuestionnaire, [
            'action' => $this->generateUrl('gestion_patientquestionnaire_new'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response      = [];
            $params        = [];
            $redirectRoute = 'gestion_patientquestionnaire_index';
            $redirect      = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($patientQuestionnaire);
                $em->flush();
                $message = 'Opération effectuée avec succès';
                $statut  = 1;
                $this->addFlash('success', $message);
            } else {
                $message = $this->get('app.form_error')->all($form);
                $statut  = 0;
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

        return $this->render('gestion/patientquestionnaire/new.html.twig', [
            'patientQuestionnaire' => $patientQuestionnaire,
            'form'                 => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a patientQuestionnaire entity.
     *
     * @Route("/{id}/show", name="gestion_patientquestionnaire_show")
     * @Method("GET")
     */
    public function showAction(Request $request, PatientQuestionnaire $patient)
    {

        $form = $this->getForm($patient->getQuestionnaire(), $patient->getDonnees());

        return $this->render('gestion/patientquestionnaire/show.html.twig', [
            'patient' => $patient,
            'form'    => $form->createView(),

        ]);
    }

    /**
     * Displays a form to edit an existing patientQuestionnaire entity.
     *
     * @Route("/{id}/edit", name="gestion_patientquestionnaire_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, PatientQuestionnaire $patientQuestionnaire)
    {
        //$deleteForm = $this->createDeleteForm($patientQuestionnaire);
        $form = $this->createForm(PatientQuestionnaireType::class, $patientQuestionnaire, [
            'action' => $this->generateUrl('gestion_patientquestionnaire_edit', ['id' => $patientQuestionnaire->getId()]),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response      = [];
            $params        = [];
            $redirectRoute = 'gestion_patientquestionnaire_index';
            $redirect      = $this->generateUrl($redirectRoute, $params);
            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $message = 'Opération effectuée avec succès';
                $statut  = 1;
                $this->addFlash('success', $message);
            } else {
                $message = $this->get('app.form_error')->all($form);
                $statut  = 0;
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

        return $this->render('gestion/patientquestionnaire/edit.html.twig', [
            'patientQuestionnaire' => $patientQuestionnaire,
            'form'                 => $form->createView(),
        ]);
    }

    /**
     * Deletes a patientQuestionnaire entity.
     *
     * @Route("/{id}/delete", name="gestion_patientquestionnaire_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, PatientQuestionnaire $patientQuestionnaire)
    {
        $form = $this->createDeleteForm($patientQuestionnaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($patientQuestionnaire);
            $em->flush();

            $redirect = $this->generateUrl('gestion_patientquestionnaire_index');

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

        return $this->render('gestion/patientquestionnaire/delete.html.twig', [
            'patientQuestionnaire' => $patientQuestionnaire,
            'form'                 => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a patientQuestionnaire entity.
     *
     * @param PatientQuestionnaire $patientQuestionnaire The patientQuestionnaire entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PatientQuestionnaire $patientQuestionnaire)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'gestion_patientquestionnaire_delete'
                    , [
                        'id' => $patientQuestionnaire->getId(),
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @param QuestionnaireDepistage $questionnaire
     * @param $donnees
     * @return mixed
     */
    private function getForm(QuestionnaireDepistage $questionnaire, $donnees)
    {
        //$lignes    = $questionnaire->getLignes();
         $lignes = $questionnaire->getLignes()->filter(function ($l) {
            return $l->getStatut();
        });
        $builder   = $this->createFormBuilder();
        $namespace = 'Symfony\\Component\\Form\\Extension\\Core\\Type\\';

        foreach ($lignes as $ligne) {
            $valeur = [];
            foreach ($donnees as $donnee) {
                if ($donnee->getLigne() == $ligne) {
                    $valeur = $donnee->getReponse();
                    break;
                }
            }
            $type            = $ligne->getTypeChamp();
            //$valeursPossible = $ligne->getValeurPossible();
            $valeurs         = $ligne->getValeurs()/*$valeursPossible ? array_combine($valeursPossible, $valeursPossible) : []*/;
            $multiple        = $ligne->getMultiple();
            $props           = [
                'label'    => $ligne->getQuestion(),
                'required' => $ligne->getRequis(),
            ];



            $isArray = false;
            if ($type == 'ChoiceType') {
                $props = array_merge($props, [
                    'expanded' => true,
                    'multiple' => false,
                    'choices'  => $valeurs,
                ]);
            } elseif ($type == 'SelectType') {
                $type  = 'ChoiceType';
                $props = array_merge($props, [
                    'expanded' => false,
                    'multiple' => false,
                    'choices'  => $valeurs,
                ]);
            } elseif ($type == 'TextareaType') {
                $props = array_merge($props, [
                    'attr' => ['rows' => 3],
                ]);
            } elseif ($type == 'CheckboxType') {

                $type    = 'ChoiceType';
                $isArray = true;
                $props   = array_merge($props, [
                    'expanded' => true,
                    'multiple' => $multiple,
                    'choices'  => $valeurs,
                ]);
            }

            $props['data'] = $isArray ? $valeur : ($valeur[0] ?? '');
            //dump($valeur);exit;
            $props['attr'] = array_merge($props['attr'] ?? [], ['readonly' => 'readonly', 'disabled' => 'disabled']);


            if (in_array($type, ['ChoiceType', 'SelectType', 'CheckboxType'])) {
                $em = $this->getDoctrine()->getManager();
                $fullType = "Symfony\\Bridge\\Doctrine\\Form\\Type\\EntityType";
                $data = $em->getRepository(ValeurLigneQuestionnaire::class)->findBy(['id' => $valeur]);


                $props['data'] = $isArray ? $data: ($data[0] ?? []) ;
                $props['class'] = ValeurLigneQuestionnaire::class;
                $props['choice_label'] = function ($e) {
                    return $e->getLibelle();
                };
            } else {
                 $fullType = $namespace . $type;
            }

           
            $builder->add('question_' . $questionnaire->getId() . '_' . $ligne->getId(), $fullType, $props);
        }

        return $builder->getForm();
    }

    /**
     * Deletes a questionnaireDepistage entity.
     *
     * @Route("/{id}/traitement", name="gestion_patientquestionnaire_traitement")
     * @Security("is_granted('ROLE_MEDECIN')")
     */
    public function traitementAction(Request $request, PatientQuestionnaire $patient)
    {
        $em = $this->getDoctrine()->getManager();

        $form           = $this->getForm($patient->getQuestionnaire(), $patient->getDonnees());
        $traitement     = new TraitementQuestionnaire();
        $formTraitement = $this->createForm(TraitementQuestionnaireType::class, $traitement, [
            'action' => $this->generateUrl('gestion_patientquestionnaire_traitement', ['id' => $patient->getId()]),
            'method' => 'POST',
            'questionnaire' => $patient->getQuestionnaire()
        ]);
        $formTraitement->handleRequest($request);
        $_patient = $patient->getPatient();

        if ($formTraitement->isSubmitted()) {
            $response      = [];
            $params        = ['id' => $patient->getId()];
            $redirectRoute = 'gestion_patientquestionnaire_show';
            $redirect      = $this->generateUrl($redirectRoute, $params);
            if ($formTraitement->isValid()) {
                $resultat = $traitement->getDiagnostic();
                $patient->setStatut(true);
                $user = $this->getUser();
                $traitement->setPatient($patient);
                $traitement->setHopital($user->getHopital());
                $traitement->setMedecin($user->getMedecin());
                $traitement->setDate(new \DateTime());
                $em->persist($patient);
                $em->persist($traitement);

                 $em->flush();

                $smsManager = $this->get('app.ps_sms');
                $_message    = "Cher(e) abonné(e)\nRéponse à votre test de dépistage COVID-19:\n";
                $_message .= $resultat->getLibelle();
                $_message .= "\n(https://santemousso.net)";

                $this->get('app.action_logger')->add('Traitement questionnaire', $_patient, true);

                $contact = $_patient->getPersonne()->getSmsContact();

                $smsManager->send($_message, [$contact, '08289006']);
                $message = 'Opération effectuée avec succès';
                $statut  = 1;
                $this->addFlash('success', $message);
            } else {
                $message = $this->get('app.form_error')->all($formTraitement);
                $statut  = 0;
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

        return $this->render('gestion/patientquestionnaire/traitement.html.twig', [
            'patient'         => $patient,
            'form'            => $form->createView(),
            'form_traitement' => $formTraitement->createView(),

        ]);
    }


     /**
     * Finds and displays a patientQuestionnaire entity.
     *
     * @Route("/{id}/resultat", name="gestion_patientquestionnaire_resultat")
     * @Method("GET")
     */
    public function resultatAction(Request $request, PatientQuestionnaire $patient)
    {
        $em = $this->getDoctrine()->getManager();
        $questionnaire = $patient->getQuestionnaire();

        //$form = $this->getForm($questionnaire, $patient->getDonnees());
        $diagnostic = $em->getRepository(DiagnosticQuestionnaire::class)->diagnostic($questionnaire, $patient->getPourcentage());

        return $this->render('gestion/patientquestionnaire/resultat.html.twig', [
            'patient' => $patient,
            'diagnostic' => $diagnostic,
            //'form'    => $form->createView(),
        ]);
    }


}
