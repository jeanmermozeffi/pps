<?php

namespace PS\ParametreBundle\Controller;

use PS\GestionBundle\Service\RowAction;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Form\ExportType;
use PS\ParametreBundle\Entity\Nationalite;
use PS\ParametreBundle\Entity\Pays;
use PS\ParametreBundle\Entity\Ville;
use PS\ParametreBundle\Form\PaysType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Pays controller.
 *
 */
class PaysController extends Controller
{
    /**
     * Lists all Pays entities.
     *
     */
    public function indexAction(Request $request)
    {
        $source = new Entity('ParametreBundle:Pays');
        $grid   = $this->get('grid');
        $grid->setSource($source);

        $rowAction = new RowAction('Détails', 'admin_parametre_pays_show');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Pays:show', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Modifier', 'admin_parametre_pays_edit');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Pays:edit', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        $rowAction = new RowAction('Supprimer', 'admin_parametre_pays_delete');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Pays:delete', 'parameters' => ['id' => $row->getField('id')]];
        });
        $grid->addRowAction($rowAction);

        return $grid->getGridResponse('ParametreBundle:Pays:grid.html.twig');
    }

    /**
     * Creates a new Pays entity.
     *
     */
    public function newAction(Request $request)
    {
        $pays = new Pays();
        $form = $this->createForm(PaysType::class, $pays, [
            'action' => $this->generateUrl('admin_parametre_pays_new'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pays);
            $em->flush();

            return $this->redirectToRoute('admin_parametre_pays_index');
        }

        return $this->render('ParametreBundle:Pays:new.html.twig', [
            'pays' => $pays,
            'form' => $form->createView(),
        ]);
    }


    public function villeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        $villes = $em->getRepository(Ville::class)->findByPays($request->query->get('pays'));

        $response = [];

        foreach ($villes as $ville) {
            $response[] = ['id' => $ville->getId(), 'name' => $ville->getNom()];
        }

        return new JsonResponse($response);
    }

    /**
     * Finds and displays a Pays entity.
     *
     */
    public function showAction(Pays $pays)
    {
        $showForm = $this->createForm(PaysType::class, $pays);

        $deleteForm = $this->createDeleteForm($pays);

        return $this->render('ParametreBundle:Pays:show.html.twig', [
            'pays'        => $pays,
            'show_form'   => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Pays entity.
     *
     */
    public function editAction(Request $request, Pays $pays)
    {
        $deleteForm = $this->createDeleteForm($pays);
        $editForm   = $this->createForm(PaysType::class, $pays, [
            'action' => $this->generateUrl('admin_parametre_pays_edit', ['id' => $pays->getId()]),
            'method' => 'POST',
        ]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_parametre_pays_index');
        }

        return $this->render('ParametreBundle:Pays:edit.html.twig', [
            'pays'        => $pays,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a Pays entity.
     *
     */
    public function deleteAction(Request $request, Pays $pays)
    {
        $form = $this->createDeleteForm($pays);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($pays);
            $em->flush();

            return $this->redirectToRoute('admin_parametre_pays_index');
        }

        return $this->render('ParametreBundle:Pays:delete.html.twig', ['form' => $form->createView(), 'pays' => $pays]);
    }

    /**
     * Creates a form to delete a Pays entity.
     *
     * @param Pays $pays The Pays entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Pays $pays)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_parametre_pays_delete', ['id' => $pays->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function importAction(Request $request)
    {
        $em       = $this->getDoctrine()->getManager();
        $rep      = $em->getRepository(Pays::class);
        $repN     = $em->getRepository(Nationalite::class);
        $repVille = $em->getRepository(Ville::class);

        $errors = [];
        $form   = $this->createForm(ExportType::class);

        $form->handleRequest($request);
        $exportPays = $exportNat = $exportVille = 0;
        $total      = 0;
        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                //dump('mmmmm');

                $fichier = $this->get('app.psm_logo_uploader')
                    ->upload($form->get('file')->getData(), null, $path, 'pays');

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

                    $pays = $loader->getActiveSheet()->getTitle();
                    $data = $loader->getActiveSheet()->toArray();
                    //unset($data[0]);

                    $nationalite = $data[0][0];
                    $villes      = array_slice($data, 1);

                    unset($data[0]);

                    if (!$idPays = $rep->exists($pays)) {
                        $_pays = new Pays();
                        $_pays->setNom($pays);

                        $em->persist($_pays);
                        $em->flush();
                        $idPays = $_pays->getId();
                        $exportPays += 1;
                    }

                    if (!$repN->exists($nationalite)) {
                        $_nationalite = new Nationalite();
                        $_nationalite->setLibNationalite($nationalite);

                        $em->persist($_nationalite);
                        $em->flush();

                        $exportNat += 1;
                    }

                    foreach ($data as $_data) {
                        $ville = $_data[0];
                        if ($ville && !$repVille->exists($ville, ['a.pays' => $idPays])) {
                            $_ville = new Ville();
                            $_ville->setNom($ville);
                            $_ville->setPays($em->getReference(Pays::class, $idPays));
                            $em->persist($_ville);
                            $em->flush();
                            $exportVille += 1;
                        }
                    }
                }

                if ($exportVille + $exportNat + $exportPays > 0) {
                    $this->addFlash('success', $exportVille . ' villes, ' . $exportNat . ' nationalités, ' . $exportPays . ' pays');

                    return $this->redirectToRoute('admin_parametre_pays_index');
                } else {
                    $this->addFlash('error', 'Aucune ligne exportée');
                }

                return $this->redirectToRoute('admin_parametre_pays_import');
            } else {
                $errors = $this->get('app.form_error')->all($form);
            }
        }

        return $this->render('ParametreBundle:Pays:import.html.twig', [

            'errors' => $errors,
            'form'   => $form->createView(),
        ]);
    }
}
