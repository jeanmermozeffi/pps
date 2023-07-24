<?php



namespace PS\MobileBundle\Controller\Patient;



use FOS\RestBundle\Controller\Annotations as Rest;

use FOS\RestBundle\View\View;

use PS\GestionBundle\Entity\Patient;

use PS\SiteBundle\Form\PatientRechercherForm;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\HttpFoundation\Response;

use PS\MobileBundle\Controller\ApiTrait;

use PS\GestionBundle\Entity\PatientConstante;
use PS\ParametreBundle\Entity\Constante;
use PS\GestionBundle\Form\PatientConstanteType;

use FOS\RestBundle\Controller\Annotations\QueryParam;

use FOS\RestBundle\Request\ParamFetcherInterface;



class ConstanteController extends Controller

{

    use ApiTrait;



    /**

     * @Rest\View(serializerGroups={"patient-constante", "constante"})

     * @QueryParam(name="page", requirements="\d+", default=1, description="Index de début de la pagination")

     * @QueryParam(name="limit", requirements="\d+", default=20, description="Nombre d'éléments")

     * @QueryParam(name="annee", requirements="\d+", description="Année de recherche")

     * @Rest\Get("/patients/{id}/constantes/{constante}")

     */

    public function getDataConstanteAction(Request $request, ParamFetcherInterface $paramFetcher)

    {

        $id = $request->get('id');

        $constante = $request->get('constante');

        $rep      = $this->getRepository(PatientConstante::class);

        $page     = intval($paramFetcher->get('page'));

        $limit    = intval($paramFetcher->get('limit'));

        $annee = intval($paramFetcher->get('annee'));

        $maxPages = ceil($rep->countByPatient($id, $constante) / $limit);



       

        

         if ($page <= 0 || $page > $maxPages) {

            $page = 1;

        }



        $offset = ($page - 1) * $limit;



        return [
            'maxPages' => $maxPages,
             'data' => $rep->findAllByPatient($id, $constante, $annee, $limit, $offset)
             , 'constante' => $this->getRepository(Constante::class)->find($constante)
        ];

    }





    private function getConstante($patient, $constante)
    {

        $em =  $this->getManager();

        return $em->getRepository(PatientConstante::class)->findOneBy(['patient' => $patient, 'id' => $constante]);

    }







     /**

     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)

     * @Rest\Delete("/patients/{id}/constantes/{constante}")

     */

    public function deleteConstanteAction(Request $request)

    {

        $patientConstante = $this->getConstante($request->get('id'), $request->get('constante'));



        $patient = $this->getPatient($request->get('id'));



        if ($patientConstante) {

            $patient->removeConstante($patientConstante);

            $this->getManager()->flush();

            return new Response();

        }



        return $this->notFound('Constante avec ID '.$request->get('constante').' inexistante ou n\'est pas associé à votre compte');

    }


     /**

     * @Rest\View(serializerGroups={"patient-constante"})

     * @Rest\Get("/patients/{id}/constantes/{constante}/donnees/{patientConstante}")
     */
    public function getConstanteAction(Request $request)

    {

        $patientConstante = $this->getConstante($request->get('id'), $request->get('patientConstante'));



        if ($patientConstante) {

           return $patientConstante;

        }



        return $this->notFound('Constante avec ID '.$request->get('constante').' inexistante ou n\'est pas associé à votre compte');

    }





      /**

     * @Rest\View(serializerGroups={"patient-constante", "constante"})

     * @Rest\Put("/patients/{id}/constantes/{constante}")

     */

    public function putConstanteAction(Request $request)

    {

        $patientConstante = $this->getConstante( $request->get('id'), $request->get('constante') );

       
        $form = $this->createForm(PatientConstanteType::class, $patientConstante, [

            'csrf_protection' => false,
            'date_format' => 'api'

        ]);





        $form->submit($request->request->all());



        if ($form->isValid()) {

            $em = $this->getManager();

            $em->flush();

            return $patientConstante;

        }



        return $form;

    }





     /**

     * @Rest\View(serializerGroups={"patient-constante", "constante"}, statusCode=Response::HTTP_CREATED)

     * @Rest\Post("/patients/{id}/constantes/{mesure}")

     */

    public function postConstanteAction(Request $request)

    {

        $em = $this->getManager();

        $patient = $this->getPatient( $request->get('id'));

        $constante = $em->getRepository(Constante::class)->find($request->get('mesure'));



        $patientConstante = new PatientConstante();

        $patientConstante->setConstante($constante);

        $patientConstante->setPatient($patient);



        $form = $this->createForm(PatientConstanteType::class, $patientConstante, [

            'csrf_protection' => false,
            'date_format' => 'api'

        ]);





        $form->submit($request->request->all());



        if ($form->isValid()) {

            

            $em->persist($patientConstante);

            $em->flush();

            return $patientConstante;

        }



        return $form;

    }



}

