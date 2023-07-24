<?php



namespace PS\MobileBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\View\View;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Entity\Questionnaire;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;



trait ApiTrait
{

    /**

     * @param string $message

     * @return View

     */

    public function notFound(string $message): View

    {

        return View::create([

            "code"    => Response::HTTP_NOT_FOUND,

            "message" => $message

            , "errors" => [],

        ]

            , Response::HTTP_NOT_FOUND

        );

    }


    public function getManager(): EntityManagerInterface
    {
        return $this->getDoctrine()->getManager();
    }



    /**

     * @param string $repoClass

     * @return EntityRepository
     */
    public function getRepository(string $repoClass): EntityRepository
    {
        return $this->getManager()->getRepository($repoClass);
    }



    /**

     * Retourne le patient

     *

     * @param integer $id

     * @return Patient|null

     */

    public function getPatient(int $id)/*:  ?Patient*/
    {

       

        //$currentPatient = $this->getUser()->getPatient();

        if ($patient = $this->getRepository(Patient::class)->find($id)) {
            return $patient;
        }

        throw $this->createNotFoundException('api.patient_not_found');
    }



    /**

     * Retourne le questionnaire

     *

     * @param integer $id

     * @return Questionnaire|null

     */

    public function getQuestionnaire(int $id): ?Questionnaire
    {
        return $this->getRepository(Questionnaire::class)->find($id);
    }


    public function checkUser($id)
    {
        $jwtManager = $this->get('lexik_jwt_authentication.jwt_manager');
        $user = $jwtManager->decode($this->get('security.token_storage')->getToken());
        $isValid = false;
        if ($user) {
            $currentUserid = $user['id'];
            //$currentPatientId = $user['patient_id'];
            $currentPatient = $this->getPatient($user['patient_id']);
            $viewedPatient = $this->getPatient($id);

            $isValid = $viewedPatient == $currentPatient || $currentPatient->isParentOf($viewedPatient);
        }

        if (!$isValid) {
            throw $this->createAccessDeniedException('api.ressource_not_allowed');
        }
    }





    public function formatValue($data, $maps = [])
    {

        foreach ($data as $field => $value) {
            $data[$maps[$field] ?? $field] = isset($value['id']) ? $value['id'] : $value;
        }

        foreach (array_keys($maps) as $field) {
            unset($data[$field]);
        }

        return $data;

    }

}

