<?php

namespace PS\GestionBundle\Controller;

use APY\DataGridBundle\Grid\Column\RankColumn;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Entity\Historique;
use PS\GestionBundle\Form\PatientSearchType;
use PS\GestionBundle\Form\PatientType;
use PS\ParametreBundle\Entity\Telephone;
use PS\ParametreBundle\Entity\PatientVaccin;
use PS\SiteBundle\Form\PatientRechercherForm;
use PS\UtilisateurBundle\Entity\CompteAssocie;
use PS\UtilisateurBundle\Entity\Personne;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use PS\GestionBundle\Form\ImportType;
use PS\ParametreBundle\Entity\PatientAffections;
use PS\ParametreBundle\Entity\PatientAllergies;
use PS\GestionBundle\Entity\Corporate;
use PS\ParametreBundle\Entity\Pays;
use PS\ParametreBundle\Entity\Ville;
use PS\ParametreBundle\Entity\GroupeSanguin;
use PS\GestionBundle\Entity\Inscription;
use PS\GestionBundle\Service\RowAction;
use Symfony\Component\Form\FormError;
use PS\ParametreBundle\Entity\Image;
use Doctrine\ORM\QueryBuilder;
use PS\GestionBundle\Service\CustomCheckboxColumn;
use PS\MobileBundle\Form\PhotoType;
use PS\ParametreBundle\Entity\Pass;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use PS\GestionBundle\Service\MtargetApiService;
use Symfony\Component\HttpFoundation\Response;

/**
 * Patient controller.
 *
 */
class PatientController extends Controller
{

    /**
     * Lists all patient entities.
     *
     */
    public function indexAction(Request $request)
    {
        $user   = $this->getUser();
        $source = new Entity(Patient::class);

        $max = $request->query->get('max');
        $date = $request->query->get('date');

        $grid = $this->get('grid');

        $grid->setRouteUrl($this->generateUrl('admin_gestion_patient_index', compact('max', 'date')));


        $source->manipulateQuery(function (QueryBuilder $qb) use ($user, $max, $grid) {
            $filters = $grid->getFilters();




            if ($user->hasRole('ROLE_ADMIN_CORPORATE')) {
                $corporate = $user->getPersonne()->getCorporate()->getId();

                $qb->andWhere('_personne.corporate = :corporate')->setParameter('corporate', $corporate);
            } elseif (
                $user->hasRole('ROLE_ADMIN_LOCAL') ||
                $user->hasRole('ROLE_RECEPTION') ||
                $user->hasRole('ROLE_INFIRMIER') ||
                $user->hasRole('ROLE_MEDECIN')
            ) {
                $hopital = $user->getHopital()->getId();

                $qb->leftjoin('_personne.personneHopital', 'pp')
                    // ->join('UtilisateurBundle:PersonneHopital', 'p0', 'WITH', 'p0.personne = _personne.id')
                    // ->join('UtilisateurBundle:UtilisateurHopital', 'h', 'WITH', 'h.utilisateur = :user')
                    ->andWhere('pp.hopital = :hopital')
                    // ->andWhere('h.hopital = :hopital OR p0.hopital = :hopital')
                    ->setParameter('hopital', $hopital);
                // ->setParameter('user', $user);
            }


            if ($filters) {

                if (!empty($filters['nom_complet'])) {

                    $value = trim($filters['nom_complet']->getValue());
                    $parts = array_map('trim', explode(' ', $value, 2));

                    $qb->orWhere("CONCAT(_personne.nom, ' ', _personne.prenom) LIKE :nom2");
                    $qb->setParameter('nom2', '%' . $value . '%');

                }
            }


            $qb->orderBy('_personne.dateInscription', 'DESC');
            return $qb;
        });


        $grid->setSource($source);

        // $grid->getColumn('select-js')->manipulateRenderCell(function($value) 
        // {
        //     return '<label class="">
        //                         <div class="icheckbox_flat-green checked" aria-checked="true" aria-disabled="false" style="position: relative;">
        //                         <input type="checkbox" class="flat-red" checked="" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
        //                     </label>';
        // });


        // Ajoutez une colonne de case à cocher pour la sélection
        $checkboxColumn = new CustomCheckboxColumn([
            'id'           => 'select-checkbox',  // Identifiant unique pour la colonne
            'title'        => 'Sélection',        // Titre de la colonne
            'select_field' => 'id',               // Champ à utiliser pour la valeur de la case à cocher
        ]);
        // $grid->addColumn($checkboxColumn);

        // Manipulez le rendu de la cellule de la colonne de case à cocher
        $checkboxColumn->manipulateRenderCell(function ($value, $row, $router) {
            $rowId = $row->getField('id');

            $checkboxHtml = sprintf(
                '<input type="checkbox" class="profile-checkbox" value="%s" name="%s[]">',
                htmlspecialchars($rowId),
                'selected_profiles'
            );

            return $checkboxHtml;
        });

        $grid->addColumn(new RankColumn(array('title' => 'N°')), 1);
        $grid->getColumn('sexe')->manipulateRenderCell(function ($value) {
            if ($value == 'M') {
                return 'Masculin';
            }

            return 'Féminin';
        });

        $grid->getColumn('personne.datenaissance')->manipulateRenderCell(function ($value) {
            if ($value !== '-0001-11-30') {
                return $value;
            }

            return '';
        });

        $grid->getColumn('personne.corporate.raisonSociale')
            ->setVisible($user->hasNotRoles(['ROLE_ADMIN_LOCAL', 'ROLE_ADMIN_CORPORATE']))
            ->setFilterable(false);

        $rowAction = new RowAction('Afficher', 'admin_gestion_patient_info');
        $rowAction->addManipulateRender(function ($action, $row) {
            $action->setAttributes(['class' => 'btn btn-success btn-sm', 'ajax' => false]);
            $action->setTitle('<i class="fa fa-file-o"></i>');
            $action->setRouteParameters(['id' => $row->getField('id')]);
            return $action;
        });
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'admin_gestion_patient_modifier');
        $rowAction->addManipulateRender(function ($action, $row) {
            $action->setAttributes(['class' => 'btn btn-primary btn-sm', 'ajax' => false]);
            $action->setTitle('<i class="fa fa-edit"></i>');
            $action->setRouteParameters(['id' => $row->getField('id')]);
            return $action;
        });
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('ResetPassword', 'admin_gestion_patient_reset_password');
        $rowAction->addManipulateRender(function ($action, $row) {
            $action->setAttributes(['class' => 'btn btn-primary btn-sm', 'ajax' => false]);
            $action->setTitle('<i class="fa fa-key"></i>');
            $action->setRouteParameters(['id' => $row->getField('id')]);
            return $action;
        });
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Supprimer', 'admin_gestion_patient_delete');
        $rowAction->addManipulateRender(function ($action, $row) {
            $action->setAttributes(['class' => 'btn btn-danger btn-sm']);
            $action->setTitle('<i class="fa fa-trash"></i>');
            $action->setRouteParameters(['id' => $row->getField('id')]);
            return $action;
        });
        $grid->addRowAction($rowAction);
        //->setRole()

