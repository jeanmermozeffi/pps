<?php

namespace PS\ParametreBundle\Controller;

use PS\ParametreBundle\Entity\Fichier;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class FichierController extends Controller
{
    

     /**
     * @param $id
     * @return mixed
     */
    public function showAction(Request $request, Fichier $fichier)
    {
       
        $fileName = $fichier->getFileName();
        $filePath = $fichier->getPath();
        $download = $request->query->get('download');

        $file = null;

        try {
            $file = $this->getUploadDir($filePath . '/' . $fileName);
        } catch (FileNotFoundException $e) {
            $file = $this->getUploadDir($fileName);
        } catch (FileNotFoundException $e) {
            $file = null;
            //return new Response('Fichier invalide');
        }


        $this->get('app.action_logger')
                    ->add('Téléchargement fichier '.$fichier->getFileName.'(#'.$fichier->getId().')');

        if ($file == null) {
            return new Response('Fichier invalide');
        }

        if ($download) {
            return $this->file($file);
        }

        return new BinaryFileResponse($file);
    }



     /**
     * @return mixed
     */
    public function getUploadDir($path)
    {
        return $this->getParameter('upload_dir') . '/' . $path;
    }

    /**
     * @param Request $request
     * @param PieceJointeMission $id
     */
    public function deleteAction(Request $request, Fichier $id)
    {
        if (!$request->isXmlHttpRequest() || !$request->isMethod('DELETE')) {
            return JsonReponse(['statut' => 0, 'message' => 'Action invalide'], JsonResponse::HTTP_METHOD_NOT_ALLOWED);
        }

        if ($id) {
            $em          = $this->getDoctrine()->getManager();
            //$pieceJointe = $em->getRepository(PieceJointeMission::class)->find($id);

            $em->remove($id);
            $em->flush();

            return new JsonResponse(['statut' => 1]);

            //return $this->redirectToRoute('admin_sise_dano_edit', ['id' => $pano->getDano()->getId()]);
        }
    }

}
