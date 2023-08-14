<?php

namespace PS\UtilisateurBundle\Controller;

use PS\GestionBundle\Service\RowAction;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Entity\Infirmier;
use PS\GestionBundle\Entity\Medecin;
use PS\GestionBundle\Entity\Patient;
use PS\UtilisateurBundle\Entity\InfoPersonne;
use PS\GestionBundle\Form\PatientType;
use PS\UtilisateurBundle\Entity\Utilisateur;
use PS\UtilisateurBundle\Form\CodeVerifType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
//use PS\UtilisateurBundle\Validator\Constraints\UserPassword;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PS\GestionBundle\Form\ExportType;
use PS\GestionBundle\Form\ExportUtilisateurType;
use PS\UtilisateurBundle\Entity\Personne;

/**
 * Utilisateur controller.
 *
 */
class UtilisateurController extends Controller
{
    /**
     * Lists all utilisateur entities.
     *
     */
    public function indexAction(Request $request)
    {
        $source = new Entity(Utilisateur::class);

        $user    = $this->getUser();
        $hopital = $user->getHopital();

        $grid = $this->get('grid');

        $source->manipulateQuery(function ($qb) use ($user) {
            if ($this->isGranted('ROLE_ASSISTANT') || $this->isGranted('ROLE_ADMIN_LOCAL') || $this->isGranted('ROLE_RECEPTION') || $this->isGranted('ROLE_ADMIN_SUP')) {
                if ($this->isGranted('ROLE_LOCAL_ASSURANCE', $user)) {
                    $assurance = $user->getAssurance();
                    $qb->join('UtilisateurBundle:UtilisateurAssurance', 'a', 'WITH', 'a.utilisateur = _a.id')
                        ->andWhere('a.assurance = :assurance')
                        ->andWhere('_a.roles LIKE :role')
                        ->setParameter('assurance', $assurance)
                        ->setParameter('role', '%"ROLE_MEDECIN"%');
                } else if ($this->isGranted('ROLE_ADMIN_SUP')) {

                    $qb->andWhere('_a.roles LIKE :role1')->orWhere('_a.roles LIKE :role2')
                        ->setParameter('role1', '%"ROLE_ADMIN_LOCAL"%')
                        ->setParameter('role2', '%"ROLE_PHARMACIE"%');
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



                $qb->andWhere('_a.roles LIKE \'%"ROLE_ADMIN_CORPORATE"%\' OR _a.roles LIKE \'%"ROLE_ADMIN_LOCAL"%\'');
                $qb->setParameter('corporate', $corporate);
            }



            if ($user->hasRole('ROLE_ADMIN')) {

                $qb->andWhere('_a.roles NOT LIKE \'%"ROLE_SUPER_ADMIN"%\'');
            }
        });

        $grid->setSource($source);

        $rowAction = new RowAction('Détails', 'admin_config_utilisateur_show');

        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'admin_config_utilisateur_edit');

        $grid->addRowAction($rowAction);

        if (!$this->isGranted('ROLE_ASSISTANT')) {
            $rowAction = new RowAction('Supprimer', 'admin_config_utilisateur_delete');
            $rowAction->addManipulateRender(function ($action, $row) use ($user) {
                if ($row->getField('id') != $user->getid()) {
                    return ['controller' => 'UtilisateurBundle:Utilisateur:delete', 'parameters' => ['id' => $row->getField('id')]];
                }
            });

            $rowAction = new RowAction('Désactiver', 'admin_config_utilisateur_deactivate');

            $grid->addRowAction($rowAction);
        }

        $values = $grid->getColumn('roles')->getValues();

        //$roles = $this->get('app.user_manager')->getCurrentVisibileRoles();

        $grid->getColumn('roles')->setFilterable($user->hasRoles(['ROLE_ADMIN', 'ROLE_SUPER_ADMIN', 'ROLE_ADMIN_SUP']));

        //dump($grid->getColumn('roles')->getValues());exit;

        return $grid->getGridResponse('utilisateur/grid.html.twig');
    }

