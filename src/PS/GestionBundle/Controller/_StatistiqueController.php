<?php

namespace PS\GestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Ob\HighchartsBundle\Highcharts\Highchart;


class StatistiqueController extends Controller
{
    const FORMAT = '{point.name} ({point.y})';


    public function indexAction(Request $request)
    {
         $annee = $request->query->get('annee') ?: date('Y');

        return $this->container->get('templating')->renderResponse('GestionBundle:Stats:index.html.twig', compact('annee'));
    }

    public function genreAction()
    {
        $em = $this->getDoctrine()->getManager();
        $message   = "Genre";

        $sexe_table = $em->getRepository('GestionBundle:Patient')->statistiqueSexe();

        foreach($sexe_table as $k=>$v):
            if(is_null($v["sexe"])) $v["sexe"] = 'NP';
            if($v["sexe"] == 'F') $v["sexe"] = 'Femme';
            if($v["sexe"] == 'M') $v["sexe"] = 'Homme';
            $data[$k][] = $v['sexe'];
            $data[$k][] = $v['nbre'] * 1;
        endforeach;

        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('genre');
        $ob->title->text('Taux par genre');

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
        $objet["container"] = "genre";
        return $this->container->get('templating')->renderResponse('GestionBundle:Stats:box-stat.html.twig', array('objet' => $objet,'message'=>$message));

    }


    public function groupeSanguinAction()
    {
        $em = $this->getDoctrine()->getManager();
        $message = 'Groupe Sanguin';

        $queryTotalGroupe = $em->createQueryBuilder();
        $queryTotalGroupe->select('g.code as code, COUNT(p.id) as nb')
            ->from('GestionBundle:Patient', 'p')
            ->join("p.groupeSanguin", "g")
            ->groupBy('g.code');

         $nb = $queryTotalGroupe->getQuery()->getResult();

         foreach($nb as $k=>$v):
            $data[$k][] = $v['code'];
            $data[$k][] = $v['nb'] * 1;
        endforeach;

        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('groupeSanguin');
        $ob->title->text('Taux par groupe sanguin');

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
        $objet["container"] = "groupeSanguin";
        return $this->container
            ->get('templating')
            ->renderResponse('GestionBundle:Stats:box-stat.html.twig', array('objet' => $objet,'message'=>$message));


    }

    public function affectionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $message   = "Affections";

        /*$queryTotalAffection = $em->createQueryBuilder();
        $queryTotalAffection->select('aff.nom as nom, COUNT(a.id) as nb')
            ->from('GestionBundle:Patient', 'a')
            ->join('a.affection', 'aff')
            ->groupBy('aff.nom')
        ;

        $nb = $queryTotalAffection->getQuery()->getResult();*/

        $queryTotalAffection = $em->createQueryBuilder();
        $queryTotalAffection->select('a.affection as nom, COUNT(a.id) as nb')
            ->from('ParametreBundle:PatientAffections', 'a')
            ->groupBy('a.affection')
        ;

        $nb = $queryTotalAffection->getQuery()->getResult();

