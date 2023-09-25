<?php

namespace PS\GestionBundle\EventListener;

// ...

use Avanzu\AdminThemeBundle\Event\SidebarMenuEvent;
use PS\GestionBundle\Model\MenuItemModel;
use Symfony\Component\HttpFoundation\Request;

class MyMenuItemListListener
{

    // ...
    /**
     * @var mixed
     */
    protected $token;
    /**
     * @var mixed
     */
    protected $auth_checker;

    /**
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage $token
     * @param \Symfony\Component\Security\Core\Authorization\AuthorizationChecker $auth_checker
     */
    public function __construct(\Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage $token, \Symfony\Component\Security\Core\Authorization\AuthorizationChecker $auth_checker)
    {
        $this->token        = $token;
        $this->auth_checker = $auth_checker;
    } /**/

    /**
     * @param SidebarMenuEvent $event
     */
    public function onSetupMenu(SidebarMenuEvent $event)
    {

        $request = $event->getRequest();

        foreach ($this->getMenu($request) as $item) {
            $event->addItem($item);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    protected function getMenu(Request $request)
    {

        $menuItems = [];

        if ($this->auth_checker->isGranted('ROLE_SUPER_ADMIN')) {
            // Build your menu here by constructing a MenuItemModel array
            $menuItems = [
                $menu = new MenuItemModel('menu', 'menu.title', false, [/*options*/], 'iconclasses fa fa-users'),
                $logout = new MenuItemModel('logout', 'menu.logout', 'fos_user_security_logout', [], 'iconclasses fa fa-power-off'),
                //$blog = new MenuItemModel('ItemId', 'ItemDisplayName', 'item_symfony_route', array(/* options */), 'iconclasses fa fa-plane'),
                $dashboard = new MenuItemModel('dashboard', 'menu.dashboard', 'gestion_homepage', [/* options */], 'iconclasses fa fa-dashboard'),
                $gestion = new MenuItemModel('gestion', 'menu.gestion.title', false, [], 'iconclasses fa fa-th-list'),
                $parametre = new MenuItemModel('parametre', 'menu.parametre.title', false, [/* options */], 'iconclasses fa fa-cubes'),

                $configuration = new MenuItemModel('configuration', 'menu.configuration.title', false, [/* options */], 'iconclasses fa fa-cogs'),
                $statistique = new MenuItemModel('statistiques', 'menu.stats.title', false, [/* options */], 'iconclasses fa fa-bar-chart'),

                //$fiche = new MenuItemModel('fiche', 'Fiches', false, [], 'iconclasses fa fa-th-list')
            ];


            $gestion->addChild(new MenuItemModel('configCorporate', 'menu.gestion.corporate', 'admin_gestion_corporate_index', [], "iconclasses fa fa-users"));
            $gestion->addChild(new MenuItemModel('configPharmacie', 'menu.gestion.pharmacies', 'admin_pharmacie_index', [], "iconclasses fa fa-medkit"));
            $gestion->addChild(new MenuItemModel('configFaq', 'menu.gestion.faq', 'admin_gestion_faq_index', [], "iconclasses fa fa-question-circle"));
            $gestion->addChild(new MenuItemModel('configSite', 'Sites', 'admin_gestion_site_index', [], 'iconclasses fa fa-alt'));
            $gestion->addChild(new MenuItemModel('configSMS', 'menu.gestion.sms', 'admin_gestion_sms_index', [], 'ionclasses fa fa-phone'));

            $gestion->addChild(new MenuItemModel('configQ', 'Questionnaires', 'gestion_questionnairedepistage_index', [], 'ionclasses fa fa-phone'));
            //Parametrage
            $parametre->addChild(new MenuItemModel('paramAffection', 'menu.parametre.affections', 'admin_parametre_affection_index', [], "iconclasses fa fa-cube"))
                ->addChild(new MenuItemModel('paramAllergie', 'Allergies', 'admin_parametre_allergie_index', [], "iconclasses fa fa-steam"))
                ->addChild(new MenuItemModel('paramHopital', 'menu.parametre.hopitaux', 'admin_parametre_hopital_index', [], "iconclasses fa fa-hospital-o"))
                ->addChild(new MenuItemModel('paramGroupeSanguin', 'menu.parametre.groupesanguin', 'admin_parametre_groupesanguin_index', [], "iconclasses fa fa-code-fork"))
                ->addChild(new MenuItemModel('paramSpecialite', 'menu.parametre.specialites', 'admin_parametre_specialite_index', [], "iconclasses fa fa-user-md"))
                ->addChild(new MenuItemModel('paramAssurance', 'menu.parametre.assurances', 'admin_parametre_assurance_index', [], "iconclasses fa fa-briefcase"))
                ->addChild(new MenuItemModel('paramMedicament', 'Medicaments', 'admin_parametre_medicament_index', [], "iconclasses fa fa-briefcase"))
                ->addChild(new MenuItemModel('paramVaccin', 'Vaccins', 'admin_parametre_vaccin_index', [], "iconclasses fa fa-eyedropper"))
                ->addChild(new MenuItemModel('paramEtat', 'Etats', 'admin_parametre_etat_index', [], "iconclasses fa fa-eyedropper"))
                ->addChild(new MenuItemModel('paramRegion', 'menu.parametre.regions', 'admin_parametre_region_index', [], "iconclasses fa fa-briefcase"))
                ->addChild(new MenuItemModel('paramVille', 'menu.parametre.villes', 'admin_parametre_ville_index', [], "iconclasses fa fa-eyedropper"))
                ->addChild(new MenuItemModel('paramAttr', 'Attributs', 'admin_parametre_attribut_index', [], "iconclasses fa fa-eyedropper"))
                ->addChild(new MenuItemModel('paramReligion', 'menu.parametre.religions', 'admin_parametre_religion_index', [], "iconclasses fa fa-eyedropper"))
                ->addChild(new MenuItemModel('paramPays', 'menu.parametre.pays', 'admin_parametre_pays_index', [], "iconclasses fa fa-eyedropper"))
                ->addChild(new MenuItemModel('paramNationalite', 'menu.parametre.nationalites', 'admin_parametre_nationalite_index', [], 'iconclasses fa fa-eyedropper'));
            //Statistiques
            $statistique->addChild(new MenuItemModel('statGlobales', 'menu.stats.global', 'admin_statistique_index', [], "iconclasses fa fa-pie-chart"));
            //Configuration
            $configuration->addChild(new MenuItemModel('configUtilisateur', 'menu.configuration.utilisateurs', 'admin_config_utilisateur_index', [], "iconclasses fa fa-user"))
                //->addChild(new MenuItemModel('configGroupe', 'Groupes utilisateurs', 'admin_parametre_allergie_index', array(), "iconclasses fa fa-group"))
                //->addChild(new MenuItemModel('configRole', 'Roles', 'admin_parametre_allergie_index', array(), "iconclasses fa fa-lightbulb-o"))
                ->addChild(new MenuItemModel('configPatient', 'menu.configuration.patients', 'admin_gestion_patient_index', [], "iconclasses fa fa-users"))
                ->addChild(new MenuItemModel('configMedecin', 'Médecins', 'admin_config_medecin_index', array(), "iconclasses fa fa-medkit"))

                /*->addChild(new MenuItemModel('configInfirmier', 'Infirmiers', 'admin_gestion_infirmier_index', array(), "iconclasses fa fa-users"))*/
                ->addChild(new MenuItemModel('configPass', 'menu.configuration.pass', 'admin_config_pass_index', [], "iconclasses fa fa-sticky-note-o"));
            //Consultation

            //Pharmacie

            //->addChild(new MenuItemModel('pharmacieItem4', 'Pharmacies de garde', 'admin_pharmacie_garde', ['mode' => 'view'], 'iconclasses fa fa-medkit'));
            //Patient




        } elseif ($this->auth_checker->isGranted('ROLE_ADMIN_CORPORATE') || $this->auth_checker->isGranted('ROLE_ADMIN_LOCAL')) {
            $isSuperAdmin = $this->auth_checker->isGranted('ROLE_ADMIN_CORPORATE');
            $isAssuranceAdmin = $this->auth_checker->isGranted('ROLE_LOCAL_ASSURANCE', $this->token->getToken()->getUser());

            $menuItems    = [
                $menu = new MenuItemModel('menu', 'menu.title', false, [/*options*/], 'iconclasses fa fa-users'),
                $logout = new MenuItemModel('logout', 'menu.logout', 'fos_user_security_logout', [], 'iconclasses fa fa-power-off'),
                //$blog = new MenuItemModel('ItemId', 'ItemDisplayName', 'item_symfony_route', array(/* options */), 'iconclasses fa fa-plane'),
                $dashboard = new MenuItemModel('dashboard', 'menu.dashboard', 'gestion_homepage', [/* options */], 'iconclasses fa fa-dashboard'),

                $parametre = new MenuItemModel('parametre', 'menu.parametre.title', false, [/* options */], 'iconclasses fa fa-cubes'),
                $configuration = new MenuItemModel('configuration', 'menu.configuration.title', false, [/* options */], 'iconclasses fa fa-cogs'),
                //$statistique = new MenuItemModel('statistiques', 'menu.stats.title', false, [/* options */], 'iconclasses fa fa-bar-chart'),

            ];

            if (!$isSuperAdmin) {
                // unset($menuItems[2]);
            }



            if ($isSuperAdmin) {
                $gestion     = new MenuItemModel('gestion', 'menu.gestion.title', false, [], 'iconclasses fa fa-th-list');
                $menuItems[] = $gestion;
                $_corporate  = $this->token->getToken()->getUser()->getPersonne()->getCorporate();
                $gestion->addChild(new MenuItemModel('configCorporate', 'menu.gestion.corporate', 'admin_gestion_corporate_index', ['id' => $_corporate->getId()], "iconclasses fa fa-users"));

                /*$fiche       = new MenuItemModel('fiche', 'Fiches', false, [], 'iconclasses fa fa-th-list');
                $menuItems[] = $fiche;

                $fiche
                    ->addChild(new MenuItemModel('patientEtape', 'Gestion des étapes', 'admin_specialite_etape_index'))
                    ->addChild(new MenuItemModel('patientChamp', 'Gestion des champs', 'admin_specialite_champ_index'))
                    ->addChild(new MenuItemModel('patientStats', 'Stats par spécialité', 'admin_specialite_stats_index'));*/
            }


            //Statistiques
            //$statistique->addChild(new MenuItemModel('statGlobales', 'menu.stats.global', 'admin_statistique_index', [], "iconclasses fa fa-pie-chart"));
            //Configuration
            $configuration->addChild(new MenuItemModel('configUtilisateur', 'menu.configuration.utilisateurs', 'admin_config_utilisateur_index', [], "iconclasses fa fa-user"))
                ->addChild(new MenuItemModel('configPatient', 'menu.configuration.patients', 'admin_gestion_patient_index', [], "iconclasses fa fa-users"));
            if (!$isAssuranceAdmin) {

                //->addChild(new MenuItemModel('configGroupe', 'Groupes utilisateurs', 'admin_parametre_allergie_index', array(), "iconclasses fa fa-group"))
                //->addChild(new MenuItemModel('configRole', 'Roles', 'admin_parametre_allergie_index', array(), "iconclasses fa fa-lightbulb-o"))
                /*$configuration->addChild(new MenuItemModel('configPatient', 'menu.configuration.patients', 'admin_gestion_patient_index', [], "iconclasses fa fa-users"));*/
            }

            $configuration->addChild(new MenuItemModel('configMedecin', 'Médecins', 'admin_config_medecin_index', [], "iconclasses fa fa-medkit"));

            /*if ($isSuperAdmin) {
                $configuration->addChild(new MenuItemModel('configPass', 'menu.configuration.pass', 'admin_config_pass_index', [], "iconclasses fa fa-sticky-note-o"));
            }*/

            $parametre->addChild(new MenuItemModel('paramHopital', 'menu.parametre.hopitaux', 'admin_parametre_hopital_index', [], "iconclasses fa fa-hospital-o"))

                /*->addChild(new MenuItemModel('paramSpecialite', 'menu.parametre.specialites', 'admin_parametre_specialite_index', [], "iconclasses fa fa-user-md"))

                ->addChild(new MenuItemModel('paramReligion', 'menu.parametre.religions', 'admin_parametre_religion_index', [], "iconclasses fa fa-eyedropper"))
                ->addChild(new MenuItemModel('paramPays', 'menu.parametre.pays', 'admin_parametre_pays_index', [], "iconclasses fa fa-eyedropper"))
                ->addChild(new MenuItemModel('paramNationalite', 'menu.parametre.nationalites', 'admin_parametre_nationalite_index', [], 'iconclasses fa fa-eyedropper'))*/;
        } elseif ($this->auth_checker->isGranted('ROLE_ADMIN')) {
            // Build your menu here by constructing a MenuItemModel array
            $menuItems = [
                $logout = new MenuItemModel('logout', 'menu.logout', 'fos_user_security_logout', [], 'iconclasses fa fa-power-off'),
                $menu = new MenuItemModel('menu', 'menu.title', false, [/*options*/], 'iconclasses fa fa-users'),
                $dashboard = new MenuItemModel('dashboard', 'menu.dashboard', 'gestion_homepage', [/* options */], 'iconclasses fa fa-dashboard'),
                new MenuItemModel('compte_psm', 'menu.user_account', 'utilisateur_admin_profile_utilisateur_show', [/* options */], 'iconclasses fa fa-edit'),
                $gestion = new MenuItemModel('gestion', 'menu.gestion.title', false, array(), 'iconclasses fa fa-th-list'),
                $parametre = new MenuItemModel('parametre', 'menu.parametre.title', false, array(), 'iconclasses fa fa-cubes'),
                // $consultation = new MenuItemModel('consultation', 'Consultation', false, [/* options */], 'iconclasses fa fa-users'),
                $patient = new MenuItemModel('menu.patient.title', 'menu.patient.title', false, [/* options */], 'iconclasses fa fa-user'),
                $configuration = new MenuItemModel('configuration', 'menu.configuration.title', false, [/* options */], 'iconclasses fa fa-cogs'),
                $statistique = new MenuItemModel('statistiques', 'menu.stats.title', false, [/* options */], 'iconclasses fa fa-bar-chart'),
                $pharmacie = new MenuItemModel('pharmacie', 'Pharmacie', false, [/* options */], 'iconclasses fa fa-medkit'),

                $rendezVous = new MenuItemModel('calendar', 'menu.patient.rdv', 'admin_gestion_rdv_index', [], 'iconclasses fa fa-calendar'),
            ];


            $gestion->addChild(new MenuItemModel('configSMS', 'menu.gestion.sms', 'admin_gestion_sms_index', [], 'ionclasses fa fa-phone'));

            $patient->addChild(new MenuItemModel('patientProfil', 'Informations', 'admin_gestion_patient_info', [], 'iconclasses fa fa-file'));
            $patient->addChild(new MenuItemModel('patientAssocie', 'menu.patient.associe', 'gestion_admin_patient_associe_index', [], 'iconclasses fa fa-file'));

            $gestion->addChild(new MenuItemModel('configCorporate', 'menu.gestion.corporate', 'admin_gestion_corporate_index', array(), "iconclasses fa fa-users"));
            // ->addChild(new MenuItemModel('configCommande', 'Commandes', 'admin_gestion_commande_index', array(), "iconclasses fa fa-sticky-note-o"));

            // $pharmacie->addChild(new MenuItemModel('pharmacieItem1', 'menu.patient.ordonnances', 'admin_pharmacie_ordonnance', [/* options */], 'iconclasses fa fa-medkit'))
            // ->addChild(new MenuItemModel('pharmacieItem4', 'Pharmacies de garde', 'admin_pharmacie_garde', ['mode' => 'view'], 'iconclasses fa fa-medkit'));

            //Parametrage
            $parametre->addChild(new MenuItemModel('paramAffection', 'menu.parametre.affections', 'admin_parametre_affection_index', array(), "iconclasses fa fa-cube"))
                ->addChild(new MenuItemModel('paramAllergie', 'Allergies', 'admin_parametre_allergie_index', array(), "iconclasses fa fa-steam"))
                ->addChild(new MenuItemModel('paramHopital', 'menu.parametre.hopitaux', 'admin_parametre_hopital_index', array(), "iconclasses fa fa-hospital-o"))
                ->addChild(new MenuItemModel('paramGroupeSanguin', 'menu.parametre.groupesanguin', 'admin_parametre_groupesanguin_index', array(), "iconclasses fa fa-code-fork"))
                ->addChild(new MenuItemModel('paramSpecialite', 'menu.parametre.specialites', 'admin_parametre_specialite_index', array(), "iconclasses fa fa-user-md"))
                ->addChild(new MenuItemModel('paramAssurance', 'menu.parametre.assurances', 'admin_parametre_assurance_index', array(), "iconclasses fa fa-briefcase"))
                ->addChild(new MenuItemModel('paramMedicament', 'Medicaments', 'admin_parametre_medicament_index', array(), "iconclasses fa fa-briefcase"))
                ->addChild(new MenuItemModel('paramVaccin', 'Vaccins', 'admin_parametre_vaccin_index', array(), "iconclasses fa fa-eyedropper"))
                ->addChild(new MenuItemModel('paramRegion', 'menu.parametre.regions', 'admin_parametre_region_index', array(), "iconclasses fa fa-briefcase"))
                ->addChild(new MenuItemModel('paramVille', 'menu.parametre.villes', 'admin_parametre_ville_index', array(), "iconclasses fa fa-eyedropper"))
                ->addChild(new MenuItemModel('paramAttr', 'Attributs', 'admin_parametre_attribut_index', array(), "iconclasses fa fa-eyedropper"))
                ->addChild(new MenuItemModel('paramEtat', 'Etats', 'admin_parametre_etat_index', [], "iconclasses fa fa-eyedropper"))
                ->addChild(new MenuItemModel('paramPays', 'menu.parametre.pays', 'admin_parametre_pays_index', array(), "iconclasses fa fa-eyedropper"));

            // $consultation->addChild(new MenuItemModel('consultationItem1', 'Votre historique', 'admin_consultation_historique', [/* options */], 'iconclasses fa fa-folder'));
            //Statistiques
            $statistique->addChild(new MenuItemModel('statGlobales', 'menu.stats.global', 'admin_statistique_index', [], "iconclasses fa fa-charts"));
            //Configuration
            $configuration->addChild(new MenuItemModel('configUtilisateur', 'menu.configuration.utilisateurs', 'admin_config_utilisateur_index', [], "iconclasses fa fa-user"))
                ->addChild(new MenuItemModel('configPatient', 'menu.configuration.patients', 'admin_gestion_patient_index', [], "iconclasses fa fa-users"))
                ->addChild(new MenuItemModel('configMedecin', 'Médecins', 'admin_config_medecin_index', [], "iconclasses fa fa-users"))
                ->addChild(new MenuItemModel('configInfirmier', 'Infirmiers', 'admin_gestion_infirmier_index', array(), "iconclasses fa fa-users"))
                ->addChild(new MenuItemModel('configPass', 'menu.configuration.pass', 'admin_config_pass_index', [], "iconclasses fa fa-sticky-note-o"));
        } elseif ($this->auth_checker->isGranted('ROLE_MEDECIN')) {
            //$user = $this->token->getToken()->getUser();

            // Build your menu here by constructing a MenuItemModel array
            $menuItems = [
                $menu = new MenuItemModel('menu', 'menu.title', false, [/*options*/], 'iconclasses fa fa-users'),
                $logout = new MenuItemModel('logout', 'menu.logout', 'fos_user_security_logout', [], 'iconclasses fa fa-power-off'),
                $dashboard = new MenuItemModel('dashboard', 'menu.dashboard', 'gestion_homepage', [/* options */], 'iconclasses fa fa-dashboard'),
                $consultation = new MenuItemModel('consultation', 'Consultation', false, [/* options */], 'iconclasses fa fa-users'),
                $rendezVous = new MenuItemModel('calendar', 'menu.patient.rdv', 'admin_gestion_rdv_index', [], 'iconclasses fa fa-calendar'),
            ];

            //dump($this->token->getToken()->getUser()->getUtilisateurAssurance()[0]->getAssurance());exit;


            if ($this->auth_checker->isGranted('ROLE_MEDECIN_CONSEIL', $this->token->getToken()->getUser())) {
                unset($menuItems[2], $menuItems[3]);
            } else {

                $consultation->addChild(new MenuItemModel('consultationItem1', 'Votre historique', 'admin_consultation_historique', [/* options */], 'iconclasses fa fa-folder'))
                    ->addChild(new MenuItemModel('consultationItem2', 'Consultation', 'admin_consultation_search', [/* options */], 'iconclasses fa fa-folder-open'))
                    /*->addChild(new MenuItemModel('consultationItem3', 'CPN', 'gestion_fiche_search', [], 'iconclasses fa fa-folder-open'))
        ->addChild(new MenuItemModel('consultationItem5', 'Vaccins', 'gestion_vaccination_search', [], 'iconclasses fa fa-folder-open'))
             ->addChild(new MenuItemModel('consultationItem4', 'Fiche DREPANO', 'gestion_ficheaffection_search', ], 'iconclasses fa fa-folder-open'))*/
                    ->addChild(new MenuItemModel('consultationItem4', 'Suivi Patient', 'gestion_suivi_search', [/* options */], 'iconclasses fa fa-folder-open'));
            }
            //Consultation

            //->addChild(new MenuItemModel('patientFiche', 'Gestion des fiches', 'admin_specialite_fiche_search'));

        } elseif ($this->auth_checker->isGranted('ROLE_INFIRMIER')) {
            $menuItems = [
                $menu = new MenuItemModel('menu', 'menu.title', false, [/*options*/], 'iconclasses fa fa-users'),
                $logout = new MenuItemModel('logout', 'menu.logout', 'fos_user_security_logout', [], 'iconclasses fa fa-power-off'),
                $dashboard = new MenuItemModel('dashboard', 'menu.dashboard', 'gestion_homepage', [/* options */], 'iconclasses fa fa-dashboard'),
                new MenuItemModel('admission', 'menu.admission.title', 'gestion_admission_index', [], 'iconclasses fa fa-circle'),
                $utilisateur = new MenuItemModel('user', 'menu.configuration.patients', 'admin_gestion_patient_index', [], 'iconclasses fa fa-users'),
                $patient = new MenuItemModel('menu.patient.title', 'Prise de constantes', 'admin_gestion_infirmier_patient', [/* options */], 'iconclasses fa fa-th-list'),
                $patient = new MenuItemModel('historique', 'Mon historique', 'admin_gestion_infirmier_historique', [/* options */], 'iconclasses fa fa-edit'),
                $rdv = new MenuItemModel('rdv', 'menu.rdv.title', 'admin_gestion_rdv_index', [], 'iconclasses fa fa-calendar'),

            ];
        } elseif ($this->auth_checker->isGranted('ROLE_PHARMACIE')) {
            // Build your menu here by constructing a MenuItemModel array
            $menuItems = [
                $menu = new MenuItemModel('menu', 'menu.title', false, [/*options*/], 'iconclasses fa fa-users'),
                $logout = new MenuItemModel('logout', 'menu.logout', 'fos_user_security_logout', [], 'iconclasses fa fa-power-off'),
                $dashboard = new MenuItemModel('dashboard', 'menu.dashboard', 'gestion_homepage', [/* options */], 'iconclasses fa fa-dashboard'),
                $pharmacie = new MenuItemModel('pharmacie', 'Pharmacie', false, [/* options */], 'iconclasses fa fa-medkit'),

            ];

            $_pharmacie = $this->token->getToken()->getUser()->getPharmacie();
            //Pharmacie
            $pharmacie->addChild(new MenuItemModel('pharmacieItem1', 'Votre historique', 'admin_pharmacie_historique', array(), 'iconclasses fa fa-medkit'))
                ->addChild(new MenuItemModel('pharmacieItem2', 'Servir', 'admin_pharmacie_search', [], 'iconclasses fa fa-hand-stop-o'))
            ->addChild(new MenuItemModel('pharmacieItem3', 'Médicaments', 'admin_pharmacie_liste', [], 'iconclasses fa fa-medkit'));
            if ($_pharmacie) {

                $pharmacie->addChild(new MenuItemModel('pharmacieItem4', 'Info', 'admin_pharmacie_edit', ['id' => $_pharmacie->getId()], 'iconclasses fa fa-file-o'));
            }
        } elseif ($this->auth_checker->isGranted('ROLE_ADMIN_SUP')) {
            $menuItems = [
                $logout = new MenuItemModel('logout', 'menu.logout', 'fos_user_security_logout', [], 'iconclasses fa fa-power-off'),
                $menu = new MenuItemModel('menu', 'menu.title', false, [/*options*/], 'iconclasses fa fa-users'),
                $dashboard = new MenuItemModel('dashboard', 'menu.dashboard', 'gestion_homepage', [/* options */], 'iconclasses fa fa-dashboard'),
                new MenuItemModel('compte_psm', 'menu.user_account', 'utilisateur_admin_profile_utilisateur_show', [/* options */], 'iconclasses fa fa-edit'),
                new MenuItemModel('menu', 'menu.title', false, [/*options*/], 'iconclasses fa fa-users'),
                    $logout = new MenuItemModel('logout', 'menu.logout', 'fos_user_security_logout', [], 'iconclasses fa fa-power-off'),
                    //$blog = new MenuItemModel('ItemId', 'ItemDisplayName', 'item_symfony_route', array(/* options */), 'iconclasses fa fa-plane'),
                    $dashboard = new MenuItemModel('dashboard', 'menu.dashboard', 'gestion_homepage', [/* options */], 'iconclasses fa fa-dashboard'),
                    $gestion = new MenuItemModel('gestion', 'menu.gestion.title', false, [], 'iconclasses fa fa-th-list'),
                    $parametre = new MenuItemModel('parametre', 'menu.parametre.title', false, [/* options */], 'iconclasses fa fa-cubes'),


                $configuration = new MenuItemModel('configuration', 'menu.configuration.title', false, [/* options */], 'iconclasses fa fa-cogs'),


            ];


            $gestion->addChild(new MenuItemModel('configCorporate', 'menu.gestion.corporate', 'admin_gestion_corporate_index', [], "iconclasses fa fa-users"));
            $gestion->addChild(new MenuItemModel('configPharmacie', 'menu.gestion.pharmacies', 'admin_pharmacie_index', [], "iconclasses fa fa-medkit"));
            $gestion->addChild(new MenuItemModel('configSMS', 'menu.gestion.sms', 'admin_gestion_sms_index', [], 'ionclasses fa fa-phone'));
            // $gestion->addChild(new MenuItemModel('configFAQ', 'menu.gestion.faq', 'admin_gestion_faq_index'));
            // $gestion->addChild(new MenuItemModel('configFicheDep', 'Fiches dépistages cancer', 'gestion_fichedepistagecancer_index_index'));
            //Configuration
            $configuration
                ->addChild(new MenuItemModel('configUtilisateur', 'menu.configuration.utilisateurs', 'admin_config_utilisateur_index', [], "iconclasses fa fa-user"))
                ->addChild(new MenuItemModel('configGroupe', 'Groupes utilisateurs', 'admin_parametre_allergie_index', array(), "iconclasses fa fa-group"))
                ->addChild(new MenuItemModel('configRole', 'Roles', 'admin_parametre_allergie_index', array(), "iconclasses fa fa-lightbulb-o"))
                ->addChild(new MenuItemModel('configPatient', 'menu.configuration.patients', 'admin_gestion_patient_index', [], "iconclasses fa fa-users"))
                ->addChild(new MenuItemModel('configPhoto', 'menu.configuration.photo', 'admin_gestion_patient_photo', [], "iconclasses fa fa-users"))
                ->addChild(new MenuItemModel('configMedecin', 'Médecins', 'admin_config_medecin_index', array(), "iconclasses fa fa-medkit"))
                ->addChild(new MenuItemModel('configInfirmier', 'Infirmiers', 'admin_gestion_infirmier_index', array(), "iconclasses fa fa-users"))
                ->addChild(new MenuItemModel('configPass', 'menu.configuration.pass', 'admin_config_pass_index', [], "iconclasses fa fa-sticky-note-o"));


            //Parametrage
            $parametre->addChild(new MenuItemModel('paramAffection', 'menu.parametre.affections', 'admin_parametre_affection_index', [], "iconclasses fa fa-cube"))
                ->addChild(new MenuItemModel('paramAllergie', 'Allergies', 'admin_parametre_allergie_index', [], "iconclasses fa fa-steam"))
                ->addChild(new MenuItemModel('paramHopital', 'menu.parametre.hopitaux', 'admin_parametre_hopital_index', [], "iconclasses fa fa-hospital-o"))
                ->addChild(new MenuItemModel('paramGroupeSanguin', 'menu.parametre.groupesanguin', 'admin_parametre_groupesanguin_index', [], "iconclasses fa fa-code-fork"))
                ->addChild(new MenuItemModel('paramSpecialite', 'menu.parametre.specialites', 'admin_parametre_specialite_index', [], "iconclasses fa fa-user-md"))
                ->addChild(new MenuItemModel('paramAssurance', 'menu.parametre.assurances', 'admin_parametre_assurance_index', [], "iconclasses fa fa-briefcase"))
                ->addChild(new MenuItemModel('paramMedicament', 'Medicaments', 'admin_parametre_medicament_index', [], "iconclasses fa fa-briefcase"))
                ->addChild(new MenuItemModel('paramVaccin', 'Vaccins', 'admin_parametre_vaccin_index', [], "iconclasses fa fa-eyedropper"))
                ->addChild(new MenuItemModel('paramRegion', 'menu.parametre.regions', 'admin_parametre_region_index', [], "iconclasses fa fa-briefcase"))
                ->addChild(new MenuItemModel('paramVille', 'menu.parametre.villes', 'admin_parametre_ville_index', [], "iconclasses fa fa-eyedropper"))
                ->addChild(new MenuItemModel('paramAttr', 'Attributs', 'admin_parametre_attribut_index', [], "iconclasses fa fa-eyedropper"))

                ->addChild(new MenuItemModel('paramPays', 'menu.parametre.pays', 'admin_parametre_pays_index', [], "iconclasses fa fa-eyedropper"));
        } elseif ($this->auth_checker->isGranted('ROLE_RECEPTION')) {

            $menuItems = [
            $menu = new MenuItemModel('compte_psm', 'menu.user_account', 'utilisateur_admin_profile_utilisateur_show', [/* options */], 'iconclasses fa fa-edit'),
            new MenuItemModel('menu', 'menu.title', false, [/*options*/], 'iconclasses fa fa-users'),
                $logout = new MenuItemModel('logout', 'menu.logout', 'fos_user_security_logout', [], 'iconclasses fa fa-power-off'),
                $dashboard = new MenuItemModel('dashboard', 'menu.dashboard', 'gestion_homepage', [/* options */], 'iconclasses fa fa-dashboard'),


                $patient = new MenuItemModel('menu.patient.title', 'menu.patient.title', false, [/* options */], 'iconclasses fa fa-user'),

                $admission = new MenuItemModel('admission', 'menu.admission.title', 'gestion_admission_index', [], 'iconclasses fa fa-plus'),

                $rendezVous = new MenuItemModel('calendar', 'menu.rdv.title', 'admin_gestion_rdv_index', [], 'iconclasses fa fa-calendar'),
            ];


            $patient->addChild(new MenuItemModel('patientProfil', 'Nouveau', 'admin_gestion_patient_new', [], 'iconclasses fa fa-file'))

                ->addChild(new MenuItemModel('configPatient', 'menu.configuration.patients', 'admin_gestion_patient_index', [], "iconclasses fa fa-users"));
        } elseif ($this->auth_checker->isGranted('ROLE_CUSTOMER')) {
            $_patient = $this->token->getToken()->getUser()->getPatient();
            // Build your menu here by constructing a MenuItemModel array
            $menuItems = [
                $menu = new MenuItemModel('menu', 'menu.title', false, [/*options*/], 'iconclasses fa fa-users'),
                new MenuItemModel('logout', 'menu.logout', 'fos_user_security_logout', [], 'iconclasses fa fa-power-off'),
                new MenuItemModel('compte_psm', 'menu.user_account', 'utilisateur_admin_profile_utilisateur_show', [/* options */], 'iconclasses fa fa-edit'),
                $dashboard = new MenuItemModel('dashboard', 'menu.dashboard', 'gestion_homepage', [/* options */], 'iconclasses fa fa-dashboard'),
                $patient = new MenuItemModel('menu.patient.title', 'menu.patient.title', false, [/* options */], 'iconclasses fa fa-user'),
                new MenuItemModel('site_distrib', 'menu.site_distrib', 'site_distrib', [], 'iconclasses fa fa-file-o'),




                new MenuItemModel('configFaq', 'menu.gestion.faq', 'admin_gestion_faq_index', [], "iconclasses fa fa-question-circle"),

                /*new MenuItemModel('unregister', 'Suppression de compte', 'admin_config_utilisateur_unregister', [], 'icon fa fa-remove'),
                /*$consultation = new MenuItemModel('consultation', 'Consultation', false, [], 'iconclasses fa fa-users'),
                $pharmacie = new MenuItemModel('pharmacie', 'Pharmacie', false, [], 'iconclasses fa fa-medkit'),*/

            ];
            //Consultation

            //Pharmacie

            /*->addChild(new MenuItemModel('pharmacieItem3', 'Disponibilité Médicaments', 'admin_pharmacie_verif', [], 'iconclasses fa fa-search'))
                ->addChild(new MenuItemModel('pharmacieItem4', 'Pharmacies de garde', 'admin_pharmacie_garde', [], 'iconclasses fa fa-medkit'));*/
            //Patient
            $patient->addChild(new MenuItemModel('patientProfil', 'Informations', 'admin_gestion_patient_info', [], 'iconclasses fa fa-file'));
            if ($this->auth_checker->isGranted('ROLE_IS_PREMIUM')) {
                $patient->addChild(new MenuItemModel('patientProfil2', 'menu.patient.evolution', 'gestion_suivi_historique_patient', ['id' => $_patient->getId()], 'iconclasses fa fa-file'));

                $patient->addChild(new MenuItemModel('patientProfil4', 'menu.patient.constantes', 'gestion_patientconstante_index', ['id' => $_patient->getId()], 'iconclasses fa fa-file'));

                $patient->addChild(new MenuItemModel('patientAssocie', 'menu.patient.associe', 'gestion_admin_patient_associe_index', [], 'iconclasses fa fa-file'));
                $patient->addChild(new MenuItemModel('consultationItem1', 'menu.patient.historique_medical', 'admin_patient_historique', [/* options */], 'iconclasses fa fa-folder'));
                $patient->addChild(new MenuItemModel('calendar', 'menu.patient.rdv', 'admin_gestion_rdv_index', [], 'iconclasses fa fa-calendar'));
                $patient->addChild(new MenuItemModel('pharmacieItem1', 'menu.patient.ordonnances', 'admin_pharmacie_ordonnance', [/* options */], 'iconclasses fa fa-file'));
                }
            
        } elseif ($this->auth_checker->isGranted('ROLE_URGENTISTE')) {
            $menuItems = [
                $dashboard = new MenuItemModel('site_home', 'menu.site_home', 'homepage', [/* options */], 'iconclasses fa fa-dashboard'),
                new MenuItemModel('site_charge', 'Urgences', 'gestion_urgence_index', [])
            ];
        } else {
            $menuItems = [
                $dashboard = new MenuItemModel('site_home', 'menu.site_home', 'homepage', [/* options */], 'iconclasses fa fa-dashboard'),
            ];
        }

        // A child with default circle icon
        //$blog->addChild(new MenuItemModel('ChildTwoItemId', 'ChildTwoDisplayName', 'child_2_route'));
        return $this->activateByRoute($request->get('_route'), $menuItems);
    }

    /**
     * @param $route
     * @param $items
     * @return mixed
     */
    protected function activateByRoute($route, $items)
    {

        foreach ($items as $item) {
            if ($item->hasChildren()) {
                $this->activateByRoute($route, $item->getChildren());
            } else {
                if ($item->getRoute() == $route) {
                    $item->setIsActive(true);
                }
            }
        }

        return $items;
    }

    /**
     * @param $role
     */
    private function displayMenuByRole($role)
    {
        $role   = studly(str_replace('ROLE_', '', $role));
        $method = 'display' . $role;
        if (method_exists($this, $method)) {
            return call_user_func([$this, $method]);
        }
    }

    private function displayAdminCorporateMenu()
    {
    }

    private function displayCustomerMenu()
    {
    }

    private function displaySuperAdminMenu()
    {
    }

    public function displayAdminMenu()
    {
    }

    public function displayMedecinMenu()
    {
    }

    private function displayInfirmierMenu()
    {
    }
}
