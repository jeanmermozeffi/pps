<?php



namespace PS\MobileBundle\Controller\Patient;



use FOS\RestBundle\Controller\Annotations as Rest;

use FOS\RestBundle\View\View;

use PS\GestionBundle\Entity\Patient;

use PS\ParametreBundle\Entity\LienParente;

use PS\SiteBundle\Form\PatientRechercherForm;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\HttpFoundation\Response;

use PS\MobileBundle\Controller\ApiTrait;

use PS\UtilisateurBundle\Entity\CompteAssocie;

use PS\UtilisateurBundle\Form\CompteAssocieType;

use FOS\RestBundle\Controller\Annotations\QueryParam;

use FOS\RestBundle\Request\ParamFetcherInterface;

use PS\ParametreBundle\Entity\Pass;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\Validator\Validation;



class AssocieController extends Controller

{

    use ApiTrait;



    /**

     * @Rest\View(serializerGroups={"patient-associe", "personne", "photo", "lien-parente"})

     * @QueryParam(name="page", requirements="\d+", default=1, description="Index de début de la pagination")

     * @QueryParam(name="limit", requirements="\d+", default=10, description="Nombre d'éléments")

     * @Rest\Get("/patients/{id}/associes")

     */
    public function getAssociesAction(Request  $request, ParamFetcherInterface $paramFetcher)
    {

        

        $patient = $this->getPatient($request->get('id'));

        $page     = intval($paramFetcher->get('page'));

        $limit    = intval($paramFetcher->get('limit'));

        

        $associes = $patient->getAssocies();





        



        $maxPages = ceil(count($associes) / $limit);



       



        if ($page <= 0 || $page > $maxPages) {

            $page = 1;

        }



        $offset = ($page - 1) * $limit;



        return ['maxPages' => $maxPages, 'data' => $associes->slice($offset, $limit)];

    }







    /**
     * 
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"patient-associe", "personne", "photo", "lien-parente"})
     * @QueryParam(name="_locale", default="fr")
     * @Rest\Post("/patients/{id}/associes")
     */
    public function postAssocieAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $locale = $paramFetcher->get('_locale');
        $form = $this->createFormBuilder(null, ['csrf_protection' => false]);

        $form->add('identifiant', null, [

            'constraints' => new NotBlank(['message' => 'Veuillez renseigner l\'ID'])

        ])->add('pin', null, [

                'constraints' => new NotBlank(['message' => 'Veuillez renseigner le PIN'])

        ])->add('lien', EntityType::class, ['class' => LienParente::class, 'choice_label' => 'libelle']);



        $data = $request->request->all();



        $_form = $form->getForm();



        $_form->submit($data);





        if ($_form->isValid()) {

            $em = $this->getManager();

            $patient = $this->getPatient($request->get('id'));

            if (!$patient->getPin() || !$patient->getIdentifiant()) {
                throw $this->createAccessDeniedException('Vous devez disposer d\'un PASS pour associer un compte');
            }

            $associe = new CompteAssocie();









            $identifiant = $data['identifiant'];

            $pin = $data['pin'];

           

            $_pass = $em->getRepository(Pass::class)->findOneBy(compact('identifiant', 'pin'));



           





            if (!$_pass) {

                return $this->notFound('Le couple ID/PIN est inexistant de notre BDD');

            }



            $_patient = $em->getRepository(Patient::class)->findOneBy(compact('identifiant', 'pin'));





            if ($_patient && $patient->isParentOf($_patient)) {

                throw $this->createAccessDeniedException('Le couple ID/PIN est déjà associé à votre compte');

            }



            if ($_patient == $patient) {

                throw $this->createAccessDeniedException('Vous ne pouvez pas vous associer vous même');

            }



            if ($em->getRepository(CompteAssocie::class)->findOneBy(['associe' => $_patient])) {

                throw $this->createAccessDeniedException('Le couple est déjà associé à un autre compte');

            }









            $_patient = $_patient ?: new Patient();

            $_patient->setIdentifiant($identifiant);

            $_patient->setPin($pin);



            $associe = new CompteAssocie();

            $associe->setAssocie($_patient);

            $associe->setLien($em->getRepository(LienParente::class)->find($data['lien']));

            $associe->setPatient($patient);

            $em->persist($_patient);

            $em->persist($associe);



            $em->flush();



            return $associe;
        }

        return $_form;


    }





    /**

     * Retourne l'associé du patient

     *

     * @param integer $patient

     * @param integer $associe

     * @return CompteAssocie|null

     */

    public function getAssocie(int $patient, int $associe): ?CompteAssocie

    {

        return $this->getRepository(CompteAssocie::class)->findOneBy(compact('associe', 'patient'));

    }









    /**

     * @Rest\View(serializerGroups={"patient-associe", "personne", "photo", "lien-parente"})

     * @Rest\Patch("/patients/{id}/associes/{associe}")

     */

    public function patchAssocieAction(Request $request)

    {

        $em = $this->getManager();

       



        $associe = $this->getAssocie($request->get('id'), $request->get('associe'));





        if (!$associe) {

            return $this->notFound('Ce compte n\'est pas associé au votre ou est inexistant');

        }



        $form = $this->createFormBuilder($associe, ['csrf_protection' => false]);

        $form->add('lien', EntityType::class, ['class' => LienParente::class, 'choice_label' => 'libelle']);







        $form->submit($request->request->all(), false);



        if ($form->isValid()) {

           

            $em->flush();

            return $associe;

        }



        return $form;

    }





    /**

     * @Rest\View(serializerGroups={"patient-associe", "personne", "photo", "lien-parente"})

     * @Rest\Patch("/patients/{id}/associes/{associe}/patient")

     */

    public function getPatientAssocieAction(Request $request)

    {

        $em = $this->getManager();





        $associe = $this->getAssocie($request->get('id'), $request->get('associe'));





        if (!$associe) {

            return $this->notFound('Ce compte n\'est pas associé au votre ou est inexistant');

        }



        return $associe->getAssocie();

    }









     /**

     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)

     * @Rest\Delete("/patients/{id}/associes/{associe}")

     */

    public function deleteAssocieAction(Request $request)

    {

        $em = $this->getManager();

        $patient = $this->getPatient($request->get('id'));



        $associe = $this->getAssocie($request->get('id'), $request->get('associe'));



        





        if (!$associe) {

            return $this->notFound('Ce compte n\'est pas associé au votre ou est inexistant');

        }





        $em->remove($associe);

        //$em->persist($patient);

        $em->flush();



    }

}