        foreach($nb as $k=>$v):
            $data[$k][] = $v['nom'];
            $data[$k][] = $v['nb'] * 1;
        endforeach;

        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('affection');
        $ob->title->text('Taux par affection');

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
        $objet["container"] = "affection";
        return $this->container->get('templating')->renderResponse('GestionBundle:Stats:box-stat.html.twig', array('objet' => $objet,'message'=>$message));

    }

    public function allergieAction()
    {
        $em = $this->getDoctrine()->getManager();
        $message   = "Allergies";

        /*$queryTotalAffection = $em->createQueryBuilder();
        $queryTotalAffection->select('aff.nom as nom, COUNT(a.id) as nb')
            ->from('GestionBundle:Patient', 'a')
            ->join('a.affection', 'aff')
            ->groupBy('aff.nom')
        ;

        $nb = $queryTotalAffection->getQuery()->getResult();*/

        $queryTotalAffection = $em->createQueryBuilder();
        $queryTotalAffection->select('a.allergie as nom, COUNT(a.id) as nb')
            ->from('ParametreBundle:PatientAllergies', 'a')
            ->groupBy('a.allergie')
        ;

        $nb = $queryTotalAffection->getQuery()->getResult();

        foreach($nb as $k=>$v):
            $data[$k][] = $v['nom'];
            $data[$k][] = $v['nb'] * 1;
        endforeach;

        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('allergie');
        $ob->title->text('Taux par allergie');

        $ob->plotOptions->pie(array(
            'allowPointSelect'  => true,
            'cursor'    => 'pointer',
            'dataLabels'    => array('enabled' => true, 'format' => self::FORMAT),
            'showInLegend'  => true
        ));

        $ob->series(array(array('type' => 'pie','name' => 'Nombre', 'data' => $data)));

        $objet["ob"] = $ob;
        $objet["container"] = "allergie";
        return $this->container->get('templating')->renderResponse('GestionBundle:Stats:box-stat.html.twig', array('objet' => $objet,'message'=>$message));

    }

    public function regionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $message   = "Regions";

        $queryTotalAffection = $em->createQueryBuilder();
        $queryTotalAffection->select('aff.nom as nom, COUNT(a.id) as nb')
            ->from('GestionBundle:Patient', 'a')
            ->join('a.ville', 'aff')
            ->groupBy('aff.nom')
        ;

        $nb = $queryTotalAffection->getQuery()->getResult();

        /*$queryTotalAffection = $em->createQueryBuilder();
        $queryTotalAffection->select('a.allergie as nom, COUNT(a.id) as nb')
            ->from('ParametreBundle:PatientAllergies', 'a')
            ->groupBy('a.allergie')
        ;

        $nb = $queryTotalAffection->getQuery()->getResult();*/

        foreach($nb as $k=>$v):
            $data[$k][] = $v['nom'];
            $data[$k][] = $v['nb'] * 1;
        endforeach;

       
        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('region');
        $ob->title->text('Taux par region');

        $ob->plotOptions->pie(array(
            'allowPointSelect'  => true,
            'cursor'    => 'pointer',
            'dataLabels'    => array('enabled' => true, 'format' => self::FORMAT),
            'showInLegend'  => true
        ));

        $ob->series(array(array('type' => 'pie','name' => 'Nombre', 'data' => $data)));

        $objet["ob"] = $ob;
        $objet["container"] = "region";
        return $this->container->get('templating')->renderResponse('GestionBundle:Stats:box-stat.html.twig', array('objet' => $objet,'message'=>$message));

    }

    public function assuranceAction()
    {
        $em = $this->getDoctrine()->getManager();
        $message   = "Assurances";

        /*$queryTotalAffection = $em->createQueryBuilder();
        $queryTotalAffection->select('aff.nom as nom, COUNT(a.id) as nb')
            ->from('GestionBundle:Patient', 'a')
            ->join('a.affection', 'aff')
            ->groupBy('aff.nom')
        ;

        $nb = $queryTotalAffection->getQuery()->getResult();*/

        $queryTotalAffection = $em->createQueryBuilder();
        $queryTotalAffection->select('a.assurance as nom, COUNT(a.id) as nb')
            ->from('ParametreBundle:PatientAssurance', 'a')
            ->groupBy('a.assurance')
        ;

        $nb = $queryTotalAffection->getQuery()->getResult();

        foreach($nb as $k=>$v):
            $data[$k][] = $v['nom'];
            $data[$k][] = $v['nb'] * 1;
        endforeach;

        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('assurance');
        $ob->title->text('Taux par assurance');

        $ob->plotOptions->pie(array(
            'allowPointSelect'  => true,
            'cursor'    => 'pointer',
            'dataLabels'    => array('enabled' => true, 'format' => self::FORMAT),
            'showInLegend'  => true
        ));

        $ob->series(array(array('type' => 'pie','name' => 'Nombre', 'data' => $data)));

        $objet["ob"] = $ob;
        $objet["container"] = "assurance";
        return $this->container->get('templating')->renderResponse('GestionBundle:Stats:box-stat.html.twig', array('objet' => $objet,'message'=>$message));

    }

    public function medicamentAction()
    {
        $em = $this->getDoctrine()->getManager();
        $message   = "Médicaments";

        /*$queryTotalAffection = $em->createQueryBuilder();
        $queryTotalAffection->select('aff.nom as nom, COUNT(a.id) as nb')
            ->from('GestionBundle:Patient', 'a')
            ->join('a.affection', 'aff')
            ->groupBy('aff.nom')
        ;

        $nb = $queryTotalAffection->getQuery()->getResult();*/

        $query = $em->createQueryBuilder();
        $query->select('a.medicament as nom, COUNT(a.id) as nb')
            ->from('GestionBundle:ConsultationTraitements', 'a')
            ->groupBy('a.medicament')
        ;

        $nb = $query->getQuery()->getResult();

        foreach($nb as $k=>$v):
            $data[$k][] = $v['nom'];
            $data[$k][] = $v['nb'] * 1;
        endforeach;

        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('medicament');
        $ob->title->text('Taux par medicament');

        $ob->plotOptions->pie(array(
            'allowPointSelect'  => true,
            'cursor'    => 'pointer',
            'dataLabels'    => array('enabled' => true, 'format' => self::FORMAT),
            'showInLegend'  => false
        ));

        $ob->series(array(array('type' => 'pie','name' => 'Nombre', 'data' => $data)));

        $objet["ob"] = $ob;
        $objet["container"] = "medicament";
        return $this->container->get('templating')->renderResponse('GestionBundle:Stats:box-stat.html.twig', array('objet' => $objet,'message'=>$message));

    }

    public function vaccinAction()
    {
        $em = $this->getDoctrine()->getManager();
        $message   = "Vaccinations";

        /*$queryTotalAffection = $em->createQueryBuilder();
        $queryTotalAffection->select('aff.nom as nom, COUNT(a.id) as nb')
            ->from('GestionBundle:Patient', 'a')
            ->join('a.affection', 'aff')
            ->groupBy('aff.nom')
        ;

        $nb = $queryTotalAffection->getQuery()->getResult();*/

        $queryTotalAffection = $em->createQueryBuilder();
        $queryTotalAffection->select('a.vaccin as nom, COUNT(a.id) as nb')
            ->from('ParametreBundle:PatientVaccin', 'a')
            ->groupBy('a.vaccin')
        ;

        $nb = $queryTotalAffection->getQuery()->getResult();

        foreach($nb as $k=>$v):

            $data[$k][] = $v['nom'];
            $data[$k][] = $v['nb'] * 1;
        endforeach;

        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('vaccin');
        $ob->title->text('Taux par vaccin');

        $ob->plotOptions->pie(array(
            'allowPointSelect'  => true,
            'cursor'    => 'pointer',
            'dataLabels'    => array('enabled' => true, 'format' => self::FORMAT),
            'showInLegend'  => true
        ));

        $ob->series(array(array('type' => 'pie','name' => 'Nombre', 'data' => $data)));

        $objet["ob"] = $ob;
        $objet["container"] = "vaccin";
        return $this->container->get('templating')->renderResponse('GestionBundle:Stats:box-stat.html.twig', array('objet' => $objet,'message'=>$message));

    }


    public function pharmacieAction()
    {
         $em = $this->getDoctrine()->getManager();
        $message   = "Pharmacies";

        /*$queryTotalAffection = $em->createQueryBuilder();
        $queryTotalAffection->select('aff.nom as nom, COUNT(a.id) as nb')
            ->from('GestionBundle:Patient', 'a')
            ->join('a.affection', 'aff')
            ->groupBy('aff.nom')
        ;

        $nb = $queryTotalAffection->getQuery()->getResult();*/

        $query = $em->createQueryBuilder();
        $query->select($query->expr()->countDistinct('a.pharmacie').' AS nb')
            ->addSelect('p.libPharmacie')
            ->from('GestionBundle:OperationPharmacie', 'a')
            ->join('a.pharmacie', 'p')
            ->groupBy('p.libPharmacie')
        ;

        $nb = $query->getQuery()->getResult();

        foreach($nb as $k=>$v):
            $data[$k][] = $v['libPharmacie'];
            $data[$k][] = $v['nb'] * 1;
        endforeach;

        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('pharmacie');
        $ob->title->text('Taux par pharmacie');

        $ob->plotOptions->pie(array(
            'allowPointSelect'  => true,
            'cursor'    => 'pointer',
            'dataLabels'    => array('enabled' => true, 'format' => self::FORMAT),
            'showInLegend'  => true
        ));

        $ob->series(array(array('type' => 'pie','name' => 'Nombre', 'data' => $data)));

        $objet["ob"] = $ob;
        $objet["container"] = "pharmacie";
        return $this->container->get('templating')->renderResponse('GestionBundle:Stats:box-stat.html.twig', array('objet' => $objet,'message'=>$message));

    }


    public function consultationAction()
    {
         $em         = $this->getDoctrine()->getManager();
          $query = $em->createQueryBuilder();
         $total = $query->select('COUNT(p.id)')->from('GestionBundle:Patient', 'p')->getQuery()->getSingleScalarResult();
         $consultation = $query->select($query->expr()->countDistinct('c.patient'))
                ->from('GestionBundle:Consultation', 'c')
                ->getQuery()
                ->getSingleScalarResult();

       
        $message = 'Consultation';

        $data = [];


        $consultation = 50;
        $data[0] = ['Patient', intval($total)];
        $data[1] = ['Consultation', intval($consultation)];

        
        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('consultations');
        $ob->title->text('Taux par consultation');

        $ob->plotOptions->pie(array(
            'allowPointSelect'  => true,
            'cursor'    => 'pointer',
            'dataLabels'    => array('enabled' => true, 'format' => self::FORMAT),
            'showInLegend'  => true
        ));

        $ob->series(array(array('type' => 'pie','name' => 'Nombre', 'data' => $data)));

        $objet["ob"] = $ob;
        $objet['container'] = 'consultations';


        return $this->render('GestionBundle:Stats:box-stat.html.twig', array('objet' => $objet,'message'=>$message, 'consultation' => $consultation, 'total' => $total));
    }

    public function inscriptionAction(Request $request, $annee)
    {
        $annees = range(date('Y') - 2, date('Y') + 30);

       

        $em         = $this->getDoctrine()->getManager();
        $message    = "Inscription";

        $query = $em->createQueryBuilder();

        $query->select('MONTH(u.dateInscription) AS mois, COUNT(p1.id) as nb')
            ->from('GestionBundle:Patient', 'p1')
            ->join("p1.personne", "p2")
            ->join('UtilisateurBundle:Utilisateur', 'u', 'WITH', 'u.personne = p2.id')
            
            ->where('YEAR(u.dateInscription) = :year')
            ->groupBy('mois')
            ->setParameter('year', $annee);

       

        $nb = $query->getQuery()->getResult();
        $mois = [1 => 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

        $data = [];

        foreach($nb as $k=>$v):
            $data[$k][] = $mois[$v['mois']];
            $data[$k][] = $v['nb'] * 1;
        endforeach;

        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('inscription');
        $ob->title->text('Taux par mois ('.$annee.')');

        $ob->plotOptions->pie(array(
            'allowPointSelect'  => true,
            'cursor'    => 'pointer',
            'dataLabels'    => array('enabled' => true, 'format' => self::FORMAT),
            'showInLegend'  => true
        ));

        $ob->series(array(array('type' => 'pie','name' => 'Nombre', 'data' => $data)));

        $objet["ob"] = $ob;
        $objet["container"] = "inscription";
        return $this->container
            ->get('templating')
            ->renderResponse('GestionBundle:Stats:box-stat.html.twig', array('objet' => $objet,'message'=>$message, 'annees' => $annees, 'annee' => $annee));


        
            
    }

    public function ageAction()
    {
        $em         = $this->getDoctrine()->getManager();
        $message    = "Tranche d'âge";
        $v1 = $v2 = $v3 = $v4 = $i = 0;

        $query = $em->createQueryBuilder();
        $query->select('b.datenaissance')
            ->from('GestionBundle:Patient', 'a')
            ->leftjoin('a.personne','b');

        $nb = $query->getQuery()->getResult();

        foreach($nb as $k=>$v):
            $now = new \DateTime();
            $age = '';

            if($v['datenaissance'] != null){
                $age = date_diff($now, $v['datenaissance']);
                $age = $age->format("%Y");
                if($age <= 17) $v1++;
                if((17 < $age) || ($age <= 65)) $v2++;
                if(65 < $age) $v3++;
            }
            else{
                $age = 'NP';
                if($age == 'NP') $v4++;
            }

        endforeach;

        $patient["enfant"]['nb'] = $v1 ;
        $patient["jeune"]['nb'] = $v2 ;
        $patient["vieux"]['nb'] = $v3 ;
        $patient["np"]['nb'] = $v4 ;

        foreach($patient as $k=>$v):
            $data[$i][] = $k;
            $data[$i][] = $v['nb'] * 1;
            $i++;
        endforeach;


        $ob = new Highchart();
        // ID de l'élement de DOM que vous utilisez comme conteneur
        $ob->chart->renderTo('age');
        $ob->title->text('Taux par tranche d\'âge');

        $ob->plotOptions->pie(array(
            'allowPointSelect'  => true,
            'cursor'    => 'pointer',
            'dataLabels'    => array('enabled' => true, 'format' => self::FORMAT),
            'showInLegend'  => true
        ));

        $ob->series(array(array('type' => 'pie','name' => 'Nombre', 'data' => $data)));

        $objet["ob"] = $ob;
        $objet["container"] = "age";
        return $this->container->get('templating')->renderResponse('GestionBundle:Stats:box-stat.html.twig', array('objet' => $objet,'message'=>$message));

    }
}
