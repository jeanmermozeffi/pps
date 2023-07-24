<?php

namespace PS\ParametreBundle\Controller;

use PS\GestionBundle\Service\RowAction;
use APY\DataGridBundle\Grid\Source\Entity;
use PS\GestionBundle\Form\ExportType;
use PS\ParametreBundle\Entity\Pass;
use PS\ParametreBundle\Form\PassSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use APY\DataGridBundle\Grid\Column\TextColumn;
use PS\GestionBundle\Entity\Patient;

/**
 * Pass controller.
 *
 */
class PassController extends Controller
{
    /**
     * Lists all pass entities.
     *
     */
    public function indexAction(Request $request)
    {

        $source = new Entity(Pass::class);
        $date   = $request->query->get('date');

        $source->manipulateQuery(function ($qb) {

            $user = $this->getUser();

            /*if ($user->getPersonne()->getCorporate()) {
                $qb->join('GestionBundle:PassCorporate', 'c', 'WITH', 'c.pass = _a.id');
                $qb->andWhere('c.corporate = :corporate');
                $qb->setParameter('corporate', $user->getPersonne()->getCorporate());
            }*/

            $qb->orderBy('_a.dateCreation', 'DESC');
        });


        $em = $this->getDoctrine()->getManager();



        $grid = $this->get('grid');

        $grid->setSource($source);


        $column = new TextColumn(array('id' => 'patient', 'title' => 'Patient', 'filterable' => false, 'safe' => false));

        $column->manipulateRenderCell(function ($value, $row) use ($em) {
            $patient = $em->getRepository(Patient::class)->findByPinPass($row->getField('identifiant'), $row->getField('pin'));
            if ($patient) {
                return '<a href="' . $this->generateUrl('admin_gestion_patient_info', ['id' => $patient[0]->getId()]) . '">' . $patient[0]->getPersonne()->getNomComplet() . '</a>';
            }
        });

        $grid->addColumn($column, 4);

        $grid->getColumn('actif')->manipulateRenderCell(function ($value) {
            if ($value == 'true') {
                return '<span class="label label-success">Oui</span>';
            }
            return '<span class="label label-danger">Non</span>';
        });

        $rowAction = new RowAction('Détails', 'admin_config_pass_show');
        $rowAction->manipulateRender(function ($action, $row) {
            return ['controller' => 'ParametreBundle:Pass:show', 'parameters' => ['id' => $row->getField('id')]];
        });

        $grid->addRowAction($rowAction);

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $rowAction = new RowAction('Modifier', 'admin_config_pass_edit');

            $rowAction->manipulateRender(function ($action, $row) {
                return ['controller' => 'ParametreBundle:Pass:edit', 'parameters' => ['id' => $row->getField('id')]];
            });

            $grid->addRowAction($rowAction);




            $rowAction = new RowAction('Supprimer', 'admin_config_pass_delete');
            $rowAction->manipulateRender(function ($action, $row) {
                return ['controller' => 'ParametreBundle:Pass:delete', 'parameters' => ['id' => $row->getField('id')]];
            });
            $grid->addRowAction($rowAction);
        }