    /**
     * Creates a new utilisateur entity.
     *
     */
    public function newAction(Request $request)
    {

        $idPersonne  = $request->get('idPersonne');
        $user        = $this->getUser();
        $utilisateur = new Utilisateur();
        $form        = $this->createForm($this->get('app.utilisateur_type'), $utilisateur, [
            'action' => $this->generateUrl('admin_config_utilisateur_new'),
            'method' => 'POST',
        ]);

        $em = $this->getDoctrine()->getManager();

        $hopital   = null;
        $corporate = null;

        if ($user->getPersonne()->getCorporate()) {
            $corporate = $user->getPersonne()->getCorporate();
        }

        $url = $this->generateUrl('admin_config_utilisateur_index');
        $errorUrl = $url . '#modal-utilisateur-new';

        if ($user->getHopital() && $user->getHopital()->getInfo()) {
            $form->get('email')->setData($user->getHopital()->getInfo()->getEmailHopital());
        }




        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $response = [];
            $params = [];
            $redirectRoute = 'gestion_admission_index';
            $redirect = $this->generateUrl('admin_config_utilisateur_index');



            $userManager = $this->get('fos_user.user_manager');

            $idHopital   = $form->has('hopital') ? $form->get('hopital')->getData() : null;
            $idPharmacie = $form->has('pharmacie') ? $form->get('pharmacie')->getData() : null;
            $specialites = $form->has('specialites') ? $form->get('specialites')->getData() : [];
            $idCorporate = $form->get('personne')->has('corporate') ? $form->get('personne')->get('corporate')->getData() : null;
            $roles       = $form->has('roles') ? (array)$form->get('roles')->getData() : [];
            $email       = $form->get('email')->getData();
            $assurance = $form->has('assurance') ? $form->get('assurance')->getData() : null;

            $requireHospital = (in_array('ROLE_MEDECIN', $roles, true)
                && !($user->hasRole('ROLE_ADMIN_LOCAL') && $user->getAssurance()))
                || in_array('ROLE_INFIRMIER', $roles, true)
                || in_array('ROLE_RECEPTION', $roles, true)
                || in_array('ROLE_SF', $roles, true);

            if ($user->getHopital() && !in_array('ROLE_ADMIN_CORPORATE', $roles, true)) {
                $hopital = $user->getHopital();
            }

            $requirePharmacie = in_array('ROLE_PHARMACIE', $roles, true);
            $requireCorporate = in_array('ROLE_ADMIN_CORPORATE', $roles, true);

            if ($requireHospital && $form->has('hopital')) {
                if (!$idHopital) {
                    $form->addError(new FormError('Veuillez choisir un hôpital pour cet utilisateur'));
                }
            }

            if ($requirePharmacie && $form->has('pharmacie')) {
                if (!$idPharmacie) {
                    $form->addError(new FormError('Veuillez choisir une pharmacie pour cet utilisateur'));
                }
            }

            if ($requireCorporate) {
                if (!$idCorporate) {
                    $form->addError(new FormError('Veuillez choisir un corporate pour cet utilisateur'));
                }
            }



            if (!isset($hopital)) {
                if ($userManager->findUserByEmail($email)) {
                    $form->addError(new FormError('Cette adresse mail est déjà existante, veuillez en choisir une autre'));
                }
            }

            if ($form->isValid()) {
                if ($user->getHopital() && !$assurance) {
                    $utilisateur->setHopital($hopital);
                }




                if ($corporate) {
                    $utilisateur->getPersonne()->setCorporate($corporate);
                }

                if (!$roles) {
                    $utilisateur->setRoles(['ROLE_CUSTOMER']);
                }
                $utilisateur->setEnabled(true);



                if ($this->isGranted('ROLE_LOCAL_ASSURANCE', $user)) {
                    $utilisateur->setAssurance($user->getAssurance());
                } else {
                    $utilisateur->setAssurance($assurance);
                }



                //$info = new InfoPersonne();

                //$utilisateur->getPersonne()->setInfo($info);

                $userManager->updateUser($utilisateur, false);




                if ($utilisateur->hasRole('ROLE_MEDECIN')) {
                    $medecin = new Medecin();

                    $medecin->setSpecialites($specialites);
                    if ($utilisateur->getHopital()) {
                        $medecin->setHopital($utilisateur->getHopital());
                    }

                    $medecin->setPersonne($utilisateur->getPersonne());
                    $em->persist($medecin);
                } elseif ($utilisateur->hasRole("ROLE_CUSTOMER") || $this->isGranted('ROLE_ASSISTANT')) {
                    $patient = new Patient();
                    $patient->setPersonne($utilisateur->getPersonne());
                    $em->persist($patient);
                } elseif ($utilisateur->hasRole('ROLE_INFIRMIER')) {
                    $infirmier = new Infirmier();
                    $infirmier->setPersonne($utilisateur->getPersonne());
                    $em->persist($infirmier);
                } else {
                    $em->persist($utilisateur);
                }

                $em->flush();

                $message       = 'Opération effectuée avec succès';
                $statut = 1;
                $this->addFlash('success', $message);


                //return new RedirectResponse($url);
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

        return $this->render('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'idPersonne'  => $idPersonne,
            'form'        => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a utilisateur entity.
     *
     */
    public function showAction(Utilisateur $utilisateur)
    {
        //$this->denyAccessUnlessGranted('ROLE_VIEW_USER', $utilisateur);
        $em = $this->getDoctrine()->getManager();

        $enabled = "";
        $locked  = "";

        $role = "";

        if ($utilisateur->isEnabled()) {
            $enabled = "checked";
        }
        /*if ($utilisateur->isLocked()) {
            //$locked = "checked";
        }*/

        $role = "";

        $map = [
            'ROLE_CUSTOMER' => 'Patient',
            'ROLE_INFIRMIER'        => 'Infirmier',
            'ROLE_MEDECIN'          => 'Medecin',
            'ROLE_ADMIN'            => 'Administrateur',
            'ROLE_ADMIN_CORPORATE'  => 'Administrateur groupe médical',
            'ROLE_ADMIN_LOCAL'      => 'Administrateur local',
            'ROLE_PHARMACIE'        => 'Pharmacien',
            'ROLE_SUPER_ADMIN'      => 'Super Administrateur',
            'ROLE_RECEPTION'        => 'Réceptionniste',
            'ROLE_ADMIN_SUP' => 'Admin suppléant'
        ];

        foreach ($map as $_role => $role) {
            if ($utilisateur->hasRole($_role)) {
                break;
            }
        }

        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
            'enabled'     => $enabled,
            'locked'      => $locked,
            'role'        => $role,
        ]);
    }

    /**
     * Displays a form to edit an existing utilisateur entity.
     *
     */
    public function editAction(Request $request, Utilisateur $utilisateur)
    {

        //$this->denyAccessUnlessGranted('ROLE_VIEW_USER', $utilisateur);
        $personne     = $utilisateur->getPersonne();
        $em           = $this->getDoctrine()->getManager();
        $id           = $utilisateur->getId();
        $user         = $this->getUser();



        $oldHopital   = $utilisateur->getHopital();
        $oldPharmacie = $utilisateur->getPharmacie();


        $editForm = $this->createForm($this->get('app.utilisateur_type'), $utilisateur, [
            'action'           => $this->generateUrl('admin_config_utilisateur_edit', ['id' => $id]),
            'method'           => 'POST',
            'passwordRequired' => false,
        ]);







        $redirect      = $this->generateUrl('admin_config_utilisateur_index');
        //$errorUrl = $url . '#modal-edit' . $utilisateur->getId();

        if ($personne && $personne->getMedecin()) {
            $editForm->get('specialites')->setData($personne->getMedecin()->getSpecialites());
        }

        $editForm->handleRequest($request);

        $hopitaux   = $utilisateur->getUtilisateurHopital()->toArray();
        $pharmacies = $utilisateur->getUtilisateurPharmacie()->toArray();
        $assurances = $utilisateur->getUtilisateurAssurance()->toArray();
        if ($utilisateur->getMedecin()) {
            $oldSpecialites =  $utilisateur->getPersonne()->getMedecin()->getSpecialites();
        } else {
            $oldSpecialites = [];
        }






        foreach ($hopitaux as $hopital) {
            $utilisateur->removeUtilisateurHopital($hopital);
        }

        foreach ($pharmacies as $pharmacie) {
            $utilisateur->removeUtilisateurPharmacie($pharmacie);
        }



        foreach ($assurances as $assurance) {
            $utilisateur->removeUtilisateurAssurance($assurance);
        }

        if ($editForm->isSubmitted()) {

            $userManager = $this->get('fos_user.user_manager');

            $idHopital   = $editForm->has('hopital') ? $editForm->get('hopital')->getData() : null;
            $idPharmacie = $editForm->has('pharmacie') ? $editForm->get('pharmacie')->getData() : null;
            $idCorporate = $editForm->get('personne')->has('corporate') ? $editForm->get('personne')->get('corporate')->getData() : null;
            $roles       = $editForm->has('roles') ? $editForm->get('roles')->getData() : [];
            $specialites = $editForm->has('specialites') ? $editForm->get('specialites')->getData() : [];


            //
            $email       = $editForm->get('email')->getData();
            $assurance = $editForm->has('assurance') ? $editForm->get('assurance')->getData() : null;
            $this->get('session')->set('__old_input', $request->request->all());

            $requireHospital = (in_array('ROLE_MEDECIN', $roles, true)
                && !($user->hasRole('ROLE_ADMIN_LOCAL') && $user->getAssurance()))
                || in_array('ROLE_INFIRMIER', $roles, true)
                || in_array('ROLE_RECEPTION', $roles, true);
            $requirePharmacie = in_array('ROLE_PHARMACIE', $roles, true);
            $requireCorporate = in_array('ROLE_ADMIN_CORPORATE', $roles, true);

            if ($requireHospital && $editForm->has('hopital')) {
                if (!$idHopital) {
                    $editForm->addError(new FormError('Veuillez choisir un hôpital pour cet utilisateur'));
                }
            }

            if ($requirePharmacie && $editForm->has('pharmacie')) {
                if (!$idPharmacie) {
                    $editForm->addError(new FormError('Veuillez choisir une pharmacie pour cet utilisateur'));
                }
            }

            if ($requireCorporate) {
                if (!$idCorporate) {
                    $editForm->addError(new FormError('Veuillez choisir un corporate pour cet utilisateur'));
                }
            }

            /*if ($requireHospital || $requirePharmacie || $requireCorporate) {
                if (($_utilisateur = $userManager->findUserByEmail($email)) && $_utilisateur != $utilisateur) {
                    $editForm->addError(new FormError('Cette adresse mail est déjà existante, veuillez en choisir une autre'));
                }
            }*/


            if (!$user->getHopital()) {
                if (($_utilisateur = $userManager->findUserByEmail($email)) && $_utilisateur != $utilisateur) {
                    $editForm->addError(new FormError('Cette adresse mail est déjà existante, veuillez en choisir une autre'));
                }
            }



            if ($editForm->isValid()) {
                $utilisateur->setEnabled(true);
                if ($idHopital) {
                    $utilisateur->setHopital($idHopital);
                } elseif (!$assurance && $user->getHopital() && ($this->isGranted('ROLE_ASSISTANT') || $this->isGranted('ROLE_ADMIN_CORPORATE') || $this->isGranted('ROLE_ADMIN_LOCAL'))) {
                    $utilisateur->setHopital($user->getHopital());
                }


                $utilisateur->setAssurance($assurance);

                if ($utilisateur->hasRole('ROLE_MEDECIN')) {
                    if ($utilisateur->getHopital()) {
                        if (!$utilisateur->getPersonne()->getMedecin()) {
                            $medecin = new Medecin();
                            $medecin->setSpecialites($specialites);
                            if ($utilisateur->getHopital()) {
                                $medecin->setHopital($utilisateur->getHopital());
                            }

                            $medecin->setPersonne($utilisateur->getPersonne());

                            $em->persist($medecin);
                        } else {
                            $utilisateur->getPersonne()->getMedecin()->setHopital($utilisateur->getHopital());
                        }
                    }





                    //($specialites);exit;


                    if ($utilisateur->getPersonne()->getMedecin()) {
                        $utilisateur->getPersonne()->getMedecin()->setSpecialites($specialites);


                        foreach ($oldSpecialites as $specialite) {
                            if (!$specialites->contains($specialite)) {
                                $utilisateur->getPersonne()->getMedecin()->removeSpecialite($specialite);
                            }
                        }
                    }


                    // dump($specialites);exit;



                }

                if ($utilisateur->hasRole('ROLE_PHARMACIE')) {
                    $utilisateur->setPharmacie($idPharmacie);
                }

                if ($utilisateur->hasRole('ROLE_INFIRMIER')) {
                    if (!$em->getRepository(Infirmier::class)->findOneByPersonne($utilisateur->getPersonne())) {
                        $infirmier = new Infirmier();
                        $infirmier->setPersonne($utilisateur->getPersonne());
                        $em->persist($infirmier);
                    }
                }
                $userManager->updateUser($utilisateur, false);
                $em->flush();
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

        return $this->render('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'edit_form'   => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing patient entity.
     *
     */
    public function patientAction(Request $request, Patient $patient)
    {
        $editForm = $this->createEditForm($patient);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted()) {

            //    print_r($editForm);die();
            //var_dump($editForm);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_gestion_patient_modifier', ['id' => $patient->getId()]);
        }

        return $this->render('utilisateur/patient.html.twig', [
            'patient'   => $patient,
            'edit_form' => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Creates a form to create a Consultation entity.
     *
     * @param Patient $patient The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Patient $patient)
    {
        $form = $this->createForm(new PatientType(), $patient, [
            'action' => $this->generateUrl('admin_config_utilisateur_patient', ['id' => $patient->getId()]),
            'method' => 'POST',
        ]);

        $form->add('submit', 'submit', ['label' => 'Modifier']);

        return $form;
    }

    /**
     * Displays a form to edit an existing utilisateur entity.
     *
     */
    public function profilAction(Request $request, Utilisateur $utilisateur)
    {
        //$deleteForm = $this->createDeleteForm($utilisateur);
        $editForm = $this->createForm('PS\UtilisateurBundle\Form\UtilisateurType', $utilisateur, [
            'action'           => $this->generateUrl('admin_config_utilisateur_edit_profil', ['id' => $utilisateur->getId()]),
            'method'           => 'POST',
            'passwordRequired' => false,
        ]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_config_utilisateur_index');
        }

        return $this->render('UtilisateurBundle:Profile:edit.html.twig', [
            'utilisateur' => $utilisateur,
            'edit_form'   => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function unregisterAction(Request $request)
    {
        $utilisateur = $this->getUser();
        $form        = $this->createUnregisterForm($utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($utilisateur);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }

        return $this->render('utilisateur/unregister.html.twig', [
            'form' => $form->createView(),
            'utilisateur' => $utilisateur
            //'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a utilisateur entity.
     *
     */
    public function deleteAction(Request $request, Utilisateur $utilisateur)
    {
        $this->denyAccessUnlessGranted('ROLE_DELETE_USER', $utilisateur);
        $form = $this->createDeleteForm($utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($utilisateur);
            $em->flush();
            return $this->redirectToRoute('admin_config_utilisateur_index');
        }

        if ($request->query->get('action') == 'unregister') {
            $template = 'utilisateur/unregister.html.twig';
        } else {
            $template = 'utilisateur/delete.html.twig';
        }

        return $this->render($template, [
            'utilisateur' => $utilisateur,
            'form'        => $form->createView(),
            //'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Utilisateur $utilisateur
     * @return mixed
     */
    public function deactivateAction(Request $request, Utilisateur $utilisateur)
    {
        $form = $this->createDeactivateForm($utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $utilisateur->setEnabled(!$utilisateur->getEnabled());
            $em->persist($utilisateur);
            $em->flush();

            $redirect =  $this->generateUrl('admin_config_utilisateur_index');

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

        return $this->render('utilisateur/deactivate.html.twig', [
            'utilisateur' => $utilisateur,
            'form'        => $form->createView(),
            //'delete_form' => $deleteForm->createView(),
        ]);
    }



    /**
     * Creates a form to delete a utilisateur entity.
     *
     * @param Utilisateur $utilisateur The utilisateur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Utilisateur $utilisateur)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_config_utilisateur_delete', ['id' => $utilisateur->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }


    /**
     * Creates a form to delete a utilisateur entity.
     *
     * @param Utilisateur $utilisateur The utilisateur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createUnregisterForm(Utilisateur $utilisateur)
    {
        return $this->createFormBuilder()
            ->add('current_password', 'password', [
                'label'              => 'form.current_password',
                'translation_domain' => 'FOSUserBundle',
                'constraints'        => [new UserPassword()]
            ])
            ->add('validate', CheckboxType::class, ['label' => 'Je comprends les risques de mon action'])
            ->setAction($this->generateUrl('admin_config_utilisateur_unregister'))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Creates a form to delete a utilisateur entity.
     *
     * @param Utilisateur $utilisateur The utilisateur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeactivateForm(Utilisateur $utilisateur)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_config_utilisateur_deactivate', ['id' => $utilisateur->getId()]))
            ->setMethod('POST')
            ->getForm();
    }

    /**
     * @param Utilisateur $utilisateur
     */
    private function updateToken(Utilisateur $utilisateur)
    {
        $token = new \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken(
            $utilisateur,
            null,
            'main',
            $utilisateur->getRoles()
        );

        $this->get('session')->set('_security_main', serialize($token));
    }

    /**
     * @param Request $request
     * @param Utilisateur $utilisateur
     * @return mixed
     */
    public function updateRoleAction(Request $request, Utilisateur $utilisateur)
    {
        $this->updateToken($utilisateur);

        //dump($_SESSION);exit;

        if ($request->query->get('fully')) {
        }

        return $request->query->get('fully') ?
            $this->redirectToRoute('gestion_homepage') : $this->redirectToRoute('admin_config_utilisateur_sms_verif', ['id' => $utilisateur->getId()]);
    }

    /**
     * @param Request $request
     * @param Utilisateur $utilisateur
     * @return mixed
     */
    public function codeAction(Request $request, Utilisateur $utilisateur)
    {

        if ($this->getUser() != $utilisateur) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $em   = $this->getDoctrine()->getManager();
        $rep  = $em->getRepository(Utilisateur::class);
        $form = $this->createForm(CodeVerifType::class);

        $userManager = $this->container->get('fos_user.user_manager');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $code = $form->get('smsCode')->getData();
            if (
                ($user = $userManager->findUserBy(['smsCode' => $code, 'id' => $utilisateur->getId()]))
                && $user->getSmsCodeExpiredAt() > new \DateTime()
            ) {

                $utilisateur->setSmsCode(null);
                $utilisateur->setSmsCodeExpireAt(null);


                $userManager->updateUser($utilisateur);



                return $this->redirectToRoute('gestion_homepage');
            }

            $form->addError(new FormError('Le code est incorrect ou a expiré'));
        }

        return $this->render('utilisateur/code.html.twig', ['form' => $form->createView()]);
    }

    public function importAction(Request $request)
    {
        set_time_limit(0);
        $em = $this->getDoctrine()->getManager();
        $util = $this->get('app.psm_util');
        $form   = $this->createForm(ExportUtilisateurType::class);
        $form->handleRequest($request);
        $errors = [];

        $userManager = $this->get('fos_user.user_manager');

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
            $role = $form->get('roles')->getData();

            // Chemin vers le répertoire où les fichiers Excel sont stockés
            $uploadDirectory = $this->get('kernel')->getRootDir() . '/../web/uploads/excel/';

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            $file->move($uploadDirectory, $fileName);

            // Charger le fichier Excel
            $spreadsheet = IOFactory::load($uploadDirectory . $fileName);
            $sheet = $spreadsheet->getActiveSheet();

            // Start from the second row (index 2)
            $rowIndex = 1;
            $importLimit = null; // Limite d'importation

            $userCounter = 0;
            $batchSize = 25; // Nombre d'utilisateurs par flux
            foreach ($sheet->getRowIterator() as $row) {
                if ($rowIndex === 1) {
                    $rowIndex++;
                    continue;
                }

                if ($importLimit !== null) {
                    if ($rowIndex > $importLimit) 
                    {
                        break; // Arrêter la boucle après le nombre souhaitez d'enregistrements
                    }
                }

                $data = [];
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                foreach ($cellIterator as $cell) {
                    $data[] = $cell->getValue();
                }

                $user = $this->createUser($data, $role);

                if ($user['isCreated']) 
                {
                    $data = $user['dataSecurity'];
                    $userCounter++;

                    // Envoie de mail à l'utilisateur
                    // $util->sendMessage($data['email'], $data['username'], $data['password']);
                }

                if ($userCounter % $batchSize === 0) {
                    $em->flush();
                    $em->clear();
                }

                $errors[] = $user['errors'];
                $rowIndex++;
            }

            // Supprimer le fichier Excel
            try {
                if (file_exists($uploadDirectory . $fileName)) {
                    unlink($uploadDirectory . $fileName);
                } else {
                    throw new \Exception("Le fichier à supprimer n'existe pas.");
                }

                if ($errors) {
                    return $this->render('utilisateur/import.html.twig', [
                        'errors' => $errors,
                        'form' => $form->createView(),
                    ]);
                }

                return $this->redirectToRoute('admin_config_utilisateur_index');
            } catch (\Exception $e) {
                // echo "Erreur lors de la suppression du fichier : " . $e->getMessage();
                return $this->render('utilisateur/import.html.twig', [
                    'errors' => $errors,
                    'form' => $form->createView(),
                ]);
            }
        }

        return $this->render('utilisateur/import.html.twig', [
            'errors' => $errors,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param data = []
     */
    private function createUser($data = [], $role = ['ROLE_ADMIN_SUP'], $resent = false, $telephones = null)
    {
        $isCreated = false;
        $em = $this->getDoctrine()->getManager();
        $smsMtarget  = $this->get('app.mtarget_sms');
        $sender = 'COMPTE PSM';
        $authUser = $this->getUser();
        $userManager = $this->get('fos_user.user_manager');
        $util = $this->get('app.psm_util');

        $username = isset($data[0]) ? $data[0] : '';
        // $password = isset($data[1]) ? $data[1] : '';
        $email = isset($data[2]) ? $data[2] : '';
        $fullName = isset($data[3]) ? $data[3] : '';

        $dataErrors = [];
        $dataSecurity = [];

        if (strpos($fullName, ' ') !== false) {
            $nameParts = explode(' ', $fullName);

            $firstName = $nameParts[0];
            $lastNameParts = array_slice($nameParts, 1);
            $lastName = implode(' ', $lastNameParts);
            // list($lastName, $firstName) = explode(' ', $fullName);
        } else {
            $lastName = '';
            $firstName = $fullName;
        }

        $isExistEmail = $userManager->findUserByEmail($email);
        $isExistUsername = $userManager->findUserByUsername($username);

        $utilisateur = $isExistEmail;

        if ($isExistEmail || $isExistUsername) {
            $dataErrors = [
                'email' => $email,
                'username'=> $username,
                'nom'=> $fullName
            ];
        }
       
        if (!$isExistEmail || !$isExistUsername) 
        {
            $personne    = new Personne();
            $personne->setNom($firstName)
                ->setPrenom($lastName)
                ->setDatenaissance(NULL)
                ->setDateInscription(new \DateTime());

            $password = $util->random(8, ['alphabet' => true]);

            $utilisateur = new Utilisateur();
            $utilisateur->setRoles($role)
                ->setEnabled(true)
                ->setEmail($email)
                ->setPlainPassword($password)
                ->setUsername($username)
                ->setPersonne($personne);

            $userManager->updateUser($utilisateur, false);

            $dataSecurity['email'] = $email;
            $dataSecurity['password'] = $password;
            $dataSecurity['username'] = $username;

            $em->persist($utilisateur);
           
            $isCreated = true;
        }

        // if ($resent && $utilisateur && $personne->getSmsContact()) {
        //     $utilisateur->setPlainPassword($password);
        //     $userManager->updateUser($utilisateur, false);

        //     if ($additionalContact) {
        //         $msg = sprintf("Le Profil médical PPS du %s vient d'être crée. Les infos de connexion sont:nLogin: %s\nMot de Passe: %s\nhttps://passpostesante.ci/login", $personne->getSmsContact(), $username, $password);
        //         $smsMtarget->sendSms($additionalContact, $msg, $sender);

        //         $msgID = sprintf("Veuillez garder ces ID\PIN à la proté de toute personne !\nID:%s\nPIN:%s", $identifiant, $pin);
        //         $smsMtarget->sendSms($additionalContact, $msgID, $sender);
        //     }

        //     $msg = sprintf(
        //         "Votre profil médical PPS vient d'être crée. Vos infos de connexion sont:Login: %s\nMot de Passe: %s\nhttps://passpostesante.ci/login",
        //         $username,
        //         $password
        //     );
        //     $smsMtarget->sendSms($contact, $msg, $sender);
        //     $msgID = sprintf("Veuillez garder ces ID\PIN à la proté de toute personne !\nID:%s\nPIN:%s", $identifiant, $pin);
        //     $smsMtarget->sendSms($additionalContact, $msgID, $sender);
        // }
        return [
            'user' => $utilisateur,
            'dataSecurity' => $dataSecurity,
            'errors' => $dataErrors,
            'isCreated' => $isCreated,
        ];
    }
}