        /*$rowAction = new RowAction('Supprimer', 'admin_gestion_patient_delete');
        $rowAction->manipulateRender(function ($action, $row) {
        return ['controller' => 'GestionBundle:Patient:delete', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);*/

        return $grid->getGridResponse('patient/grid.html.twig');
    }

    /**
     * @return mixed
     */
    private function createPatientForm()
    {
        $form = $this->createForm(PatientSearchType::class, [
            'action' => $this->generateUrl('admin_gestion_patient_index'),
            'method' => 'POST',
        ]);

        $form->add('submit', 'submit', ['label' => " "]);

        return $form;
    }

    /**
     * @return mixed
     */
    private function getPatient()
    {
        return $this->getUser()->getPersonne()->getPatient();
    }

    /**
     * Creates a new patient entity.
     */
    public function newAction(Request $request, Patient $patient = null)
    {
        $em           = $this->getDoctrine()->getManager();
        $_patient     = new Patient();
        $oldAttributs = $_patient->getLigneAttributs();
        $repPass    = $em->getRepository(Pass::class);

        $isAssociated = boolval($patient);

        if ($patient && $this->isGranted('ROLE_CUSTOMER') && $patient != $this->getPatient()) {
            return $this->redirectRoute('admin_gestion_patient_info', ['id' => $patient->getId()]);
        }

        if (!$patient && $this->isGranted('ROLE_CUSTOMER')) {
            $patient = $this->getPatient();
        }

        //$this->denyAccessUnlessGranted('ROLE_EDIT_PATIENT', $patient);
        $form = $this->createCreateForm($_patient, $em, $patient);

        $oldAttributs = $oldAttributs->toArray();

        $authUser = $this->getUser();

        if ((null !== $authUser && $corporate = $authUser->getPersonne()->getCorporate()) || $this->isGranted('ROLE_CUSTOMER')) {

            $form->get('personne')->remove('corporate');
        }

        $form->handleRequest($request);

        foreach ($oldAttributs as $attribut) {
            $_patient->removeLigneAttribut($attribut);
        }

        if ($form->isSubmitted()) {

            if ($form->isValid()) {
                //$familiaux = $form->get('familiaux')->getData();
                if (null !== $authUser && $authUser->getHopital()) {
                    $_patient->getPersonne()->setHopital($this->getUser()->getHopital());
                }

                if (null !== $authUser && $authUser->getPersonne()->getCorporate()) {
                    $_patient->getPersonne()->setCorporate($this->getUser()->getPersonne()->getCorporate());
                }


                /*foreach ($familiaux as $antecedent) {
                    $_patient->addLigneAntecedent($antecedent);
                }*/

                if ($patient) {
                    $associe = new CompteAssocie();
                    $associe->setAssocie($_patient);
                    $associe->setLien($form->get('lien')->getData());
                    $patient->addAssocy($associe);
                    $em->persist($patient);
                }

                if ($data = $repPass->generateIdPin()) {
                    $pass = new Pass();
                    $pass->setIdentifiant($data[0]);
                    $pass->setPin($data[1]);
                    $pass->setActif(true);
                    $pass->setDateCreation(new \DateTime());
                    $_patient->setPin($data[1])
                        ->setIdentifiant($data[0]);
                    $em->persist($pass);
                }

                //                $inscription = new Inscription();
                //
                //                $inscription->setDateInscription(new \DateTime());
                //$inscription->setPatient($_patient);

                //                $_patient->setInscription($inscription);

                //$patient->getPersonne()->setCorporate($this->getUser()->getPersonne()->getCorporate());
                $em->persist($_patient);
                $this->createUser($_patient, false, $form->get('telephones')->getData());

                /*if (null !== $authUser) {
                    $inscription->setUtilisateur($authUser);
                } else {
                    $inscription->setUtilisateur($user);
                }

                $em->persist($inscription);*/

                $em->flush();

                $this->get('app.action_logger')
                    ->add('Création du profil', $_patient);

                if (null !== $authUser) {
                    return $this->redirectToRoute('admin_gestion_patient_info', ['id' => $_patient->getId()]);
                } else {
                    return $this->redirectToRoute('fos_user_security_login');
                }
            } else {
                //dump($form->getErrors(true, true));
            }
        }

        return $this->render('patient/modifier.html.twig', [
            'patient'       => $patient,
            '_patient'      => $_patient,
            'is_associated' => $isAssociated,
            'edit_form'     => $form->createView(),
            'limit'         => '2048Ko (2Mo)',
            //'delete_form' => $deleteForm->createView(),
        ]);
    }


    /**
     * @param Patient $patient
     */
    private function createUser(Patient $patient, $resent = false, $telephones = null)
    {
        $personne    = $patient->getPersonne();
        $email    = $patient->getEmail();
        $contact = $personne->getSmsContact();
        $userManager = $this->get('fos_user.user_manager');
        $util = $this->get('app.psm_util');
        $password = $util->random(8, ['alphabet' => true]);
        $nom = $personne->getNom();
        $prenom = $personne->getPrenom();
        $identifiant = $patient->getIdentifiant();
        $pin = $patient->getPin();
        $nom = str_slug($nom, '_');
        $username = $identifiant ?  $nom . $identifiant : $nom . $util->random(5, ['alphabet' => false]);

        if (!$email) {
            $email = $username . '@santemousso.net';
        }

        $utilisateur = $userManager->findUserBy(['personne' => $personne]);
        $smsManager  = $this->get('app.ps_sms');
        $additionalContact = null;
        $smsMtarget  = $this->get('app.mtarget_sms');
        $sender = 'COMPTE PSM';
        $authUser = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        if ($telephones) {
            $contactProches = $telephones->filter(function ($telephone) {
                return $telephone->getSms();
            });


            if ($contactProches) {
                foreach ($contactProches as $key => $contactProche) {
                    $additionalContact[] = $contactProche->getNumero();
                }
            }
        }

        $inscription = new Inscription();
        $inscription->setDateInscription(new \DateTime());

        /**
         * IF USER LOGGED  AND NOT USER CREATE
         */
        if (null !== $authUser && !$utilisateur) {
            $inscription->setUtilisateur($authUser);
        }

        $inscription->setPatient($patient);

        if (!$utilisateur) {
            $utilisateur = $userManager->createUser();
            $utilisateur->setRoles(['ROLE_CUSTOMER']);
            $utilisateur->setEnabled(true);
            $utilisateur->setEmail($email);

            $utilisateur->setPlainPassword($password);
            $utilisateur->setUsername($username);

            $utilisateur->setPersonne($personne);

            $userManager->updateUser($utilisateur, false);

            $em->persist($utilisateur);

            /**
             * IF NOT USER LOGGED
             */
            $inscription->setUtilisateur($utilisateur);

            $em->persist($inscription);

            $patient->setInscription($inscription);

            if ($personne->getSmsContact()) {
                $this->buildSms($additionalContact, $nom, $username, $password, $identifiant, $pin, $contact);
            }
        }

        if ($resent && $utilisateur && $personne->getSmsContact()) {
            $userManager->updateUser($utilisateur, false);

            if ($additionalContact) {
                $msg = sprintf("Le Profil médical PPS du %s vient d'être crée. Les infos de connexion sont:nLogin: %s\nMot de Passe: %s\nhttps://passpostesante.ci/login", $personne->getSmsContact(), $username, $password);
                $smsMtarget->sendSms($additionalContact, $msg, $sender);

                $msgID = sprintf("Veuillez garder ces ID\PIN à la proté de toute personne !\nID:%s\nPIN:%s", $identifiant, $pin);
                $smsMtarget->sendSms($additionalContact, $msgID, $sender);
            }

            $msg = sprintf(
                "Votre profil médical PPS vient d'être crée. Vos infos de connexion sont:Login: %s\nMot de Passe: %s\nhttps://passpostesante.ci/login",
                $username,
                $password
            );
            $smsMtarget->sendSms($contact, $msg, $sender);
            $msgID = sprintf("Veuillez garder ces ID\PIN à la proté de toute personne !\nID:%s\nPIN:%s", $identifiant, $pin);
            $smsMtarget->sendSms($additionalContact, $msgID, $sender);
        }

        return $utilisateur;
    }

    private function buildSms($additionalContact, $nom, $username, $password, $identifiant, $pin, $contact)
    {
        $smsMtarget  = $this->get('app.mtarget_sms');
        $sender = 'COMPTE PSM';

        if ($additionalContact) {
            $msg = sprintf("Le Profil PASS de %s (GEAN) vient d'être crée. Les infos de connexion sont:nLogin: %s\nMot de Passe: %s\nhttps://passpostesante.ci/login",  $nom, $username, $password);
            $smsMtarget->sendSms($additionalContact, $msg, $sender);

            $msgID = sprintf("Veuillez garder ces identifiant à la proté de toute personne !\nID:%s\nPIN:%s", $identifiant, $pin);
            $smsMtarget->sendSms($additionalContact, $msgID, $sender);
        }

        $msg = sprintf("Votre profil médical PPS vient d'être crée. Vos infos de connexion sont:\nLogin: %s\nMot de Passe: %s\nhttps://passpostesante.ci/login", $username, $password);

        $smsMtarget->sendSMS($contact, $msg, $sender);

        $msgID = sprintf("Veuillez garder ces ID\PIN à la proté de toute personne !\nID:%s\nPIN:%s", $identifiant, $pin);
        $smsMtarget->sendSms($additionalContact, $msgID, $sender);
    }

    /**
     * Finds and displays a patient entity.
     *
     */
    public function showAction(Patient $patient)
    {
        $showForm = $this->createForm(PatientType::class, $patient);

        $deleteForm = $this->createDeleteForm($patient);

        return $this->render('patient/show.html.twig', [
            'patient'     => $patient,
            'show_form'   => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    private function checkAccess(Patient $patient)
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $this->denyAccessUnlessGranted('ROLE_EDIT_PATIENT', $patient);
        }
    }

    /**
     * Finds and displays a patient entity.
     *
     */
    public function infoAction(Patient $patient = null)
    {
        //$showForm = $this->createForm(PatientType::class, $patient);
        $em = $this->getDoctrine()->getManager();

        if (!$patient) {
            $patient = $em->getRepository(Patient::class)->findPatient($this->getUser()->getPersonne()->getId());
        }

        // $this->checkAccess($patient);

        $user = $this->getUser();

        return $this->render('patient/info.html.twig', [
            'user'    => $user,
            'patient' => $patient,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function printAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $patient = $em->getRepository(Patient::class)->findPatient($id);

        return $this->render('patient/print.html.twig', [

            'patient' => $patient,
        ]);
    }

    /**
     * Finds and displays a patient entity.
     *
     */
    public function profilAction(Request $request)
    {
        $em          = $this->getDoctrine()->getManager();
        $patient     = '';
        $form        = $request->request->get('patient_recherche');
        $identifiant = $request->query->get('identifiant');
        $pin         = $request->query->get('pin');
        //$localisation = $request->query->get('localisation');
        $contact     = '';
        $user        = $this->getUser();
        $error       = '';
        $count       = false;

        $isPost = false;

        $session = $this->get('session');

        if ($request->isMethod('POST')) {

            $isPost = true;

            $identifiant = $form['identifiant'];
            $smsManager  = $this->get('app.ps_sms');
            $smsMtarget  = $this->get('app.mtarget_sms');
            $senderNameCompte = 'COMPTE PSM';
            $senderNameUrgence = 'URGENCE PSM';

            $patient = $em->getRepository(Patient::class)->findOneByMatricule($identifiant);


            $logger = $this->get('app.action_logger');

            $pin     = $form['pin'] ?? '';
            $contact = $form['contact'] ?? '';
            $localisation = $form['localisation'] ?? '';
            $urgence = $form['urgence'] ?? false;

            if ($pin && $contact && $identifiant) {

                if ($session->get('c_' . $identifiant) > 3) {
                    $error = sprintf('Nombre maximum d\'essais atteint', $identifiant);
                } else {

                    $patient = $em->getRepository(Patient::class)->findOneBy(compact('identifiant', 'pin'));

                    if ($patient) {
                        $logger->add("Votre profil médical a été consulté par le N° {$contact}", $patient, true);

                        $telephones = array_slice($patient->getTelephones()->toArray(), 0);

                        $session->set('profile_identifiant', $identifiant);
                        $session->set('profile_pin', $pin);

                        $personne = $patient->getPersonne();

                        $contactPersonne = $personne->getSmsContact();

                        if ($contactPersonne && $contactPersonne != $contact) {
                            $msg = sprintf("Votre profil médical vient d'être consulté par le N° %s \nPASS POSTE SANTE CI(https://santemousso.net)", $contact);
                            $smsMtarget->sendSms($contactPersonne, $msg, $senderNameCompte);
                        }

                        if ($urgence) {
                            foreach ($telephones as $telephone) {
                                if ($telephone && $telephone->getSms()) {
                                    $nomComplet = $personne->getNomComplet();
                                    $numeroParent = $telephone->getNumero();
                                    $msg = sprintf("Urgence possible concernant %s. Contactez le %s \nPASS POSTE SANTE CI", $nomComplet, $contact);
                                    $smsMtarget->sendSms($numeroParent, $msg, $senderNameUrgence);

                                    if (!is_null($localisation)) {
                                        $msgUrgence = sprintf("Localisation estimée de l'urgence concernant %s\n: %s", $nomComplet, $localisation);
                                        $smsMtarget->sendSms($numeroParent, $msgUrgence, $senderNameUrgence);
                                    }
                                }
                            }
                        }

                        $session->set('sms_' . $identifiant, new \DateTime());


                        return $this->render('patient/info.html.twig', [
                            'patient' => $patient,
                        ]);
                    } else {
                        $logger->add('Erreur ID/PIN', $patient, true, Historique::PROFILE_ID_ERROR, $identifiant);
                        $session->set('c_' . $identifiant, intval($session->get('c_' . $identifiant) + 1));
                        if ($patient) {
                            $contactPersonne = $patient->getPersonne()->getSmsContact();
                            $msg = sprintf("Tentative d'accès à votre par le numéro %s\nPASS POSTE SANTE CI(https://santemousso.net)", $contact);
                            $smsMtarget->sendSms($contactPersonne, $msg, $senderNameCompte);
                        }
                        $error = sprintf('Ce ID/PIN n\'existe pas dans notre base de données! ou n\'est pas associé à un compte patient. <a href="%s">Connexion</a>', $this->generateUrl('fos_user_security_login'));
                    }
                }
            } else {
                $error = 'Veuillez renseigner l\'ID, le PIN et votre contact pour continuer';
            }
        }

        $form = $this->createForm(PatientRechercherForm::class, null, [
            'action' => $this->generateUrl('patient_profil', ['pin' => $pin]),
        ]);
        $form->get('identifiant')->setData($identifiant);
        $form->get('pin')->setData($pin);
        $form->get('contact')->setData($contact);

        return $this->render(
            'patient/profil_new.html.twig',
            [
                'form' => $form->createView(), 'identifiant' => $identifiant, 'pin' => $pin, 'error' => $error, 'is_post' => $isPost,
            ]
        );
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function scanneAction(Request $request)
    {
        $em      = $this->getDoctrine()->getManager();
        $patient = '';
        //M1kha1l$

        if ($request->isMethod('GET')) {
            $form        = $request->request->get('patient_recherche');
            $identifiant = $request->query->get("id");

            if ($identifiant != '') {
                //$count = $em->getRepository(Patient::class)->findByPass($identifiant);
                $patient = $em->getRepository(Patient::class)->findByIdentifiant($identifiant);

                if (!$patient) {
                    $this->addFlash(
                        'patient',
                        'Votre identifiant n\'existe pas dans la base de données!'
                    );
                } else {
                    $patient = $patient[0];
                }
            }
        }

        //$reponse = $em->getRepository(Patient::class)->findById();

        //$patient = $reponse[0];

        return $this->render('patient/info.html.twig', [
            'patient' => $patient,
        ]);
    }

    /**
     * Displays a form to edit an existing patient entity.
     *
     */
    public function editAction(Request $request, Patient $patient)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $deleteForm = $this->createDeleteForm($patient);
        $currentPatient = $this->getPatient();
        $isAssociated = ($currentPatient && ($patient != $currentPatient));

        $editForm = $this->createEditForm($patient, $isAssociated);
        // $editForm->remove('email');

        $editForm->handleRequest($request);
        $personne = $editForm->get('personne')->getData();
        $utilisateur = $personne->getUtilisateur();

        $email = $personne->getUtilisateur()->getEmail();
        $editForm->get('email')->setData($email);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $email = $patient->getEmail();

            $utilisateur->setEmail($email);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_gestion_patient_index');
        }

        return $this->render('patient/modifier.html.twig', [
            'patient'     => $patient,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'limit'         => '2048Ko (2Mo)',

            '_associes'     => $patient->getAssocies()->toArray(),
            //'delete_form' => $deleteForm->createView(),

        ]);
    }

    /**
     * Displays a form to edit an existing patient entity.
     *
     */
    public function modifierAction(Request $request, Patient $patient)
    {
        $em             = $this->getDoctrine()->getManager();
        $currentPatient = $this->getPatient();

        $email = $patient->getPersonne()->getUtilisateur()->getEmail();
        $patient->setEmail($email);

        $isAssociated = ($currentPatient && ($patient != $currentPatient));

        $this->checkAccess($patient);

        $oldAttributs = $patient->getLigneAttributs();
        //$oldAntecedents = $patient->getLigneAntecedents();

        /*$familiaux = $oldAntecedents->filter(function ($antecedent) {
            return $antecedent->getType() == 'familial';
        });*/




        //$this->denyAccessUnlessGranted('ROLE_EDIT_PATIENT', $patient);

        $repAssocie = $em->getRepository(CompteAssocie::class);
        $associe    = $repAssocie->findOneBy(['associe' => $patient, 'patient' => $currentPatient]);

        $editForm = $this->createEditForm($patient, $isAssociated);

        //$editForm->get('familiaux')->setData($familiaux);

        if ($this->getUser()->getPersonne()->getCorporate() || $this->isGranted('ROLE_CUSTOMER')) {

            $editForm->get('personne')->remove('corporate');
        }

        $oldAttributs = $oldAttributs->toArray();
        //$oldAntecedents = $oldAntecedents->toArray();

        if ($associe && $editForm->has('lien')) {
            $editForm->get('lien')->setData($associe->getLien());
        }

        $editForm->handleRequest($request);

        foreach ($oldAttributs as $attribut) {
            $patient->removeLigneAttribut($attribut);
        }


        /*foreach ($oldAntecedents as $antecedent) {
            $patient->removeLigneAntecedent($antecedent);
        }*/

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if ($isAssociated) {

                if (!$associe) {
                    $associe = new CompteAssocie();
                    $associe->setAssocie($patient);
                } else {
                    $currentPatient->removeAssocie($associe);
                }

                $associe->setLien($editForm->get('lien')->getData());
                $currentPatient->addAssocy($associe);
                $em->persist($currentPatient);
            }



            //$familiaux = $editForm->get('familiaux')->getData();


            /*foreach ($familiaux as $antecedent) {
                $patient->addLigneAntecedent($antecedent);
            }*/

            $em->persist($patient);

            $this->createUser($patient, $editForm->get('resent_login')->getData(), $editForm->get('telephones')->getData());

            $this->get('app.action_logger')
                ->add('Modification du profil', $patient);
            $em->flush();

            return $this->redirectToRoute('admin_gestion_patient_info', ['id' => $patient->getId()]);
        }

        return $this->render('patient/modifier.html.twig', [
            'patient'       => $patient,
            'edit_form'     => $editForm->createView(),
            'is_associated' => $isAssociated,
            'limit'         => '2048Ko (2Mo)',

            '_associes'     => $patient->getAssocies()->toArray(),
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
    private function createEditForm(Patient $patient, $isAssociated)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $form          = $this->createForm(PatientType::class, $patient, [
            'action'         => $this->generateUrl('admin_gestion_patient_modifier', ['id' => $patient->getId()]),
            'method'         => 'POST',
            'entity_manager' => $entityManager,
            'is_associated'  => $isAssociated,

        ]);

        $form->add('submit', SubmitType::class, ['label' => 'Enregistrer']);

        return $form;
    }

    /**
     * Creates a form to create a Patient entity.
     *
     * @param Patient $patient The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Patient $currentPatient, $em, $patient)
    {

        $form = $this->createForm(PatientType::class, $currentPatient, [
            'action'         => $this->generateUrl('admin_gestion_patient_new', ['id' => $patient ? $patient->getId() : null]),
            'method'         => 'POST',
            'entity_manager' => $em,
            'is_associated'  => boolval($patient),
        ]);

        $form->add('submit', SubmitType::class, ['label' => 'Enregistrer']);

        return $form;
    }

    /**
     * Deletes a patient entity.
     *
     */
    public function deleteAction(Request $request, Patient $patient)
    {
        $form = $this->createDeleteForm($patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($patient);
            $em->flush();

            $redirect =  $this->generateUrl('admin_gestion_patient_index');

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


        return $this->render('patient/delete.html.twig', [
            'patient' => $patient,
            'form'    => $form->createView(),
        ]);
    }
    /**
     * Creates a form to delete a patient entity.
     *
     * @param Patient $patient The patient entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Patient $patient)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_gestion_patient_delete', ['id' => $patient->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @param Request $request
     * @param Patient $patient
     */
    public function preferenceAction(Request $request, Patient $patient)
    {
    }


    public function importAction(Request $request)
    {
        $em     = $this->getDoctrine()->getManager();
        $repPatient    = $em->getRepository(Patient::class);
        $corporate = $em->getRepository(Corporate::class)->findOneBy(['raisonSociale' => 'OIF']);
        $pays = $em->getRepository(Pays::class)->findOneByNom('Côte d\'Ivoire');
        $repVille = $em->getRepository(Ville::class);
        $errors = [];
        $form   = $this->createForm(ImportType::class);
        $groupeSanguins = ['A+' => 'AP', 'O+' => 'OP', 'AB+' => 'ABP', 'ABN' => 'AB-', 'ON' => 'O-', 'AN' => 'A-'];

        $liens = [];

        $formatDate = function ($date) {
            try {
                $dateObj = new \DateTime($date);
            } catch (\Exception $e) {
                $dateObj = \DateTime::createFromFormat('d-m-y', $date);
            } catch (\Exception $e) {
                $dateObj = new \DateTime();
            }
            return $dateObj ? $dateObj : new \DateTime();
        };

        $form->handleRequest($request);
        $import = 0;
        $total  = 0;
        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $fichier = $this->get('app.psm_logo_uploader')
                    ->upload($form->get('file')->getData(), null, $path, 'patient_psm');

                $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

                $spreadsheet = $this->get('phpoffice.spreadsheet');
                if ($extension == 'xlsx') {
                    $reader = $spreadsheet->createReader('Xlsx');
                } else {
                    $reader = $spreadsheet->createReader('Csv');
                }

                $loader = $reader->load($path);

                $count = $loader->getSheetCount();

                for ($i = 0; $i < $count; $i++) {

                    $loader->setActiveSheetIndex($i);
                    $activeSheet =  $loader->getActiveSheet();
                    if (strpos($activeSheet->getTitle(), '@') === false) {
                        continue;
                    }
                    [$dateInscription, $ville] = explode('@', $activeSheet->getTitle());
                    $dateInscription = str_replace('_', '-', $dateInscription);


                    $data = array_slice($activeSheet->toArray(), 1);



                    foreach ($data as $row) {
                        $total += 1;
                        $row = array_map('trim', $row);
                        $identifiant = str_pad($row[0], 5, '0', STR_PAD_LEFT);
                        $pin = str_pad($row[1], 5, '0', STR_PAD_LEFT);
                        $nomPrenoms = $row[2];
                        $codeGroupeSanguin = $row[9] ?? '';


                        /*$patient = $repPatient->findOneBy(compact('identifiant', 'pin'));
                        if ($patient) {
                            if ($codeGroupeSanguin && isset($groupeSanguins[$codeGroupeSanguin])) {
                                $groupeSanguin = $em->getRepository(GroupeSanguin::class)->findOneByCode($groupeSanguins[$codeGroupeSanguin]);
                                if ($groupeSanguin) {
                                    $patient->setGroupeSanguin($groupeSanguin);
                                }
                            }


                            $em->persist($patient);
                            $em->flush();
                            $import++;
                        }*/


                        if ($identifiant && $pin && $nomPrenoms && !$repPatient->findOneBy(compact('identifiant', 'pin'))) {


                            $contact = str_replace('O', 0, $row[3]);
                            //$sexe = $row[4];
                            $lsVaccins = $row[5];
                            $associes = $row[6];
                            //$telephone = $row[7];
                            $lieuhabitation = $row[8];
                            $codeGroupeSanguin = $row[9] ?? '';
                            $affection = $row[10] ?? '';
                            $allergie = $row[11] ?? '';


                            $patient = new Patient();
                            $patient->setIdentifiant($identifiant);
                            $patient->setPin($pin);
                            $patient->setLieuhabitation($lieuhabitation);
                            $patient->setSexe(strtoupper($row[4]));
                            $patient->setPays($pays);

                            if ($codeGroupeSanguin && isset($groupeSanguins[$codeGroupeSanguin])) {
                                $groupeSanguin = $em->getRepository(GroupeSanguin::class)->findOneByCode($groupeSanguins[$codeGroupeSanguin]);
                                if ($groupeSanguin) {
                                    $patient->setGroupeSanguin($groupeSanguin);
                                }
                            }

                            if (strpos($ville, '_') == mb_strlen($ville) - 1) {
                                $ville = substr_replace($ville, '', strrpos($ville, '_'));
                            }


                            if (!$_ville = $repVille->findOneByNom($ville)) {
                                $_ville = new Ville();
                                $_ville->setPays($pays);
                                $_ville->setNom($ville);
                            }

                            $patient->setVille($_ville);






                            preg_match('#(.+)\(([^)]+)\)#', $nomPrenoms, $matches);
                            if (count($matches) == 3) {
                                $nom = trim($matches[1]);
                                $age = trim($matches[2]);
                                if (stripos($age, 'an') > 0 || is_numeric($age)) {
                                    $age = intval($age);
                                    if ($age > 100) {
                                        $age = 0;
                                    }
                                    $dateNaissance = (new \DateTime())->modify("-{$age} year");
                                } else {
                                    if (strlen($age) < 10) {
                                    }
                                    $dateNaissance = $formatDate($age);
                                }
                            } else {
                                $nom = trim($nomPrenoms);
                                $dateNaissance = new \DateTime();
                            }

                            $noms = explode(' ', $nom);



                            $personne = new Personne();
                            $personne->setDatenaissance($dateNaissance);
                            $personne->setDateInscription(new \DateTime($dateInscription));
                            $personne->setContact($contact);
                            $personne->setCorporate($corporate);
                            $personne->setNom($noms[0]);
                            $personne->setPrenom($noms[1] ?? '');

                            $patient->setPersonne($personne);


                            $partTelephones = explode('_', $row[7]);
                            $cParts = count($partTelephones);
                            $contacts = [];
                            //$parts = explode('_', $_r);

                            if ($cParts == 1) {
                                $contacts = ['nom' => $partTelephones[0], 'lien' => 'N/A', 'numero' => $contact];
                            } elseif ($cParts == 2) {
                                $parts1 = $partTelephones[0];
                                $parts2 = ucfirst(mb_strtolower($partTelephones[1]));
                                if (in_array($parts2, Telephone::CHOICES)) {
                                    $contacts = ['nom' => $parts1, 'lien' => $parts2, 'numero' => $contact];
                                } else {
                                    $contacts = ['nom' => $parts1, 'lien' => 'N/A', 'numero' => $parts2];
                                }
                            } else if ($cParts == 3) {
                                $parts1 = $partTelephones[0];
                                $parts2 = ucfirst(mb_strtolower($partTelephones[1]));
                                $parts3 = $partTelephones[2];

                                if (in_array($parts2, $liens)) {
                                    $contacts = ['nom' => $parts1, 'lien' => $parts2, 'numero' => $parts3];
                                } else {
                                    $contacts = ['nom' => $parts1, 'lien' => $parts3, 'numero' => $parts2];
                                }
                            }

                            if (!empty($contacts['numero']) && !empty($contacts['nom'])) {
                                $_telephone = new Telephone();
                                $_telephone->setLien($contacts['lien']);
                                $_telephone->setNumero($contacts['numero']);
                                $_telephone->setNom($contacts['nom']);
                                $patient->addTelephone($_telephone);
                            }

                            if ($allergie) {
                                $_allergie = new PatientAllergies();
                                $_allergie->setAllergie($allergie);
                                $_allergie->setCommentaire('RAS');
                                $patient->addAllergy($_allergie);
                            }


                            if ($affection) {
                                $_affection = new PatientAffections();
                                $_affection->setAffection($affection);
                                $_affection->setCommentaire('RAS');
                                $_affection->setVisible(true);
                                $patient->addAffection($_affection);
                            }


                            $vaccins = [];

                            if ($lsVaccins) {
                                if (substr_count($lsVaccins, ':') <= 2) {
                                    $partVaccins = explode(':', $lsVaccins, 2);
                                    if (count($partVaccins) == 2) {
                                        [$L_vaccins, $_dates] = $partVaccins;
                                    } else {
                                        $L_vaccins = $lsVaccins;
                                        $_dates = $dateInscription;
                                    }
                                    $parts = explode(':', $_dates);
                                    foreach (explode('_', $L_vaccins) as $vaccin) {
                                        $vaccins[] = ['vaccin' => $vaccin, 'date' => $parts[0], 'rappel' => $parts[1] ?? null];
                                    }
                                } else {
                                    preg_match_all(
                                        '/((?P<VACCIN>[\p{L}\p{P}\s]+):(?P<DATE>\d{2}[\/-]\d{2}[\/-]\d{2,})(:(?P<RAPPEL>\d{2}[\/-]\d{2}[\/-]\d{2,}))?_?)/',
                                        $lsVaccins,
                                        $_matches,
                                        PREG_SET_ORDER
                                    );

                                    foreach ($_matches as $_vaccins) {
                                        foreach ($_vaccins as $_vaccin) {
                                            if (isset($_vaccin['VACCIN'], $_vaccin['DATE'])) {
                                                $vaccins[] = ['vaccin' => $_vaccin['VACCIN'], 'date' => $_vaccin['DATE'], 'rappel' => $_vaccin['RAPPEL'] ?? null];
                                            }
                                        }
                                    }
                                }
                            }



                            foreach ($vaccins as $vaccin) {

                                if ($vaccin['vaccin']) {
                                    $patientVaccin = new PatientVaccin();
                                    $patientVaccin->setVaccin($vaccin['vaccin']);

                                    $date = str_replace('/', '-', $vaccin['date']);
                                    $patientVaccin->setDate($formatDate($date));
                                    if ($vaccin['rappel']) {
                                        $rappel = str_replace('/', '-', $vaccin['rappel']);
                                        $patientVaccin->setRappel($formatDate($rappel));
                                    }

                                    $patient->addVaccination($patientVaccin);
                                }
                            }

                            $vaccins = [];



                            $em->persist($patient);
                            $em->flush();
                            $import++;
                        }
                    }
                }



                if ($import > 0) {
                    $this->addFlash('success', $import . '/' . $total . ' patients insérés avec succès');
                } else {
                    $this->addFlash('error', 'Aucune ligne exportée');
                }
                return $this->redirectToRoute('admin_gestion_patient_import');
            } else {
                $errors = $this->get('app.form_error')->all($form);
            }
        }

        return $this->render('patient/import.html.twig', [

            'errors' => $errors,
            'form'   => $form->createView(),
        ]);
    }



    /**
     * @param Request $request
     * @param Patient $patient
     * @return mixed
     */
    public function associeAction(Request $request, Patient $patient = null)
    {
        $session  = $request->getSession();
        $em       = $this->getDoctrine()->getManager();
        $patient  = $em->getRepository(Patient::class)->find($session->get('patient'));
        $associes = $patient->getAssociesIncluded();

        $url = $session->get('patient_url_action');

        $_patient = $request->query->get('associe') ? $em->getRepository(Patient::class)->find($request->query->get('associe')) : null;

        if ($_patient && ($_patient->isChildOf($patient) || ($_patient == $patient))) {
            $session->set('patient', $_patient->getId());

            return $this->redirect($url);
        }

        return $this->render(
            'patient/associe.html.twig',
            [
                'associes' => $associes,
                'patient'  => $patient,

            ]
        );
    }

    public function photoAction(Request $request)
    {
        $_currentCorporate = $this->getUser()->getCorporate();
        $em = $this->getDoctrine()->getManager();
        $image = new Image();
        $formData = $request->query->get('form', []);
        $mode = $formData && !empty($formData['mode']) ? $formData['mode'] : $request->query->get('mode');
        $view = $formData && !empty($formData['filter']) ? $formData['filter'] : $request->query->get('filter');
        $hasFilter = false;
        $errors = [];
        $patients = [];
        if ($mode) {

            $_formB = $this->createFormBuilder(null, [
                'method' => 'GET',
                'action' => $this->generateUrl('admin_gestion_patient_photo', ['mode' => 'list'])
            ]);

            $_corporate = null;

            if (!$this->isGranted('ROLE_ADMIN_CORPORATE')) {
                $_formB->add('corporate', EntityType::class, ['required' => false, 'placeholder' => '--', 'class' => Corporate::class, 'choice_label' => 'raisonSociale', 'label' => 'Corporate']);
            } else {
                $_corporate = $_currentCorporate;
            }


            $_formB->add('matricule', null, ['label' => 'Rechercher par matricule', 'required' => false,])
                ->add('nom', null, ['label' => 'Rechercher par nom/prénom', 'required' => false,])
                ->add('mode', HiddenType::class, ['data' => $request->query->get('mode')]);

            $_form = $_formB->getForm();
            $_form->handleRequest($request);

            $corporate = $_corporate ?: $_form->get('corporate')->getData();
            $matricule = $_form->get('matricule')->getData();
            $nom = $_form->get('nom')->getData();

            $hasFilter = $corporate || $matricule || $nom;

            if ($hasFilter) {
                $query = $em->getRepository(Patient::class)->photos(compact('corporate', 'matricule', 'nom'));
                $paginator = $this->get('knp_paginator');
                $patients      = $paginator->paginate(
                    $query,
                    $request->query->getInt('page', 1),
                    50
                );
            }
        }

        $form  = $this->createForm(PhotoType::class, $image, [
            'csrf_protection' => true, 'action' => $this->generateUrl('admin_gestion_patient_photo', ['filter' => $view]), 'patient' => true, 'view' => $view, 'validation_groups' => $view && $view == 'nom' ? ['Default', 'Nom'] : ['Default', 'Matricule']
        ]);

        if ($this->isGranted('ROLE_ADMIN_CORPORATE')) {
            $corporate = $_currentCorporate;
            $form->remove('corporate');
        } else {
            $corporate = null;
        }

        $form->handleRequest($request);


        if ($form->isSubmitted()) {

            $patientRep = $em->getRepository(Patient::class);

            if ($form->isValid()) {

                $file =  $form->get('file')->getData();
                $total = 0;

                if ($view == 'nom') {
                    $nom = $form->get('nom')->getData();
                    $corporate = $form->has('corporate') ? $form->get('corporate')->getData() : $corporate;
                    $data = $patientRep->findByNomPrenom($nom, $corporate);
                    $total = count($data);
                    if ($total == 1) {
                        $patient = $data[0];
                    } else {
                        $data = $patientRep->findByLikeNomPrenom($nom, $corporate);
                        $total = count($data);
                        if ($total == 1) {
                            $patient = $data[0];
                        } else {
                            $patient = null;
                        }
                    }
                } else {
                    $matricule = $form->get('matricule')->getData();


                    $patient = $patientRep->findOneBy(compact('matricule'));

                    if (!$patient) {
                        $patient = $patientRep->findOneBy(['identifiant' => $matricule]);
                    }
                }

                if ($patient && $file) {


                    $personne = $patient->getPersonne();

                    $image->setFile($file);



                    $em->persist($image);


                    $personne->setPhoto($image);

                    $em->persist($personne);

                    $em->flush();

                    $this->addFlash('success', 'La photo de ' . $personne->getNomComplet() . ' , ID ' . $patient->getIdentifiant() . ' a été ajoutée avec succès');

                    return $this->redirectToRoute('admin_gestion_patient_photo');
                } else {
                    if ($total == 0) {
                        $form->addError(new FormError('Patient inconnu de notre Base de Données'));
                    } else if ($total > 1) {
                        $message = 'Nous avons trouvé plusieurs patients avec ce nom et prénom<br>';
                        foreach ($data as $_patient) {
                            $message .= $_patient->getPersonne()->getNomComplet() . ', ID = ' . $_patient->getIdentifiant() . '<br>';
                        }
                        $message .= 'Pour plus de sécurité, veuillez utiliser l\'ajout par matricule ou par ID';
                        $errors[] = $message;
                        //$form->addError(new FormError($message));
                    }
                }



                /*$identifiant = $form->get('identifiant')->getData();
                $pin = $form->get('pin')->getData();*/
            }
        }



        return $this->render(
            'GestionBundle:Patient:photo.html.twig',
            [
                'form' => $form->createView(),
                '_form' => isset($_form) ? $_form->createView() : null,
                'mode' => $request->query->get('mode'),
                'view' => $request->query->get('filter'),
                'has_filter' => $hasFilter,
                'patients' => $patients,
                'errors' => $errors
            ]
        );
    }

    public function printBadgeAction(Request $request, Patient $patient)
    {
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];


        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'orientation' => 'P',
            'fontDir' => array_merge($fontDirs, [$this->getParameter('bundle_dir') . '/public/fonts/montserrat/']),
            'fontdata' => $fontData + [
                'Montserrat' => [
                    'B' => 'Montserrat-Bold.ttf',
                    'R' => 'Montserrat-Regular.ttf',
                    'L' => 'Montserrat-Light.ttf',
                ],
            ],
            'default_font' => 'Montserrat',
            'img_dpi' => 300,
            'dpi' => 300,
            'format' => [85, 55],
        ]);


        $mpdf->shrink_tables_to_fit = 1;

        $vars = [
            'patient' => $patient
        ];

        $template = 'patient/badge.html.twig';

        $mpdf->WriteHTML($this->renderView($template, $vars));

        $mpdf->Output('BADGE ' . $patient->getIdentifiant() . '.pdf', 'I');

        return new Response();
    }

    public function resetPasswordAction(Request $request, Patient $patient)
    {

        $em = $this->getDoctrine()->getManager();

        if (!$patient) {
            $this->get('app.action_logger')
            ->add("Le patient n'existe pas dans la base de données.", $patient);
        }
        $this->checkAccess($patient);

        $email = $patient->getPersonne()->getUtilisateur()->getEmail();
        $patient->setEmail($email);

        $utilisateur = $this->resetUserPassword($patient);
        $em->persist($utilisateur);
        $em->flush();

        $this->get('app.action_logger')
            ->add('Réinitialisation du mot de passe du profil effectuée avec succès.', $patient);

        return $this->redirectToRoute('admin_gestion_patient_info', ['id' => $patient->getId()]);
    }

    /**
     * @param Patient $patient
     * @todo Fonction à deplacer un service
     */
    private function resetUserPassword(Patient $patient)
    {
        $smsMtarget  = $this->get('app.mtarget_sms');
        $util = $this->get('app.psm_util');

        $sender = 'COMPTE PSM';
        $authUser = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $personne    = $patient->getPersonne();
        $email    = $patient->getEmail();
        $contact = $personne->getSmsContact();
       
        $userManager = $this->get('fos_user.user_manager');
        $password = $util->random(8, ['alphabet' => true]);
        
        $nomComplet =  strtoupper($personne->getNomComplet());

        $utilisateur = $userManager->findUserBy(['personne' => $personne]);

        $utilisateur->setPlainPassword($password);
       
        if ($utilisateur && $personne->getSmsContact()) 
        {
            $username = $utilisateur->getUsername();

            $userManager->updateUser($utilisateur, false);

            $msg = sprintf(
                "%s\nVos accès au profil médical PPS viennent d'être réinitialisés. Vos nouvelles informations de connexion sont les suivantes:\nLogin: %s\nMot de Passe: %s\nhttps://passpostesante.ci/login",
                $nomComplet,
                $username,
                $password
            );

            $smsMtarget->sendSms($contact, $msg, $sender);
        }

        return $utilisateur;
    }
}
