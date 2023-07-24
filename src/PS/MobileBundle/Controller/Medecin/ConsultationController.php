<?php



namespace PS\MobileBundle\Controller\Medecin;

use PS\MobileBundle\DTO\ConsultationDTO;
use FOS\RestBundle\Controller\Annotations as Rest;

use FOS\RestBundle\Controller\Annotations\QueryParam;

use FOS\RestBundle\Request\ParamFetcherInterface;

use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use PS\GestionBundle\Entity\Consultation;
use PS\GestionBundle\Entity\ConsultationAnalyses;
use PS\GestionBundle\Entity\ConsultationSigne;
use PS\GestionBundle\Entity\ConsultationTraitements;
use PS\GestionBundle\Entity\Medecin;
use PS\GestionBundle\Entity\Patient;

use PS\MobileBundle\Controller\ApiTrait;
use PS\MobileBundle\Form\ConsultationType;
use PS\ParametreBundle\Entity\Specialite;
use PS\UtilisateurBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConsultationController extends Controller
{

    use ApiTrait;



    /**
     * @Security("is_granted('ROLE_MEDECIN')")
     * @Rest\View(serializerGroups={"groupe-sanguin", "info-comp", "consultation", "consultation-signe", "specialite", "medecin", "personne", "hopital", "info-hopital", "pays", "info-patient", "photo", "consultation-analyse", "consultation-medicament"})
     * @QueryParam(name="page", requirements="\d+", default=1, description="Index de début de la pagination")
     * @QueryParam(name="limit", requirements="\d+", default=20, description="Nombre d'éléments")
     * @Rest\Post("/historique-consultations", condition="request.headers.get('Content-Type') === 'application/json'")
     */
    public function postListePatientConsultationsAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        /**
         * {"identifiant": "string", "pin": "string"}
         */
        $form = $this->createFormBuilder(null, ['csrf_protection' => false])
            ->add('identifiant', null, ['constraints' => [new NotBlank(['message' => 'Veuillez renseigner l\'identifiant'])]])
            ->add('pin', null, ['constraints' => [new NotBlank(['message' => 'Veuillez renseigner le code PIN'])]])
            ->getForm();
        $data = $request->request->all();
        $form->submit($data);

        if ($form->isValid()) {



            $patient = $this->getRepository(Patient::class)->findOneBy(['identifiant' => $data['identifiant'], 'pin' => $data['pin']]);
            if (!$patient) {
                return $this->notFound('Ce couple ID/PIN est inexistant de notre base de données');
            }

            $id = $patient->getId();

            $rep      = $this->getRepository(Consultation::class);

            $page     = intval($paramFetcher->get('page'));

            $limit    = intval($paramFetcher->get('limit'));

            //$statut = $paramFetcher->get('statut');



            $filters = []/*array_filter(compact('statut'));*/;

            $maxPages = ceil($rep->countByPatient($id, $filters) / $limit);



            if ($page <= 0 || $page > $maxPages) {

                $page = 1;

            }



            $offset = ($page - 1) * $limit;



            $consultations = $rep

                ->findByPatient(

                    $id,

                    null,

                    $limit,

                    $offset,

                    $filters

                );



            return ['maxPages' => $maxPages, 'patient' => $this->getPatient($id), 'data' => $consultations];
        }
        return $form;

    }



    /**
     * @Security("is_granted('ROLE_MEDECIN')")
     * @Rest\View(serializerGroups={"consultation", "specialite", "medecin", "personne", "hopital", "info-hopital", "pays"})
     * @Rest\Get("/consultations/{id}")
     */
    public function getConsultationAction(Request $request)
    {
        $consultation = $this->getRepository(Consultation::class)->findOneBy(['id' => $request->get('consultation')]);

        if ($consultation) {
            return $consultation;
        }

        return $this->notFound('consultation avec ID ' . $request->get('consultation') . ' inexistant ou n\'est pas associé à votre compte');
    }


    /**
     * @Security("is_granted('ROLE_MEDECIN')")
     * @Rest\View(serializerGroups={"consultation", "specialite", "medecin", "personne", "hopital", "info-hopital", "pays"})
     * @Rest\Post("/consultations", condition="request.headers.get('Content-Type') === 'application/json'")
     */
    public function postConsultationAction(Request $request)
    {
        /*
         * {
  "identifiant": XXXXX
  "pin": XXXX
  "motif": "DEMO",
  "symptome": "HELLO",
  "diagnostic": "HELLO",
  "specialite": 1,
  "ordonnances": [
    {
      "posologie": "2fois/jour",
      "medicament": "AAAAA",
      "commentaire": ""
    }
  ],
  "examens": [
    {
      "examen": "HELLO",
      "commentaire": "AAAAA"
    }
  ]
}*/
       
        $em = $this->getDoctrine()->getManager();

        $consultationDTO = new ConsultationDTO();

        $data = $request->request->all();

      


        $form = $this->createForm(ConsultationType::class, $consultationDTO, [
            'csrf_protection' => false,
        ]);

        $form->submit($data);

        if ($form->isValid()) {
            $patient = $this->getRepository(Patient::class)->findOneBy(['identifiant' => $data['identifiant'], 'pin' => $data['pin']]);
            if (!$patient) {
                return $this->notFound('Ce couple ID/PIN est inexistant de notre base de données');
            }

            $jwtManager = $this->get('lexik_jwt_authentication.jwt_manager');
            $user = $jwtManager->decode($this->get('security.token_storage')->getToken());
           
           
            $medecin = $em->getRepository(Medecin::class)->find($user['id_medecin']);
          
            $consultation = new Consultation();
            $consultationDTO->toEntity($consultation);
            $consultation->setMotif($consultationDTO->getMotif());
            $consultation->setDiagnostique($consultationDTO->getDiagnostic());
            $consultation->setDiagnostiqueFinal($consultationDTO->getDiagnostic());
            $consultation->setMedecin($medecin);
            $consultation->setPatient($patient);
            $consultation->setHopital($medecin->getHopital());

            $consultation->setSpecialite($em->getRepository(Specialite::class)->find($consultationDTO->getSpecialite()));


            foreach ($consultationDTO->getExamens() as $examen) {
                $consultationAnalyse = new ConsultationAnalyses();
                $consultationAnalyse->setAnalyse($examen->getExamen());
                $consultationAnalyse->setLibelle($examen->getCommentaire());
                $consultation->addAnalyse($consultationAnalyse);
            }


            foreach ($consultationDTO->getOrdonnances() as $ordonnance) {
                $consultationTraitement = new ConsultationTraitements();
                $consultationTraitement->setPosologie($ordonnance->getPosologie());
                $consultationTraitement->setMedicament($ordonnance->getMedicament());
                $consultationTraitement->setDetails($ordonnance->getCommentaire() ?? '');
                $consultation->addMedicament($consultationTraitement);
            }

            
            
            $signe = new ConsultationSigne();
            $signe->setType('physique');
            $signe->setSigne($consultationDTO->getSymptome());
            $consultation->addSigne($signe);

            $em->persist($consultation);
            $em->flush();
            return $consultation;
        }

        return $form;

       
    }

}

