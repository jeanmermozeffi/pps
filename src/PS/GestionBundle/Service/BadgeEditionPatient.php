<?php

/*
 * This file is part of the PSM package.
 *
 * (c) Jean Mermoz Effi <mangoua.effi@uvci.edu.ci>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PS\GestionBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Repository\PatientRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Environment;

/**
 * FonctionnaliteInterface is the interface implemented by all fonctionnalite.
 *
 * @author Jean Mermoz Effi <mangoua.effi@uvci.edu.ci>
 * 
 */
class BadgeEditionPatient
{

    private $twig;
    private $entityManager;
    private $parameterBag;

    public function __construct(
        Environment $twig,
        EntityManagerInterface $entityManager,
        ParameterBagInterface $parameterBag

    ) {
        $this->twig = $twig;
        $this->entityManager = $entityManager;
        $this->parameterBag = $parameterBag;
    }

    public function editedCardCard(?array $patients)
    {
    }

    public function updateCardStatut(?EditPatientCard $editPatientCard)
    {
        $editPatientCard->setStatut(true);
        $this->repoeditedCard->add($editPatientCard);
    }

    public function createNewCard(?Patient $patient): ?EditPatientCard
    {
        $editedCard = new EditPatientCard();
        $editedCard->setDateEdition(new \DateTime())
            ->setMotif(EditPatientCard::NEW_CART)
            ->setStatut(false)
            ->setPatient($patient);
        $this->repoeditedCard->add($editedCard);

        return $editedCard;
    }

    public function printCartePVC(?array $selectedPatients)
    {
        $options = [];
        // Accédez à votre paramètre
        // $bundleDir = $this->parameterBag->get('bundle_dir');

        // Utilisez $bundleDir comme nécessaire
        // $fontDir = $bundleDir . '/public/fonts/montserrat/';
        // $options['fontDir'] = $fontDir;

        $loader = $this->twig->getLoader();
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'orientation' => $options['orientation'] ?? 'P',
            'mode' => 'utf-8',
            'default_font' => 'Montserrat',
            'img_dpi' => 300,
            'dpi' => 300,
            'format' => [85, 55],
            'fontDir' => array_merge($fontDirs, [
                $options['fontDir'] ?? []
            ]),
            'fontdata' => $fontData + [
                'Montserrat' => [
                    'B' => 'Montserrat-Bold.ttf',
                    'R' => 'Montserrat-Regular.ttf',
                    'L' => 'Montserrat-Light.ttf',
                ],
            ],
        ]);

        $mpdf->shrink_tables_to_fit = 1;
        $count = count($selectedPatients);
        $received = 0;

        // Générez le contenu HTML pour chaque patient
        // $htmlContent = '';
        foreach ($selectedPatients as $patient) {
            $vars = ['patient' => $patient];
            $template = 'patient/badge.html.twig';

            //on stocke la vue à convertir en PDF, en n'oubliant pas les paramètres twig si la vue comporte des données dynamiques
            if ($loader->exists($template)) {
                $htmlContent = $this->twig->render($template, $vars);

                //writeHTML va tout simplement prendre la vue stockée dans la variable $html pour la convertir en format PDF
                $mpdf->WriteHTML($htmlContent);

                // // On verifie la taille pour ne pas ajouter une page vierge
                // if (--$count > 0) {
                //     $mpdf->AddPage();
                // }
                $mpdf->showImageErrors = true;

                ++$received;
            }
        }

        // foreach ($patients as $id) {
        //     $editedCard = $patient->getEditPatientCards()->first();


        //     if ($patient && !$editedCard) {
        //         $editedCard = $this->createNewCard($patient);
        //     }

        //     if ($patient && !$editedCard->isStatut()) {

        //         $date = $editedCard->getDateEdition()->format('d/m/Y');
        //         $hopital = $patient->getHopitals()->first();

        //         if ($hopital) {
        //             $type = $hopital->getTypeHopital();
        //             $entite = strtoupper($type->getLibelle() . ' ' . $hopital->getNomHopital());
        //         } else {
        //             $entite = 'PASS SANTE MOUSSO';
        //         }

        //         $vars = [
        //             'name' => strtoupper($patient->getNomComplet()),
        //             'identifiant' => strtoupper($patient->getIdentifiant()),
        //             'pin' => $patient->getPin(),
        //             'hopital' => $entite,
        //             'date' => $date,
        //         ];

        //         //on stocke la vue à convertir en PDF, en n'oubliant pas les paramètres twig si la vue comporte des données dynamiques
        //         if ($loader->exists('/hopital/patient/print_file/master-card.html.twig')) {
        //             $html = $this->twig->render('/hopital/patient/print_file/master-card.html.twig', [
        //                 'patient' => $vars,
        //             ]);
        //         }

        //         //writeHTML va tout simplement prendre la vue stockée dans la variable $html pour la convertir en format PDF
        //         $mpdf->WriteHTML($html);

        //         // On verifie la taille pour ne pas ajouter une page vierge
        //         if (--$count > 0) {
        //             $mpdf->AddPage();
        //         }
        //         $mpdf->showImageErrors = true;

        //         ++$received;
        //         $this->updateCardStatut($editedCard);
        //     }
        // }

        if ($received >= 1) {
            $mpdf->Output('Badge_All_Selected_Patients.pdf', 'I');
        }
    }
}
