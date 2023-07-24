<?php

namespace PS\GestionBundle\Controller;

use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Entity\Consultation;
use PS\GestionBundle\Form\SearchType;
use PS\GestionBundle\Form\ExamenType;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Entity\LigneExamen;
use PS\GestionBundle\Entity\Examen;
use PS\GestionBundle\Service\RowAction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Examan controller.
 *
 * @Route("/admin/laboratoire/examen")
 */
class ExamenController extends Controller
{
    /**
     * @var mixed
     */
    private $session;

   
    /**
     * Lists all consultation entities.
     *
     * @Route("/search", name="gestion_examen_search")
     * @Method({"GET", "POST"})
     */
    public function searchAction(Request $request)
    {
        $session = $request->getSession();
        $session->remove('patient');
        $form = $this->createForm(SearchType::class, null, [
            'action'         => $this->generateUrl('gestion_examen_search'),
            'method'         => 'POST',
            'with_reference' => true,
        ]);

        $form->add('submit', SubmitType::class, ['label' => 'Rechercher']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $identifiant = $form->get('identifiant')->getData();
            $reference   = $form->get('reference')->getData();
            $pin         = $form->get('pin')->getData();

            $patient = $em->getRepository(Patient::class)->findOneBy(compact('identifiant', 'pin'));

            //19617726

            if (!$patient) {
                $this->addFlash(
                    'patient',
                    'Ce patient n\'existe pas dans la base de données!'
                );
            } else {
                $consultation = $em->getRepository(Consultation::class)->findOneBy(['refConsultation' => $reference]);

                if ($consultation && ($consultation->getPatient() == $patient) && $consultation->getAnalyses()->count()) {
                    return $this->redirectToRoute('gestion_examen_liste', ['consultation' => $consultation->getId()]);
                }

                $this->addFlash(
                    'patient',
                    'Référence inexistante ou n\'appartenant pas au patient ou ne contient pas d\'examens demandés'
                );

                return $this->redirectToRoute('gestion_examen_search');

            }

            return $this->redirectToRoute('gestion_examen_search');
        }

        return $this->render('gestion/patient/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Lists all examen entities.
     *
     * @Route("/{consultation}/liste", name="gestion_examen_liste")
     * @Method("GET")
     */
    public function listeAction(Request $request, Consultation $consultation)
    {
        $examens = $consultation->getAnalyses();

        return $this->render('gestion/examen/liste.html.twig', [
            'examens'      => $examens,
            'consultation' => $consultation,
        ]);
    }

    /**
     * Lists all examen entities.
     *
     * @Route("/{consultation}/examen", name="gestion_examen_examen")
     * @Method("POST")
     */
    public function examenAction(Request $request, Consultation $consultation)
    {
        $session = $request->getSession();
        $session->set('examens', $request->request->get('examen'));

        return $this->redirect($this->generateUrl('gestion_examen_suivi_new', ['consultation' => $consultation->getId()]));

    }

    /**
     * Lists all examen entities.
     *
     * @Route("/{consultation}/suivi", name="gestion_examen_suivi_new")
     * @Route("/{consultation}/suivi/{id}", name="gestion_examen_suivi_manage")
     */
    public function suiviAction(Request $request, Consultation $consultation, Examen $examen = null)
    {
         $session = $request->getSession();

         $em      = $this->getDoctrine()->getManager();
        if (!$examen) {
             if (!$session->get('examens')) {
                return $this->redirect($this->generateUrl('gestion_examen_liste', ['consultation' => $consultation->getId()]));
             }
            $examen = new Examen();
            $examen->setHopital($this->getUser()->getHopital());
            $examen->setConsultation($consultation);
            $examen->setDate(new \DateTime());
            $examen->setPatient($consultation->getPatient());
            $examens = $session->get('examens');

            //dump($examens);exit;
           
            foreach ($examens as $_examen) {
                //$_examen = $em->getRepository(TypeExamen::class)->find($id);
                if ($_examen) {
                    $ligne = new LigneExamen();
                    $ligne->setLibelle($_examen);
                    $ligne->setDiagnostic('');
                    $ligne->setDetails('');
                    $ligne->setEtat(false);
                    $examen->addLigne($ligne);
                }

            }

        }

        /*$em->persist($examen);
        $em->flush();*/
        $form = $this->createForm(ExamenType::class, $examen, [
            'action' => $this->generateUrl('gestion_examen_suivi_new', ['consultation' => $consultation->getId(), 'id' => $examen ? $examen->getId() : null]),
            'method' => 'POST',
        ]);


        if ($form->isSubmitted() && $form->isValid()) {
         
            $em->persist($examen);
            $em->flush();

        }

        return $this->render('gestion/examen/suivi.html.twig', [
            'examen'       => $examen,
            'consultation' => $consultation,
            'form'         => $form->createView(),
        ]);
    }

    /**
     * Lists all examen entities.
     *
     * @Route("/", name="gestion_examen_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {

        $source = new Entity(Examen::class);

        $grid = $this->get('grid');

        $grid->setSource($source);

        $rowAction = new RowAction('Détails', 'gestion_examen_show');

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'gestion_examen_edit');
        $grid->addRowAction($rowAction);

        /*$rowAction = new RowAction('Supprimer', 'gestion_examen_delete');
        $rowAction->setAttributes(['data-target' => '#stack2']);
        $grid->addRowAction($rowAction);*/

        return $grid->getGridResponse('gestion/examen/index.html.twig');
    }

    /**
     * Creates a new examen entity.
     *
     * @Route("/new", name="gestion_examen_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $examen = new Examan();
        $form   = $this->createForm(ExamenType::class, $examen, [
            'action' => $this->generateUrl('gestion_examen_new'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response      = [];
            $redirectRoute = 'gestion_examen_index';
            $redirect      = $this->generateUrl($redirectRoute);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($examen);
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
                return $this->redirect($redirect);
            }

        }

        return $this->render('gestion/examen/new.html.twig', [
            'examen' => $examen,
            'form'   => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a examen entity.
     *
     * @Route("/{id}/show", name="gestion_examen_show")
     * @Method("GET")
     */
    public function showAction(Request $request, Examen $examen)
    {
        $deleteForm = $this->createDeleteForm($examen);
        $showForm   = $this->createForm(ExamenType::class, $examen);
        $showForm->handleRequest($request);

        return $this->render('gestion/examen/show.html.twig', [
            'examen'      => $examen,
            'show_form'   => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing examen entity.
     *
     * @Route("/{id}/edit", name="gestion_examen_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Examen $examen)
    {
        //$deleteForm = $this->createDeleteForm($examen);
        $form = $this->createForm(ExamenType::class, $examen, [
            'action' => $this->generateUrl('gestion_examen_edit', ['id' => $examen->getId()]),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response      = [];
            $redirectRoute = 'gestion_examen_index';
            $redirect      = $this->generateUrl($redirectRoute);
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
                return $this->redirect($redirect);
            }

        }

        return $this->render('gestion/examen/edit.html.twig', [
            'examen' => $examen,
            'form'   => $form->createView(),
        ]);
    }

    /**
     * Deletes a examen entity.
     *
     * @Route("/{id}/delete", name="gestion_examen_delete")
     * @Method({"DELETE", "GET"})
     */
    public function deleteAction(Request $request, Examen $examen)
    {
        $form = $this->createDeleteForm($examen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($examen);
            $em->flush();

            $redirect = $this->generateUrl('gestion_examen_index');

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

        return $this->render('gestion/examen/delete.html.twig', [
            'examen' => $examen,
            'form'   => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a examen entity.
     *
     * @param Examen $examen The examen entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Examen $examen)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'gestion_examen_delete'
                    , [
                        'id' => $examen->getId(),
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
