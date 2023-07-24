<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Entity\Medecin;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Service\RowAction;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Medecin controller.
 *
 */
class MedecinController extends Controller
{
    /**
     * Lists all medecin entities.
     *
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();

        $source = new Entity('GestionBundle:Medecin');

        $grid = $this->get('grid');


        $source->manipulateQuery(function ($qb) use ($user) {

            if ($user->hasRole('ROLE_ADMIN_CORPORATE')) {
                $corporate = $user->getPersonne()->getCorporate()->getId();
                $qb->join('_a.personne', 'p');
                $qb->andWhere('p.corporate = :corporate')->setParameter('corporate', $corporate);
            } elseif ($user->hasRole('ROLE_ADMIN_LOCAL')) {

                if ($this->isGranted('ROLE_LOCAL_ASSURANCE', $user)) {
                    $assurance = $user->getAssurance();
                    $qb->join('UtilisateurBundle:UtilisateurAssurance', 'a', 'WITH', 'a.utilisateur = _a.id')
                        ->andWhere('a.assurance = :assurance')
                        ->setParameter('assurance', $assurance);
                } else {
                    $hopital = $user->getHopital()->getId();
                    $qb->join('_a.personne', 'p');
                    $qb->join('p.utilisateur', 'u');
                    $qb->join('UtilisateurBundle:UtilisateurHopital', 'h', 'WITH', 'h.utilisateur = u.id')
                        ->andWhere('h.hopital = :hopital')
                        ->setParameter('hopital', $hopital);
                }
            }
        });

        $source->manipulateRow(function ($row) {

            if ($row->getField('personne.nom') == '' || $row->getField('personne.prenom') == '') {
                $row->setField('personne.nom', $row->getField('personne.utilisateur.username'));
                $row->setField('personne.prenom', '');
            }
            return $row;
        });



        $grid->setSource($source);




        //dump($grid->getColumns());exit;


        $rowAction = new RowAction('Détails', 'admin_config_medecin_show');
       
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'admin_config_medecin_edit');

        

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Supprimer', 'admin_config_medecin_delete');
       
        $grid->addRowAction($rowAction);




        return $grid->getGridResponse('medecin/grid.html.twig');
    }

    /**
     * Creates a new medecin entity.
     *
     */
    public function newAction(Request $request)
    {
        $medecin = new Medecin();
        $form = $this->createForm($this->get('app.medecin_type'), $medecin, array(
            'action' => $this->generateUrl('admin_config_medecin_new'),
            'method' => 'POST'
        ));
        $form->handleRequest($request);

        $redirect = $this->generateUrl('admin_config_medecin_index');

        if ($form->isValid()) {
            $hopital = $form->get('hopital')->getData() ?: $this->getUser()->getHopital();
            $medecin->getPersonne()->getUtilisateur()->setHopital($hopital);
            $medecin->setHopital($hopital);
            $em = $this->getDoctrine()->getManager();
            $em->persist($medecin);
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


        return $this->render('medecin/new.html.twig', array(
            'medecin' => $medecin,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a medecin entity.
     *
     */
    public function showAction(Medecin $medecin)
    {

        $showForm = $this->createForm($this->get('app.medecin_type'), $medecin);

        $deleteForm = $this->createDeleteForm($medecin);

        return $this->render('medecin/show.html.twig', array(
            'medecin' => $medecin,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }



    public function specialitesAction(Request $request, Medecin $medecin)
    {
        $specialites = $medecin->getSpecialites();

        $response = [];

        foreach ($specialites as $specialite) {
            $response[] = ['id' => $specialite->getId(), 'name' => $specialite->getNom()];
        }

        return new JsonResponse($response);
    }

    /**
     * Displays a form to edit an existing medecin entity.
     *
     */
    public function editAction(Request $request, Medecin $medecin)
    {


        $utilisateur = $medecin->getPersonne()->getUtilisateur();

        $editForm = $this->createForm($this->get('app.medecin_type'), $medecin, array(
            'action' => $this->generateUrl('admin_config_medecin_edit', array('id' => $medecin->getId())),
            'method' => 'POST'
        ));



        $oldSpecialites = $medecin->getSpecialites();
        $oldSpecialites = $oldSpecialites->toArray();

        $hopitaux = $utilisateur->getUtilisateurHopital();
        $hopitaux = $hopitaux->toArray();



        $editForm->handleRequest($request);

        foreach ($oldSpecialites as $specialite) {
            $medecin->removeSpecialite($specialite);
        }

        foreach ($hopitaux as $hopital) {
            $utilisateur->removeUtilisateurHopital($hopital);
        }



        if ($editForm->isSubmitted()) {
            $redirect = $this->generateUrl('admin_config_medecin_index');

            if ($editForm->isValid()) {
                $hopital = $editForm->get('hopital')->getData() ?: $this->getUser()->getHopital();

                $medecin->setHopital($hopital);
                $medecin->getPersonne()->getUtilisateur()->setHopital($hopital);
                $this->getDoctrine()->getManager()->flush();

                $message       = 'Opération effectuée avec succès';
                $statut = 1;
                $this->addFlash('success', $message);
            } else {
                $message = $this->get('app.form_error')->all($editForm);
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

        return $this->render('medecin/edit.html.twig', array(
            'medecin' => $medecin,
            'edit_form' => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a medecin entity.
     *
     */
    public function deleteAction(Request $request, Medecin $medecin)
    {
        $form = $this->createDeleteForm($medecin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($medecin);
            $em->flush();

            $redirect = $this->generateUrl('admin_config_medecin_index');

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



        return $this->render('medecin/delete.html.twig', array(
            'medecin' => $medecin,
            //'edit_form' => $editForm->createView(),
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to delete a medecin entity.
     *
     * @param Medecin $medecin The medecin entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Medecin $medecin)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_config_medecin_delete', array('id' => $medecin->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
