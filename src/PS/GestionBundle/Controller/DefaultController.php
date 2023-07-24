<?php

namespace PS\GestionBundle\Controller;

use PS\GestionBundle\Service\RowAction;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Entity\Consultation;
use PS\GestionBundle\Form\SearchType;
use PS\GestionBundle\Entity\Infirmier;
use PS\GestionBundle\Entity\Medecin;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Entity\QuestionnaireDepistage;
use PS\UtilisateurBundle\Entity\Utilisateur;
use PS\GestionBundle\Entity\RendezVous;
use PS\GestionBundle\Entity\PatientQuestionnaire;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PS\ParametreBundle\Entity\Pass;

class DefaultController extends Controller
{
    /**
     * @return mixed
     */
    public function indexAction()
    {
        $em =  $this->getDoctrine()->getManager();

        $viewData = [];

        if ($this->getUser()->hasRole('ROLE_INFIRMIER')) {
            return $this->displayGrid();
        }


        if ($this->isGranted('ROLE_CUSTOMER')) {
            $constantes = $em->getRepository(Patient::class)->lastConstantes($this->getUser()->getPatient(), true);
            $questionnaires = $em->getRepository(QuestionnaireDepistage::class)->findAll();
            $viewData['questionnaires'] = $questionnaires;
            $viewData['constantes'] = $constantes;
        }


        if ($this->getUser()->hasRole('ROLE_PHARMACIE')) {
            return $this->displaySearch();
        }

        if ($this->isGranted('ROLE_URGENTISTE')) {
            return $this->forward('GestionBundle:Urgence:index');
        }

        if ($this->isGranted('ROLE_MEDECIN')) {

            //$em           = $this->getDoctrine()->getManager();
            $medecin = $em->getRepository(Medecin::class)->findOneByPersonne($this->getUser()->getPersonne());
            $totalAttentes = $em->getRepository(Consultation::class)->findTotalPending($medecin);
            $listeConsultations = $em->getRepository(Consultation::class)->findPending($medecin);

            $soumissions = $em->getRepository(PatientQuestionnaire::class)->findBySpecialites($medecin->getSpecialites());
            //dump($soumissions, $medecin);exit;

            $consultations = $medecin->getConsultations();
            $totalSub = 0;
            foreach ($consultations as $consultation) {
                $totalSub += $consultation->getMedicaments()->filter(function ($medicament) {
                    return $medicament->getSubstitution();
                })->count();
            }


            $totalRendezVous = $em->getRepository(RendezVous::class)->countTodayPendingByMedecin($medecin);
            $viewData = array_merge($viewData, compact('totalAttentes', 'totalRendezVous', 'totalSub', 'listeConsultations', 'soumissions'));
        }


        if ($this->getUser()->getAssurance()) {
            if ($this->getUser()->hasRole('ROLE_MEDECIN')) {
                return $this->displaySearch('medecin');
            } else {
                return $this->displayUsersOfAssurance();
            }
        }

        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_ADMIN_SUP')) {
            
        }
        //echo $this->getParameter('kernel.root_dir');exit;
        return $this->render('GestionBundle:Default:index.html.twig', $viewData);
    }


    private function displaySearch($role = 'pharmacie')
    {
        return $role == 'pharmacie' ? $this->forward('GestionBundle:Pharmacie:search') : $this->forward('GestionBundle:Consultation:search');
    }


    private function displayUsersOfAssurance()
    {
        $source = new Entity(Utilisateur::class);

        $user    = $this->getUser();
        $hopital = $user->getHopital();

        $grid = $this->get('grid');

        $source->manipulateQuery(function ($qb) use ($user) {
            if ($this->isGranted('ROLE_ASSISTANT') || $this->isGranted('ROLE_ADMIN_LOCAL') || $this->isGranted('ROLE_RECEPTION')) {
                if ($this->isGranted('ROLE_LOCAL_ASSURANCE', $user)) {
                    $assurance = $user->getAssurance();
                    $qb->join('UtilisateurBundle:UtilisateurAssurance', 'a', 'WITH', 'a.utilisateur = _a.id')
                        ->andWhere('a.assurance = :assurance')
                        ->andWhere('_a.roles LIKE :role')
                        ->setParameter('assurance', $assurance)
                        ->setParameter('role', '%"ROLE_MEDECIN"%');
                } else {
                    $hopital = $user->getHopital()->getId();
                    $qb->join('UtilisateurBundle:UtilisateurHopital', 'h', 'WITH', 'h.utilisateur = _a.id')
                        ->andWhere('h.hopital = :hopital');

                    $parameters['hopital'] = $hopital;

                    if ($this->isGranted('ROLE_ASSISTANT')) {
                        $qb->andWhere('_a.roles LIKE :role');
                        $parameters['role'] = '%"ROLE_CUSTOMER"%';
                    }

                    $qb->setParameters($parameters);
                }
            }

            if ($this->isGranted('ROLE_ADMIN_CORPORATE')) {
                $corporate = $user->getPersonne()->getCorporate()->getId();
                $qb->join('UtilisateurBundle:Personne', 'p', 'WITH', 'p.id = _a.personne');
                $qb->andWhere('p.corporate = :corporate');

                $qb->andWhere('_a.roles LIKE \'%"ROLE_ADMIN_CORPORATE"%\'')->orWhere('_a.roles LIKE \'%"ROLE_ADMIN_LOCAL"%\'');
                $qb->setParameter('corporate', $corporate);
            }



            if ($user->hasRole('ROLE_ADMIN')) {

                $qb->andWhere('_a.roles NOT LIKE \'%"ROLE_SUPER_ADMIN"%\'');
            }
        });

        $grid->setSource($source);

        $rowAction = new RowAction('Détails', 'admin_config_utilisateur_show');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'UtilisateurBundle:Utilisateur:show', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'admin_config_utilisateur_edit');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'UtilisateurBundle:Utilisateur:edit', 'parameters' => ['id' => $row->getField('id')]];
            //dump($row);exit;
            /*if ($this->isGranted('ROLE_VIEW_USER', $row->getEntity())) {
                return ['controller' => 'UtilisateurBundle:Utilisateur:edit', 'parameters' => ['id' => $row->getField('id')]];
            }*/
        });
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Désactiver', 'admin_config_utilisateur_deactivate');
        $rowAction->manipulateRender(function ($action, $row) use ($user) {
            if ($row->getField('id') != $user->getid()) {
                return ['controller' => 'UtilisateurBundle:Utilisateur:deactivate', 'parameters' => ['id' => $row->getField('id')]];
            }
        });
        $grid->addRowAction($rowAction);



        //dump($grid->getColumn('roles')->getValues());exit;

        return $grid->getGridResponse('GestionBundle:Default:user.html.twig');
    }

    /**
     * @return mixed
     */
    private function displayGrid()
    {
        //return $this->forward('GestionBundle:Infirmier:historique');
        $em           = $this->getDoctrine()->getManager();
        $infirmier    = $this->getInfirmier();
        $consulations = $em->getRepository(Consultation::class)->findAllInfirmier($infirmier);

        $source = new Entity(Consultation::class);
        $grid   = $this->get('grid');

        $source->initQueryBuilder($consulations);

        $grid->setSource($source);

        $grid->getColumn('statut')->manipulateRenderCell(function ($value) {
            if ($value == -1) {
                return '<span class="label label-default">En attente du medecin</span>';
            }
            return '<span class="label label-success">Validé</span>';
        });

        $rowAction = new RowAction('Modifier', 'admin_gestion_infirmier_consultation');
        $rowAction->manipulateRender(function ($action, $row) {
            $action->setRouteParameters([
                'id' => $row->getField('patient.id'), 'consultation' => $row->getField('id'),
            ]);
            $action->setAttributes(['class' => 'btn btn-info btn-sm']);
            $action->setTitle('<i class="fa fa-edit"></i>');
            return $action;
        });
        $grid->addRowAction($rowAction);

        $grid->getColumn('statut')->setSafe(false);

        $grid->getColumn('motif')->setVisible(false);
        $grid->getColumn('diagnostique')->setVisible(false);
        $grid->getColumn('medecin.hopital.nom')->setVisible(false);

        return $grid->getGridResponse('GestionBundle:Default:grid.html.twig');
    }

    private function getInfirmier()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        return $em->getRepository(Infirmier::class)->findOneByPersonne($user->getPersonne());
    }

    /**
     * @return mixed
     */
    private function createPatientForm()
    {
        $form = $this->createForm(new SearchType(), [
            'action' => $this->generateUrl('admin_pharmacie_search'),
            'method' => 'POST',
        ]);

        $form->add('submit', 'submit', ['label' => 'Rechercher']);

        return $form;
    }


    public function signatureAction()
    {
    }
}