        return $grid->getGridResponse('pass/grid.html.twig');
    }

    /**
     * @return mixed
     */
    private function createPassForm()
    {
        $form = $this->createForm(PassSearchType::class, [
            'action' => $this->generateUrl('admin_config_pass_index'),
            'method' => 'POST',
        ]);

        $form->add('submit', 'submit', ['label' => " "]);

        return $form;
    }

    /**
     * Creates a new pass entity.
     *
     */
    public function newAction(Request $request)
    {
        $pass     = new Pass();
        $errors   = [];
        $response = []; //ajax response
        $form     = $this->createForm('PS\ParametreBundle\Form\PassType', $pass, [
            'action' => $this->generateUrl('admin_config_pass_new'),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($pass);
                $em->flush();
                $response['success'] = 1;
                $response['message'] = 'Le pass a été crée avec succès';
            } else {
                $errors              = $this->get('app.form_error')->all($form);
                $response['success'] = 0;
                $response['message'] = $errors;
            }

            if ($request->isXmlHttpRequest()) {
                return new Response(json_encode($response));
            } elseif ($errors) {
                $this->addFlash('warning', $errors);
            }

            return $this->redirectToRoute('admin_config_pass_index');
        }

        return $this->render('pass/new.html.twig', [
            'pass' => $pass,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a pass entity.
     *
     */
    public function showAction(Pass $pass)
    {
        $showForm = $this->createForm('PS\ParametreBundle\Form\PassType', $pass);

        $deleteForm = $this->createDeleteForm($pass);

        return $this->render('pass/show.html.twig', [
            'pass'        => $pass,
            'show_form'   => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }


    public function isAssigned($pass, $em)
    {
        return $em->getRepository(Patient::class)->findByPinPass($pass->getIdentifiant(), $pass->getPin());
    }

    /**
     * Displays a form to edit an existing pass entity.
     *
     */
    public function editAction(Request $request, Pass $pass)
    {

        $editForm = $this->createForm('PS\ParametreBundle\Form\PassType', $pass, [
            'action' => $this->generateUrl('admin_config_pass_edit', ['id' => $pass->getId()]),
            'method' => 'POST',
        ]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted()) {

            if ($editForm->isValid()) {
                $this->getDoctrine()->getManager()->flush();
            } else {
                $this->addFlash('warning', $this->get('app.form_error')->all($editForm));
            }

            return $this->redirectToRoute('admin_config_pass_index');
        }

        return $this->render('pass/edit.html.twig', [
            'pass'      => $pass,
            'edit_form' => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a pass entity.
     *
     */
    public function deleteAction(Request $request, Pass $pass)
    {
        $deleteForm = $this->createDeleteForm($pass);
        $deleteForm->handleRequest($request);

        if ($deleteForm->isSubmitted() && $deleteForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($pass);
            $em->flush();
            return $this->redirectToRoute('admin_config_pass_index');
        }

        return $this->render('pass/delete.html.twig', [
            'pass' => $pass,
            'form' => $deleteForm->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function exportAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('dateCreation', TextType::class, [
                'label' => 'Date ou intervale de date', 
                'required' => true, 
                'constraints' => [new NotBlank()],
                'attr' => [
                    'placeholder' => '25-05-2022',
                ]
            ])
            ->getForm();

        $errors = [];

        $form->handleRequest($request);
        $export = 0;
        $total  = 0;
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $date      = trim($form->get('dateCreation')->getData());
                $dateRegex = '(\d{1,2})-(\d{1,2})-(\d{4})';

                if (
                    preg_match('/^' . $dateRegex . '$/', $date, $matches) ||
                    preg_match('/^' . $dateRegex . '\s' . $dateRegex . '$/', $date, $matches)
                ) {
                    if (count($matches) == 4) {
                        $firstDate = $matches[0];
                        $lastDate  = $firstDate;
                    } else {
                        list($firstDate, $lastDate) = explode(' ', $date);
                        if (new \DateTime($firstDate) > new \DateTime($lastDate)) {
                            $tmp       = $firstDate;
                            $firstDate = $lastDate;
                            $lastDate  = $tmp;
                        }
                    }

                    $firstDate = new \DateTime($firstDate);
                    $lastDate  = new \DateTime($lastDate);

                    $em   = $this->getDoctrine()->getManager();
                    $rep  = $em->getRepository(Pass::class);
                    $data = $rep->findByDate($firstDate, $lastDate);

                    if ($data) {
                        $range       = range('A', 'F');
                        $spreadsheet = $this->get('phpoffice.spreadsheet')->createSpreadSheet();
                        $sheet       = $spreadsheet->getActiveSheet();
                        $sheet->getStyle('A1:B1:C1:D1:E1:F1')->getFont()->setBold(true);
                        $headers = ['ID', 'PIN', 'PATIENT', 'VILLE', 'SEXE', 'DATE DE DEMANDE'];


                        foreach ($range as $index => $col) {
                            $sheet->getColumnDimension($col)->setAutoSize(true);
                            $sheet->setCellValue("{$col}1", $headers[$index]);
                        }

                        $index = 2;

                        foreach ($data as $row) {
                            $identifiant = $row['identifiant'];
                            $pin = $row['pin'];
                            $patient = $em->getRepository(Patient::class)->findByParam($identifiant, $pin);
                            if ($patient) {
                                $sexe = $patient->getSexe();
                                $inscription = $patient->getInscription()->getDateInscription();
                                $personne = $patient->getPersonne();
                                $ville = $patient->getVille()->getNom();
                                $nom = $personne->getNomComplet();

                                $sheet->setCellValue("A$index", $row['identifiant']);
                                $sheet->setCellValue("B$index", $row['pin']);
                                $sheet->setCellValue("C$index", $nom);
                                $sheet->setCellValue("D$index", $ville);
                                $sheet->setCellValue("E$index", $sexe);
                                $sheet->setCellValue("F$index", date_format($inscription, "d-m-Y"));

                                $index += 1;
                            }
                        }



                        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                        header('Content-Disposition: attachment;filename="liste_pass_' . date('d_m_Y') . '.xlsx"');
                        header('Cache-Control: max-age=0');
                        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                        //$writer->save('utilisateur_'.date('d_m_Y').'.xlsx');
                        $writer->save('php://output');
                        exit;
                    }
                } else {
                    $errors[] = 'Format de date invalide';
                }

                /*try {

            } catch (\Exception $e) {

            }*/
            } else {
                $errors = $this->get('app.form_error')->all($form);
            }
        }

        return $this->render('pass/export.html.twig', [

            'errors' => $errors,
            'form'   => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function importAction(Request $request)
    {
        $em     = $this->getDoctrine()->getManager();
        $rep    = $em->getRepository(Pass::class);
        $errors = [];
        $form   = $this->createForm(ExportType::class);

        $form->handleRequest($request);
        $export = 0;
        $total  = 0;
        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                //dump('mmmmm');

                $fichier = $this->get('app.psm_logo_uploader')
                    ->upload($form->get('file')->getData(), null, $path, 'pass');

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
                    $data = $loader->getActiveSheet()->toArray();

                    $_pass = [];
                    foreach ($data as $_row) {
                        $_row[0] = intval($_row[0]);
                        $_row[1] = intval($_row[1]);
                        if ($_row[0] > 0 && $_row[1] > 0) {
                            $_pass[] = ['identifiant' => $_row[0], 'pin' => $_row[1]];
                            $total += 1;
                        }
                    }

                    foreach ($_pass as $_data) {

                        if (!$rep->isIdExists($_data['identifiant'])) {
                            $pass = new Pass();
                            $pass->setIdentifiant($_data['identifiant']);
                            $pass->setPin($_data['pin']);
                            $pass->setActif(true);
                            $em->persist($pass);
                            $em->flush();
                            ++$export;
                        }
                    }
                }

                if ($export > 0) {
                    $this->addFlash('success', $export . '/' . $total . ' PASS générés avec succès');
                } else {
                    $this->addFlash('error', 'Aucune ligne exportée');
                }
                return $this->redirectToRoute('admin_config_pass_export');
            } else {
                $errors = $this->get('app.form_error')->all($form);
            }
        }

        return $this->render('pass/import.html.twig', [

            'errors' => $errors,
            'form'   => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function generateAction(Request $request)
    {
        $em     = $this->getDoctrine()->getManager();
        $rep    = $em->getRepository(Pass::class);
        $errors = [];
        $form   = $this->createFormBuilder()
            ->add('total', IntegerType::class, ['label' => 'Nombre de PASS'])
            ->add(
                'longueur',
                IntegerType::class,
                [
                    'label' => 'Taille de l\'ID/PIN (5 ou 6)',
                    'data' => 4,
                    'constraints' => [
                        new Range(['min' => 5, 'max' => 6])
                    ]
                ]
            )
            ->getForm();

        $form->handleRequest($request);
        $count = 0;
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $total = $form->get('total')->getData();
                for ($i = 0; $i < $total; $i++) {
                    if ($data = $rep->generateIdPin($form->get('longueur')->getData())) {
                        $pass = new Pass();
                        $pass->setIdentifiant($data[0]);
                        $pass->setPin($data[1]);
                        $pass->setActif(true);
                        $pass->setDateCreation(new \DateTime());
                        $em->persist($pass);
                        $em->flush();
                        ++$count;
                    }
                }

                if ($count > 0) {
                    $this->addFlash('success', $count . '/' . $total . ' PASS générés avec succès');
                    return $this->redirectToRoute('admin_config_pass_generate');
                }
            } else {
                $errors = $this->get('app.form_error')->all($form);
            }
        }

        return $this->render('pass/generate.html.twig', [

            'errors' => $errors,
            'form'   => $form->createView(),
        ]);
    }

    /**
     * Creates a form to delete a pass entity.
     *
     * @param Pass $pass The pass entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Pass $pass)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_config_pass_delete', ['id' => $pass->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
