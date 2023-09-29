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

use PS\GestionBundle\Form\PatientType;

use PS\MobileBundle\Controller\ApiTrait;

use Symfony\Component\HttpFoundation\File\File;

use PS\MobileBundle\Service\ApiUploadedFile;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use PS\ParametreBundle\Entity\Image;

use PS\MobileBundle\Form\PhotoType;

use PS\MobileBundle\Form\UpdateUserType;

use FOS\UserBundle\Event\FormEvent;

use FOS\UserBundle\FOSUserEvents;





class PatientController extends Controller

{

    use ApiTrait;











    /**

     * @Rest\View(serializerGroups={"patient", "personne", "ville", "pays", "patient-vaccination", "patient-assurance", "assurance", "patient-contact", "patient-allergie", "photo", "patient-affection", "patient-traitement", "patient-medecin", "specialite", "groupe-sanguin", "statut"})

     * @Rest\Get("/patients/{id}/profil")

     */

    public function getPatientAction(Request $request)

    {

        $patient = $this->getPatient($request->get('id'));



        if ($patient) {

            return $patient;

        }



        return $this->notFound('Ce compte n\'est pas associé au votre ou est inexistant');

    }



    /**

     * @Rest\View(serializerGroups={"patient", "personne", "ville", "pays", "patient-vaccination", "patient-assurance", "assurance", "patient-contact", "patient-allergie", "photo", "patient-affection", "patient-traitement", "patient-medecin", "specialite", "groupe-sanguin", "statut"})

     * @Rest\Post("/profil")

     */

    public function postProfilAction(Request $request)
    {

        $em   = $this->getDoctrine()->getManager();

        $form = $this->createForm(PatientRechercherForm::class, null, [

            'csrf_protection'   => false,

            'validation_groups' => ['Default', 'api'],

        ]);

        $form->submit($request->request->all());





        if ($form->isValid()) {

            $patient = $em->getRepository(Patient::class)->findOneBy(['pin' => $request->get('pin'), 'identifiant' => $request->get('identifiant')]);
            


            if ($patient) {

                $contact = $request->get('contact');
                $urgence = $request->get('urgence');
                $localisation = $request->get('localisation');

                $smsManager     = $this->get('app.ps_sms');
               
                $logger = $this->get('app.action_logger');

                $personne = $patient->getPersonne();

                if ($contact != $personne->getSmsContact()) {
                    $logger->add("Votre profil médical a été consulté par le N° {$contact}", $patient, true);
                    $smsManager->send(sprintf("Votre Profil médical PSM est entrain d'être consulté par le numéro (%s)", $contact), $personne->getSmsContact());
                }

                if ($urgence) {
                    

                    $telephones = array_slice($patient->getTelephones()->toArray(), 0, 2);

                    foreach ($telephones as $telephone) {
                        $nomComplet = $personne->getNomComplet();
                        $numeroParent = $telephone->getNumero();
                        if ($numeroParent != $contact) {
                            $smsManager->send(
                                "Urgence possible concernant {$nomComplet}. Contactez le {$contact}\nPASS POSTE",
                                $numeroParent
                            );

                            if ($localisation ) {
                                $smsManager->send(
                                    "Localisation estimée de {$nomComplet}: {$localisation}\nPASS POSTE ",
                                    $numeroParent
                                );
                            }
                        }
                        

                        if ($localisation ) {
                            $smsManager->send(
                                "Localisation estimée de {$nomComplet}: {$localisation}",
                                $numeroParent
                            );
                        }
                    }
                }

                return $patient;

            }





            return \FOS\RestBundle\View\View::create([

                    "code"    => Response::HTTP_NOT_FOUND,

                    "message" => "Ce ID/PIN n'existe pas dans notre base de données! ou n'est pas associé à un compte patient."

                    , "errors" => []

                ]

                , Response::HTTP_NOT_FOUND

            );



        }



        return $form;

    }





    /**

     * @Rest\View(serializerGroups={"patient", "personne", "ville", "pays", "photo", "groupe-sanguin", "statut"})

     * @Rest\Get("/patients/{id}")

     */

    public function getProfilAction(Request $request)

    {

        $patient = $this->getPatient($request->get('id'));



        if ($patient) {

            return $patient;

        }



        return $this->notFound('Ce compte n\'est pas associé au votre ou est inexistant');

    }





    /**

     * @Rest\View(serializerGroups={"patient", "personne", "ville", "pays", "photo", "groupe-sanguin", "statut"})

     * @Rest\Patch("/patients/{id}")

     */

    public function patchProfilAction(Request $request)

    {

        $patient = $this->getPatient($request->get('id'));



        $data = $this->formatValue($request->request->all());







        $form = $this->createForm(PatientType::class, $patient, [

            'csrf_protection' => false,

            'date_format' => 'api',

            'entity_manager' => $this->getManager()

        ]);



        $form->submit($data, false);



        if ($form->isValid()) {

            $em = $this->getManager();

            $em->flush();

            return $patient;

        }



        return $form;

    }





    /**

     * @Rest\View(serializerGroups={"photo"})
     * @Rest\Post("/patients/{id}/photo")
     */
    public function postPhotoAction(Request $request)
    {

        //$data = $request->request->all();

        $personne = $this->getPatient($request->get('id'))->getPersonne();

        if ($request->files->has('file')) {


            $file =  $request->files->get('file');
        

            $image = new Image();

            $form  = $this->createForm(PhotoType::class, $image);



            $form->submit(compact('file'));



            if ($form->isValid()) {



                $em = $this->getManager();



                $image->setFile($file);



                $em->persist($image);

                



                $personne->setPhoto($image);



                $em->merge($personne);



                $em->flush();



                return $image;

            }



            return $form;



        }



        return $personne->getPhoto();



    }







     /**

     * @Rest\View(serializerGroups={"user"})

     * @Rest\Patch("patients/{id}/user")

     */

    public function patchUserAction(Request $request)

    {

        $patient = $this->getPatient($request->get('id'));

        $userManager = $this->get('fos_user.user_manager');

        $user        = $patient->getUtilisateur();

        //dump($user);exit;

        //$user->setEnabled(true);



        $form = $this->createForm(UpdateUserType::class, $user);



        //$form->setData($user);



        $form->submit(array_filter($request->request->all()), false);



        if ($form->isValid()) {

            



            $userManager->updateUser($user);





            $jwtManager = $this->get('lexik_jwt_authentication.jwt_manager');



            return ['token' => $jwtManager->create($user), 'user' => $user];

            //return $user;



        }



        return $form;

    }









    /**

     * @Rest\View(serializerGroups={"user"})

     * @Rest\Get("patients/{id}/user")

     */

    public function getUserAction(Request $request)

    {

        $patient = $this->getPatient($request->get('id'));

        $userManager = $this->get('fos_user.user_manager');

        return $patient->getUtilisateur();

        

    }

}

