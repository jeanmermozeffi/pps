<?php

namespace PS\GestionBundle\Controller;

use Ob\HighchartsBundle\Highcharts\Highchart;
use PS\GestionBundle\Entity\Corporate;
use PS\ParametreBundle\Entity\Pays;
use PS\ParametreBundle\Entity\Hopital;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class StatistiqueController extends Controller
{
    const FORMAT = '{point.name} ({point.y})';

    /**
     * @return mixed
     */
    public function corporate()
    {
        return $this->getUser()->getPersonne()->getCorporate();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        $user  = $this->getUser();
        $annee = $request->query->get('annee')/* ?: date('Y')*/;
        $em         = $this->getDoctrine()->getManager();

        $repHopital = $em->getRepository(Hopital::class);

        if ($user->hasRole('ROLE_ADMIN_CORPORATE')) {
            $hopitaux = $this->corporate()->getHopitaux();
        } else if ($user->hasRole('ROLE_ADMIN_LOCAL')) {
            $hopitaux = $repHopital->findBy(['id' => $user->getHopital()]);
        } else {
            $hopitaux = $repHopital->findAll();
        }

       
        $hopital  = $user->hasRole('ROLE_ADMIN_LOCAL') ? $user->getHopital()->getId() : $request->query->get('hopital');
        $annees = range(2016, date('Y'));

        array_unshift($annees, '');
       
        $corporates = $em->getRepository(Corporate::class)->findAll();
        $corporate  = $user->getPersonne()->getCorporate() ? $user->getPersonne()->getCorporate()->getId() : $request->query->get('corporate');

        $pays       = $em->getRepository(Pays::class)->findAll();
        $paysId     = $request->query->get('pays');

        
        $result     = $em->getRepository(Corporate::class)->totalLivraison($corporate);

        $viewData = compact('annee', 'corporate', 'corporates', 'annees', 'result', 'pays', 'paysId', 'hopitaux', 'hopital');

        return $this->render('GestionBundle:Stats:index.html.twig', $viewData);
    }

    /**
     * @param $corporate
     * @param null $pays
     * @param null $hopital
     * @return mixed
     */
    public function genreAction($corporate = null, $pays = null, $hopital = null, $annee = null)
    {
        $data    = [];
        $em      = $this->getDoctrine()->getManager();
        $message = "Genre";

        $sexe_table = $em->getRepository('GestionBundle:Patient')->statistiqueSexe($corporate, $pays, $hopital, $annee);

        foreach ($sexe_table as $k => $v):
            if (empty($v["sexe"])) {
                $v["sexe"] = 'NP';
            }

            if ($v["sexe"] == 'F') {
                $v["sexe"] = 'Femme';
            }

            if ($v["sexe"] == 'M') {
                $v["sexe"] = 'Homme';
            }

            $data[$k][] = $v['sexe'];
            $data[$k][] = $v['nbre'] * 1;
        endforeach;

        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('genre');
        $ob->title->text('Taux par genre');

        $ob->plotOptions->pie([
            'allowPointSelect' => true,
            'cursor'           => 'pointer',
            'dataLabels'       => [
                'enabled' => true
                , 'format' => self::FORMAT,
            ],
            'showInLegend'     => true,
        ]);

        $ob->series([['type' => 'pie', 'name' => 'Nombre', 'data' => $data]]);

        $objet["ob"]        = $ob;
        $objet["container"] = "genre";
        $objet["data"]        = $data;
        return $this->container->get('templating')->renderResponse('GestionBundle:Stats:box-stat.html.twig', ['objet' => $objet, 'message' => $message]);

    }

    /*public function paysAction($corporate=null, $pays=null, $hopital=null)
    {
    $data = [];
    $em = $this->getDoctrine()->getManager();
    $message = 'Pays';

    $query = $em->createQueryBuilder();
    $query->select('p3.nom as nom, COUNT(p.id) as nb')
    ->from('GestionBundle:Patient', 'p')
    ->join('p.personne', 'p2')
    ->join("p.pays", "p3");

    if ($corporate) {
    $query->andWhere('p2.corporate = :corporate');
    $query->setParameter('corporate', $corporate);
    }

    $query->groupBy('p3.nom');

    $nb = $query->getQuery()->getResult();

    foreach($nb as $k=>$v):
    $data[$k][] = $v['nom'];
    $data[$k][] = $v['nb'] * 1;
    endforeach;

    $ob = new Highchart();
    // ID de l'élement de DOM que vous utilisez comme conteneur
    $ob->chart->renderTo('pays');
    $ob->title->text('Taux par pays');

    $ob->plotOptions->pie(array(
    'allowPointSelect'  => true,
    'cursor'    => 'pointer',
    'dataLabels'    => array(
    'enabled' => true
    , 'format' => self::FORMAT
    ),
    'showInLegend'  => true
    ));

    $ob->series(array(array('type' => 'pie','name' => 'Nombre', 'data' => $data)));

    $objet["ob"] = $ob;
    $objet["container"] = "pays";
    return $this->container
    ->get('templating')
    ->renderResponse('GestionBundle:Stats:box-stat.html.twig', array('objet' => $objet,'message'=>$message));

    }*/

    /**
     * @param $corporate
     * @param null $pays
     * @param null $hopital
     * @return mixed
     */
    public function religionAction($corporate = null, $pays = null, $hopital = null, $annee = null)
    {
        $data    = [];
        $em      = $this->getDoctrine()->getManager();
        $message = 'Religion';

        $query = $em->createQueryBuilder();
        $query->select('r.nom as nom, COUNT(p.id) as nb')
            ->from('GestionBundle:Patient', 'p')
            ->join('p.personne', 'p2')
            ->join("p.religion", "r");

        if ($corporate) {
            $query->andWhere('p2.corporate = :corporate');
            $query->setParameter('corporate', $corporate);
        }

        if ($pays) {
            $query->andWhere('p.pays = :pays');
            $query->setParameter('pays', $pays);
        }

        if ($annee) {
            $query->andWhere('YEAR(p2.dateInscription) = :annee');
            $query->setParameter('annee', $annee);
        }

        /*if ($hopital) {
            $query->andwhere('h.hopital = :hopital');
            $params['hopital'] = $hopital;
        }*/

        $query->groupBy('r.nom');

        $nb = $query->getQuery()->getResult();

        foreach ($nb as $k => $v):
            $data[$k][] = $v['nom'];
            $data[$k][] = $v['nb'] * 1;
        endforeach;

        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('religion');
        $ob->title->text('Taux par réligion');

        $ob->plotOptions->pie([
            'allowPointSelect' => true,
            'cursor'           => 'pointer',
            'dataLabels'       => [
                'enabled' => true
                , 'format' => self::FORMAT,
            ],
            'showInLegend'     => true,
        ]);

        $ob->series([['type' => 'pie', 'name' => 'Nombre', 'data' => $data]]);

        $objet["ob"]        = $ob;
        $objet["container"] = "religion";
        $objet["data"]        = $data;
        return $this->container
            ->get('templating')
            ->renderResponse('GestionBundle:Stats:box-stat.html.twig', ['objet' => $objet, 'message' => $message]);

    }

    /**
     * @param $corporate
     * @param null $pays
     * @param null $hopital
     * @return mixed
     */
    public function groupeSanguinAction($corporate = null, $pays = null, $hopital = null, $annee = null)
    {
        $data    = [];
        $em      = $this->getDoctrine()->getManager();
        $message = 'Groupe Sanguin';

        $query = $em->createQueryBuilder();
        $query->select('g.code as code, COUNT(p.id) as nb')
            ->from('GestionBundle:Patient', 'p')
            ->join('p.personne', 'p2')
            ->join("p.groupeSanguin", "g");

        if ($corporate) {
            $query->andWhere('p2.corporate = :corporate');
            $query->setParameter('corporate', $corporate);
        }

        if ($pays) {
            $query->andWhere('p.pays = :pays');
            $query->setParameter('pays', $pays);
        }

        if ($annee) {
            $query->andWhere('YEAR(p2.dateInscription) = :annee');
            $query->setParameter('annee', $annee);
        }

        if ($hopital) {
            $query->join('UtilisateurBundle:PersonneHopital', 'h', 'WITH', 'h.personne = p2.id');
            $query->andWhere('h.hopital = :hopital');
            $query->setParameter('hopital', $hopital);
        }



        /*if ($hopital) {
            $query->andwhere('h.hopital = :hopital');
            $query->setParameter('hopital', $hopital);
        }*/

        $query->groupBy('g.code');

        $nb = $query->getQuery()->getResult();

        foreach ($nb as $k => $v):
            $data[$k][] = $v['code'];
            $data[$k][] = $v['nb'] * 1;
        endforeach;

        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('groupeSanguin');
        $ob->title->text('Taux par groupe sanguin');

        $ob->plotOptions->pie([
            'allowPointSelect' => true,
            'cursor'           => 'pointer',
            'dataLabels'       => [
                'enabled' => true
                , 'format' => self::FORMAT,
            ],
            'showInLegend'     => true,
        ]);

        $ob->series([['type' => 'pie', 'name' => 'Nombre', 'data' => $data]]);

        $objet["ob"]        = $ob;
        $objet["container"] = "groupeSanguin";
        $objet["data"]        = $data;
        return $this->container
            ->get('templating')
            ->renderResponse('GestionBundle:Stats:box-stat.html.twig', ['objet' => $objet, 'message' => $message]);

    }

    /**
     * @param $corporate
     * @param null $pays
     * @param null $hopital
     * @return mixed
     */
    public function affectionAction($corporate = null, $pays = null, $hopital = null, $annee = null)
    {
        $em      = $this->getDoctrine()->getManager();
        $message = "Affections";
        $data    = [];

        /*$query = $em->createQueryBuilder();
        $query->select('aff.nom as nom, COUNT(a.id) as nb')
        ->from('GestionBundle:Patient', 'a')
        ->join('a.affection', 'aff')
        ->groupBy('aff.nom')
        ;

        $nb = $query->getQuery()->getResult();*/

        $query = $em->createQueryBuilder();
        $query->select('a.affection as nom, COUNT(a.id) as nb')
            ->from('ParametreBundle:PatientAffections', 'a')
            ->join('a.patient', 'p')
            ->join('p.personne', 'p2');

        if ($corporate) {
            $query->andWhere('p2.corporate = :corporate');
            $query->setParameter('corporate', $corporate);
        }

        if ($pays) {
            $query->andWhere('p.pays = :pays');
            $query->setParameter('pays', $pays);
        }

        if ($annee) {
            $query->andWhere('YEAR(p2.dateInscription) = :annee');
            $query->setParameter('annee', $annee);
        }

        if ($hopital) {
            $query->join('UtilisateurBundle:PersonneHopital', 'h', 'WITH', 'h.personne = p2.id');
            $query->andWhere('h.hopital = :hopital');
            $query->setParameter('hopital', $hopital);
        }



        $query->groupBy('a.affection');

        $nb = $query->getQuery()->getResult();
        $i = 0;
        foreach ($nb as $k => $v):
            $data[$i][] = $v['nom'];
            $data[$i][] = $v['nb'] * 1;
            $i++;
        endforeach;

        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('affection');
        $ob->title->text('Taux par affection');

        $ob->plotOptions->pie([
            'allowPointSelect' => true,
            'cursor'           => 'pointer',
            'dataLabels'       => [
                'enabled' => true
                , 'format' => self::FORMAT,
            ],
            'showInLegend'     => true,
        ]);

        $ob->series([['type' => 'pie', 'name' => 'Nombre', 'data' => $data]]);

        $objet["ob"]        = $ob;
        $objet["container"] = "affection";
        $objet["data"]        = $data;
        return $this->container->get('templating')->renderResponse('GestionBundle:Stats:box-stat.html.twig', ['objet' => $objet, 'message' => $message]);

    }

    /**
     * @param $corporate
     * @param null $pays
     * @param null $hopital
     * @return mixed
     */
    public function allergieAction($corporate = null, $pays = null, $hopital = null, $annee = null)
    {
        $em      = $this->getDoctrine()->getManager();
        $message = "Allergies";
        $data    = [];

        /*$query = $em->createQueryBuilder();
        $query->select('aff.nom as nom, COUNT(a.id) as nb')
        ->from('GestionBundle:Patient', 'a')
        ->join('a.affection', 'aff')
        ->groupBy('aff.nom')
        ;

        $nb = $query->getQuery()->getResult();*/

        $query = $em->createQueryBuilder();
        $query->select('a.allergie as nom, COUNT(a.id) as nb')
            ->from('ParametreBundle:PatientAllergies', 'a')
            ->join('a.patient', 'p')
            ->join('p.personne', 'p2');

        if ($corporate) {
            $query->andWhere('p2.corporate = :corporate');
            $query->setParameter('corporate', $corporate);
        }

        if ($pays) {
            $query->andWhere('p.pays = :pays');
            $query->setParameter('pays', $pays);
        }


        if ($hopital) {
            $query->join('UtilisateurBundle:PersonneHopital', 'h', 'WITH', 'h.personne = p2.id');
            $query->andWhere('h.hopital = :hopital');
            $query->setParameter('hopital', $hopital);
        }


        /*if ($hopital) {
            $query->andwhere('h.hopital = :hopital');
            $query->setParameter('hopital', $hopital);
        }*/
        if ($annee) {
            $query->andWhere('YEAR(p2.dateInscription) = :annee');
            $query->setParameter('annee', $annee);
        }


        $query->groupBy('a.allergie')
        ;

        $nb = $query->getQuery()->getResult();

        foreach ($nb as $k => $v):
            $data[$k][] = $v['nom'];
            $data[$k][] = $v['nb'] * 1;
        endforeach;

        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('allergie');
        $ob->title->text('Taux par allergie');

        $ob->plotOptions->pie([
            'allowPointSelect' => true,
            'cursor'           => 'pointer',
            'dataLabels'       => ['enabled' => true, 'format' => self::FORMAT],
            'showInLegend'     => true,
        ]);

        $ob->series([['type' => 'pie', 'name' => 'Nombre', 'data' => $data]]);

        $objet["ob"]        = $ob;
        $objet["container"] = "allergie";
        $objet["data"]        = $data;
        return $this->container->get('templating')->renderResponse('GestionBundle:Stats:box-stat.html.twig', ['objet' => $objet, 'message' => $message]);

    }

    /**
     * @param $pays
     * @return mixed
     */
    public function regionAction($pays = null, $corporate = null, $hopital = null, $annee = null)
    {
        $em      = $this->getDoctrine()->getManager();
        $message = "Villes";
        $data    = [];

        $query = $em->createQueryBuilder();
        $query->select('aff.nom as nom, COUNT(a.id) as nb')
            ->from('GestionBundle:Patient', 'a')
            ->join('a.personne', 'p2')
            ->join('a.ville', 'aff')
            ->groupBy('aff.nom')
        ;


        if ($corporate) {
            $query->andWhere('p2.corporate = :corporate');
            $query->setParameter('corporate', $corporate);
        }

        if ($pays) {
            $query->andWhere('a.pays = :pays');
            $query->setParameter('pays', $pays);
        }

        if ($annee) {
            $query->andWhere('YEAR(p2.dateInscription) = :annee');
            $query->setParameter('annee', $annee);
        }

        if ($hopital) {
            $query->join('UtilisateurBundle:PersonneHopital', 'h', 'WITH', 'h.personne = p2.id');
            $query->andWhere('h.hopital = :hopital');
            $query->setParameter('hopital', $hopital);
        }



        /*if ($hopital) {
            $query->andwhere('h.hopital = :hopital');
            $query->setParameter('hopital', $hopital);
        }*/

        $nb = $query->getQuery()->getResult();

        /*$query = $em->createQueryBuilder();
        $query->select('a.allergie as nom, COUNT(a.id) as nb')
        ->from('ParametreBundle:PatientAllergies', 'a')
        ->groupBy('a.allergie')
        ;

        $nb = $query->getQuery()->getResult();*/

        foreach ($nb as $k => $v):
            $data[$k][] = $v['nom'];
            $data[$k][] = $v['nb'] * 1;
        endforeach;

        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('region');
        $ob->title->text('Inscription par villes');

        $ob->plotOptions->pie([
            'allowPointSelect' => true,
            'cursor'           => 'pointer',
            'dataLabels'       => ['enabled' => true, 'format' => self::FORMAT],
            'showInLegend'     => true,
        ]);

        $ob->series([['type' => 'pie', 'name' => 'Nombre', 'data' => $data]]);

        $objet["ob"]        = $ob;
        $objet["data"]        = $data;
        $objet["container"] = "region";
        return $this->container->get('templating')->renderResponse('GestionBundle:Stats:box-stat.html.twig', ['objet' => $objet, 'message' => $message]);

    }

    /**
     * @param $corporate
     * @param null $pays
     * @param null $hopital
     * @return mixed
     */
    public function assuranceAction($corporate = null, $pays = null, $hopital = null, $annee = null)
    {
        $em      = $this->getDoctrine()->getManager();
        $message = "Assurances";
        $data    = [];
        /*$query = $em->createQueryBuilder();
        $query->select('aff.nom as nom, COUNT(a.id) as nb')
        ->from('GestionBundle:Patient', 'a')
        ->join('a.affection', 'aff')
        ->groupBy('aff.nom')
        ;

        $nb = $query->getQuery()->getResult();*/

        $query = $em->createQueryBuilder();
        $query->select('a.assurance as nom, COUNT(a.id) as nb')
            ->from('ParametreBundle:PatientAssurance', 'a')
            ->join("a.patient", "p")
            ->join('p.personne', 'p2');

        if ($corporate) {
            $query->andWhere('p2.corporate = :corporate');
            $query->setParameter('corporate', $corporate);
        }

        if ($pays) {
            $query->andWhere('p.pays = :pays');
            $query->setParameter('pays', $pays);
        }


        if ($annee) {
            $query->andWhere('YEAR(p2.dateInscription) = :annee');
            $query->setParameter('annee', $annee);
        }

        if ($hopital) {
            $query->join('UtilisateurBundle:PersonneHopital', 'h', 'WITH', 'h.personne = p2.id');
            $query->andWhere('h.hopital = :hopital');
            $query->setParameter('hopital', $hopital);
        }



        /*if ($hopital) {
            $query->andwhere('h.hopital = :hopital');
            $query->setParameter('hopital', $hopital);
        }*/

        $query->groupBy('a.assurance');

        $nb = $query->getQuery()->getResult();

        foreach ($nb as $k => $v):
            $data[$k][] = $v['nom'];
            $data[$k][] = $v['nb'] * 1;
        endforeach;

        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('assurance');
        $ob->title->text('Taux par assurance');

        $ob->plotOptions->pie([
            'allowPointSelect' => true,
            'cursor'           => 'pointer',
            'dataLabels'       => ['enabled' => true, 'format' => self::FORMAT],
            'showInLegend'     => true,
        ]);

        $ob->series([['type' => 'pie', 'name' => 'Nombre', 'data' => $data]]);

        $objet["ob"]        = $ob;
        $objet["container"] = "assurance";
        $objet["data"]        = $data;
        return $this->container->get('templating')->renderResponse('GestionBundle:Stats:box-stat.html.twig', ['objet' => $objet, 'message' => $message]);

    }

    /**
     * @param $corporate
     * @param null $pays
     * @param null $hopital
     * @return mixed
     */
    public function medicamentAction($corporate = null, $pays = null, $hopital = null, $annee = null)
    {
        $em      = $this->getDoctrine()->getManager();
        $message = "Médicaments";
        $data    = [];

        /*$query = $em->createQueryBuilder();
        $query->select('aff.nom as nom, COUNT(a.id) as nb')
        ->from('GestionBundle:Patient', 'a')
        ->join('a.affection', 'aff')
        ->groupBy('aff.nom')
        ;

        $nb = $query->getQuery()->getResult();*/

        $query = $em->createQueryBuilder();
        $query->select('a.medicament as nom, COUNT(a.id) as nb')
            ->from('GestionBundle:Consultation', 'c')
            ->join('GestionBundle:ConsultationTraitements', 'a', 'WITH', 'c.consultation = c.id');


        if ($annee) {
            $query->andWhere('YEAR(c.dateConsultation) = :annee');
            $query->setParameter('annee', $annee);
        }
        $query->groupBy('a.medicament')
            
        ;

        $nb = $query->getQuery()->getResult();

        /*if ($hopital) {
            $query->andwhere('h.hopital = :hopital');
            $query->setParameter('hopital', $hopital);
        }*/

        foreach ($nb as $k => $v):
            $data[$k][] = $v['nom'];
            $data[$k][] = $v['nb'] * 1;
        endforeach;

        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('medicament');
        $ob->title->text('Taux par medicament');

        $ob->plotOptions->pie([
            'allowPointSelect' => true,
            'cursor'           => 'pointer',
            'dataLabels'       => ['enabled' => true, 'format' => self::FORMAT],
            'showInLegend'     => false,
        ]);

        $ob->series([['type' => 'pie', 'name' => 'Nombre', 'data' => $data]]);

        $objet["ob"]        = $ob;
        $objet["container"] = "medicament";
        $objet["data"]        = $data;
        return $this->container->get('templating')->renderResponse('GestionBundle:Stats:box-stat.html.twig', ['objet' => $objet, 'message' => $message]);

    }

    /**
     * @param $corporate
     * @param null $pays
     * @param null $hopital
     * @return mixed
     */
    public function vaccinAction($corporate = null, $pays = null, $hopital = null, $annee = null)
    {
        $em      = $this->getDoctrine()->getManager();
        $message = "Vaccinations";
        $data    = [];

        /*$query = $em->createQueryBuilder();
        $query->select('aff.nom as nom, COUNT(a.id) as nb')
        ->from('GestionBundle:Patient', 'a')
        ->join('a.affection', 'aff')
        ->groupBy('aff.nom')
        ;

        $nb = $query->getQuery()->getResult();*/

        $query = $em->createQueryBuilder();
        $query->select('a.vaccin as nom, COUNT(a.id) as nb')
            ->from('ParametreBundle:PatientVaccin', 'a')
            ->join("a.patient", "p")
            ->join('p.personne', 'p2');

        $queryC = $em->createQueryBuilder();
        $queryC->select($queryC->expr()->countDistinct('a.vaccin') . ' AS nb')
            ->from('ParametreBundle:PatientVaccin', 'a')
            ->join("a.patient", "p")
            ->join('p.personne', 'p2');

        if ($corporate) {
            $query->andWhere('p2.corporate = :corporate');
            $query->setParameter('corporate', $corporate);

            $queryC->andWhere('p2.corporate = :corporate');
            $queryC->setParameter('corporate', $corporate);

        }

        if ($pays) {
            $query->andWhere('p.pays = :pays');
            $query->setParameter('pays', $pays);

            $queryC->andWhere('p.pays = :pays');
            $queryC->setParameter('pays', $pays);
        }


        if ($hopital) {
            $query->join('UtilisateurBundle:PersonneHopital', 'h', 'WITH', 'h.personne = p.id');
            $query->andWhere('h.hopital = :hopital');
            $query->setParameter('hopital', $hopital);


            $queryC->join('UtilisateurBundle:PersonneHopital', 'h', 'WITH', 'h.personne = p.id');
            $queryC->andWhere('h.hopital = :hopital');
            $queryC->setParameter('hopital', $hopital);
        }



        if ($annee) {
            $query->andWhere('YEAR(p2.dateInscription) = :annee');
            $query->setParameter('annee', $annee);

            $queryC->andWhere('YEAR(p2.dateInscription) = :annee');
            $queryC->setParameter('annee', $annee);
        }


        /*if ($hopital) {
            $query->andwhere('h.hopital = :hopital');
            $query->setParameter('hopital', $hopital);
        }*/

        $query->groupBy('a.vaccin')
        ;

        $nb    = $query->getQuery()->getResult();
        $total = $queryC->getQuery()->getSingleScalarResult();

        foreach ($nb as $k => $v):

            $data[$k][] = $v['nom'];
            $data[$k][] = round(($v['nb'] * 1) / $total, 2);
        endforeach;



        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('vaccin');
        $ob->title->text('Taux par vaccin');

        $ob->plotOptions->pie([
            'allowPointSelect' => true,
            'cursor'           => 'pointer',
            'dataLabels'       => ['enabled' => true, 'format' => self::FORMAT],
            'showInLegend'     => true,
        ]);

        $ob->series([['type' => 'pie', 'name' => 'Nombre', 'data' => $data]]);

        $objet["ob"]        = $ob;
        $objet["container"] = "vaccin";
        $objet["data"]        = $data;
        return $this->container->get('templating')->renderResponse('GestionBundle:Stats:box-stat.html.twig', ['objet' => $objet, 'message' => $message]);

    }

    /**
     * @param $corporate
     * @param null $pays
     * @param null $hopital
     * @return mixed
     */
    public function pharmacieAction($corporate = null, $pays = null, $hopital = null, $annee = null)
    {
        $em      = $this->getDoctrine()->getManager();
        $message = "Pharmacies";

        /*$query = $em->createQueryBuilder();
        $query->select('aff.nom as nom, COUNT(a.id) as nb')
        ->from('GestionBundle:Patient', 'a')
        ->join('a.affection', 'aff')
        ->groupBy('aff.nom')
        ;

        $nb = $query->getQuery()->getResult();*/

        $data = [];

        $query = $em->createQueryBuilder();
        $query->select('COUNT(a.pharmacie) AS nb')
            ->addSelect('p.libPharmacie')
            ->from('GestionBundle:OperationPharmacie', 'a')
            ->join('a.pharmacie', 'p')
            ->join('a.consultation', 'c')
            ->join('GestionBundle:Patient ', 'p2', 'WITH', 'c.patient = p2.id')
            ->join('p2.personne', 'p3');

        if ($corporate) {
            $query->andWhere('p3.corporate = :corporate');
            $query->setParameter('corporate', $corporate);
        }

        if ($pays) {
            $query->andWhere('p2.pays = :pays');
            $query->setParameter('pays', $pays);
        }

        if ($annee) {
            $query->andWhere('YEAR(a.dateOperation) = :annee');
            $query->setParameter('annee', $annee);
        }

        /*if ($hopital) {
            $query->andwhere('h.hopital = :hopital');
            $query->setParameter('hopital', $hopital);
        }*/

        $query->groupBy('p.libPharmacie')
        ;

        $nb = $query->getQuery()->getResult();
        $i = 0;
        foreach ($nb as $k => $v) {
            $data[$i][] = $v['libPharmacie'];
            $data[$i][] = $v['nb'] * 1;
            $i++;
        }

        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('pharmacieStat');
        $ob->title->text('Taux par pharmacie');

        $ob->plotOptions->pie([
            'allowPointSelect' => true,
            'cursor'           => 'pointer',
            'dataLabels'       => ['enabled' => true, 'format' => self::FORMAT],
            'showInLegend'     => true,
        ]);

        $ob->series([['type' => 'pie', 'name' => 'Nombre', 'data' => $data]]);

        $objet["ob"]        = $ob;
        $objet["container"] = "pharmacieStat";
        $objet["data"]        = $data;
        return $this->container->get('templating')->renderResponse('GestionBundle:Stats:box-stat.html.twig', ['objet' => $objet, 'message' => $message]);

    }


    public function hopitauxAction($corporate = null, $hopital = null, $pays = null, $annee = null)
    {
        $message = 'Hopitaux';
        $em    = $this->getDoctrine()->getManager();
        $query = $em->createQueryBuilder();
        $query->select('h.nom as hopital, COUNT(c.id) as nb')
            ->from('GestionBundle:Consultation', 'c')
            ->join('c.hopital', 'h')
            ->join('c.patient', 'p')
            ->join('p.personne', 'p2');

        if ($corporate) {
            $query->andWhere('p2.corporate = :corporate');
            $query->setParameter('corporate', $corporate);
        }

        if ($hopital) {
            $query->andWhere('c.hopital = :hopital');
            $query->setParameter('hopital', $hopital);
        }

        
        
            
            

        $query->groupBy('hopital');

        $result = $query->getQuery()->getResult();
        $data = [];

        //dump($result);exit;

        $i = 0;
        foreach ($result as $k => $v) {
            if ($v['hopital']) {
                $data[$i][] = $v['hopital'];
                $data[$i][] = $v['nb'] * 1;
                $i++;
            }
           
            
        }
        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('consultationHop');
        $ob->title->text('Consultations par hopitaux');

        $ob->plotOptions->pie([
            'allowPointSelect' => true,
            'cursor'           => 'pointer',
            'dataLabels'       => ['enabled' => true, 'format' => self::FORMAT],
            'showInLegend'     => true,
        ]);

        $ob->series([['type' => 'pie', 'name' => 'Nombre', 'data' => $data]]);

        $objet["ob"]        = $ob;
        $objet["data"]        = $data;
        $objet["container"] = "consultationHop";
        return $this->container->get('templating')->renderResponse('GestionBundle:Stats:box-stat.html.twig', ['objet' => $objet, 'message' => $message]);
    }

    /**
     * @param $corporate
     * @param null $pays
     * @param null $hopital
     * @return mixed
     */
    public function consultationAction($corporate = null, $pays = null, $hopital = null, $annee = null)
    {
        $em    = $this->getDoctrine()->getManager();
        $query = $em->createQueryBuilder();
        $query->select('COUNT(p.id)')
            ->from('GestionBundle:Patient', 'p')
            ->join('p.personne', 'p2');
        if ($corporate) {
            $query->andWhere('p2.corporate = :corporate');
            $query->setParameter('corporate', $corporate);
        }

        if ($hopital) {
            $query->join('UtilisateurBundle:PersonneHopital', 'h', 'WITH', 'h.personne = p2.id');
            $query->andWhere('h.hopital = :hopital');
            $query->setParameter('hopital', $hopital);
        }

        if ($pays) {
            $query->andWhere('p.pays = :pays');
            $query->setParameter('pays', $pays);
        }

        if ($annee) {
            $query->andWhere('YEAR(p2.dateInscription) = :annee');
            $query->setParameter('annee', $annee);
        }
        $total = $query->getQuery()
            ->getSingleScalarResult();

        $query = $em->createQueryBuilder();
        $query->select($query->expr()->countDistinct('c.patient'))
            ->from('GestionBundle:Consultation', 'c')
            ->join('c.patient', 'p')
            ->join('p.personne', 'p2');

        if ($corporate) {
            $query->andWhere('p2.corporate = :corporate');
            $query->setParameter('corporate', $corporate);
        }

        if ($pays) {
            $query->andWhere('p.pays = :pays');
            $query->setParameter('pays', $pays);
        }

        if ($hopital) {
            $query->andWhere('c.hopital = :hopital');
            $query->setParameter('hopital', $hopital);
        }

        if ($annee) {
            $query->andWhere('YEAR(c.dateConsultation) = :annee');
            $query->setParameter('annee', $annee);
        }

        /*if ($hopital) {
            $query->andwhere('h.hopital = :hopital');
            $query->setParameter('hopital', $hopital);
        }*/

        $consultation = $query->getQuery()
            ->getSingleScalarResult();

        $message = 'Consultation';

        $data = [];

        $data[0] = ['Patient', intval($total)];
        $data[1] = ['Consultation', intval($consultation)];

        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('consultations');
        $ob->title->text('Taux par consultation');

        $ob->plotOptions->pie([
            'allowPointSelect' => true,
            'cursor'           => 'pointer',
            'dataLabels'       => ['enabled' => true, 'format' => self::FORMAT],
            'showInLegend'     => true,
        ]);

        $ob->series([['type' => 'pie', 'name' => 'Nombre', 'data' => $data]]);

        $objet["ob"]        = $ob;
        $objet["data"]        = $data;
        $objet['container'] = 'consultations';

        return $this->render('GestionBundle:Stats:box-stat.html.twig', ['objet' => $objet, 'message' => $message, 'consultation' => $consultation, 'total' => $total]);
    }

    /**
     * @param Request $request
     * @param $annee
     * @param $corporate
     * @param null $pays
     * @param null $hopital
     * @param null $totalLivraison
     * @return mixed
     */
    public function inscriptionAction($corporate = null, $pays = null, $hopital = null, $annee = null)
    {
        

        //$params = compact('annee', 'corporate', 'hopital', 'pays');

        //$url = $this->generateUrl('admin_statistique_index', $params);

        $em      = $this->getDoctrine()->getManager();
        $message = "Inscription";

        $query = $em->createQueryBuilder();

        unset($params);

        $params = [];

        $query->select('MONTH(p2.dateInscription) AS mois, COUNT(p1.id) as nb')
            ->from('GestionBundle:Patient', 'p1')
            ->join("p1.personne", "p2")
            ->leftJoin('p2.utilisateur', 'u');
        
        /*if (!$annee) {
            $annee = date('Y');
        }*/

        if ($annee) {
            $query->andWhere('YEAR(p2.dateInscription) = :annee');
            $params['annee'] = $annee;
        }
        

        if ($corporate) {
            $query->andWhere('p2.corporate = :corporate');
            $params['corporate'] = $corporate;
        }


        if ($hopital) {
            $query->join('UtilisateurBundle:PersonneHopital', 'h', 'WITH', 'h.personne = p2.id');
            $query->andwhere('h.hopital = :hopital');
            $params['hopital'] = $hopital;
        }

        if ($pays) {
            $query->andwhere('p1.pays = :pays');
            $params['pays'] = $pays;
        }

        $query->groupBy('mois');

        $query->setParameters($params);

        //$annee = 2019;
        $_data   = $query->getQuery()->getResult();

        //dump($data);exit;
        /*$_data = [1 => 542, 77, 27];*/
        $mois = [1 => 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

      
        $i = 0;
        foreach ($_data as $k => $v) {

            if (isset($mois[$v['mois']])) {
                $data[$i][] = $mois[$v['mois']];
                $data[$i][] = $v['nb'] * 1;
                $i++;
            }
            //echo $k;
            
        }

        if ($i == 0) {
            $data = [];
        }

        //dump($data);exit;

        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('inscription');
        $ob->title->text('Taux par mois (' . $annee . ')');

        $ob->plotOptions->pie([
            'allowPointSelect' => true,
            'cursor'           => 'pointer',
            'dataLabels'       => ['enabled' => true, 'format' => self::FORMAT],
            'showInLegend'     => true,
        ]);

        $ob->series([['type' => 'pie', 'name' => 'Nombre', 'data' => $data]]);

        $objet["ob"]        = $ob;
        $objet["container"] = "inscription";
        $objet["data"]        = $data;
        return $this->container
            ->get('templating')
            ->renderResponse('GestionBundle:Stats:box-stat.html.twig', ['objet' => $objet, 'message' => $message]);

    }

    /**
     * @param $corporate
     * @param null $pays
     * @param null $hopital
     * @return mixed
     */
    public function ageAction($corporate = null, $pays = null, $hopital = null, $annee = null)
    {

        $em      = $this->getDoctrine()->getManager();
        $message = "Tranche d'âge";
        $v1      = $v2      = $v3      = $v4      = $i      = 0;

        $query = $em->createQueryBuilder();
        $query->select('b.datenaissance')
            ->from('GestionBundle:Patient', 'a')
            ->join('a.personne', 'b')
           
            ->leftJoin('b.utilisateur', 'u');
            //->join('UtilisateurBundle:UtilisateurHopital', 'h', 'LEFT', 'h.utilisateur = u.id');

        if ($corporate) {
            $query->andWhere('b.corporate = :corporate');
            $query->setParameter('corporate', $corporate);
        }

        if ($pays) {
            $query->andWhere('a.pays = :pays');
            $query->setParameter('pays', $pays);
        }


         if ($annee) {
            $query->andWhere('YEAR(b.dateInscription) = :annee');
            $query->setParameter('annee', $annee);
        }


        if ($hopital) {
            $query->join('UtilisateurBundle:PersonneHopital', 'h', 'WITH', 'h.personne = b.id');
            $query->andwhere('h.hopital = :hopital');
            $query->setParameter('hopital', $hopital);
        }

        $nb = $query->getQuery()->getResult();
         $now = new \DateTime();
        foreach ($nb as $k => $v):
           
            //$age = '';

            if ($v['datenaissance']) {
                $diff = date_diff($now, $v['datenaissance']);
                $age = $diff->format("%Y");
                if ($age <= 17) {
                    $v1++;
                }

                if (($age > 17) && ($age <= 65)) {
                    $v2++;
                }

                if (65 < $age) {
                    $v3++;
                }

            } else {
                $age = 'NP';
                if ($age == 'NP') {
                    $v4++;
                }

            }

        endforeach;

        $patient["enfant"]['nb'] = $v1;
        $patient["jeune"]['nb']  = $v2;
        $patient["vieux"]['nb']  = $v3;
        $patient["np"]['nb']     = $v4;

        foreach ($patient as $k => $v):
            $data[$i][] = $k;
            $data[$i][] = $v['nb'] * 1;
            $i++;
        endforeach;


        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('age');
        $ob->title->text('Taux par tranche d\'âge');

        $ob->plotOptions->pie([
            'allowPointSelect' => true,
            'cursor'           => 'pointer',
            'dataLabels'       => ['enabled' => true, 'format' => self::FORMAT],
            'showInLegend'     => true,
        ]);

        $ob->series([['type' => 'pie', 'name' => 'Nombre', 'data' => $data]]);

        $objet["ob"]        = $ob;
        $objet["container"] = "age";
        $objet["data"]        = $data;
        return $this->container->get('templating')->renderResponse('GestionBundle:Stats:box-stat.html.twig', ['objet' => $objet, 'message' => $message]);

    }
}
