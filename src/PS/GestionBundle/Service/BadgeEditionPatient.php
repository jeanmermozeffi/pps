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
        // Accédez à votre paramètre
        // $bundleDir = $this->parameterBag->get('bundle_dir');

        // Utilisez $bundleDir comme nécessaire
        // $fontDir = $bundleDir . '/public/fonts/montserrat/';
        // $options['fontDir'] = $fontDir;

        $options = [];
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

        $count = count($selectedPatients);
        $received = 0;
        $initialPageCount = $mpdf->page;

        // Générez le contenu HTML pour chaque patient
        foreach ($selectedPatients as $index => $patient) {
            $mpdf->shrink_tables_to_fit = 1;
            // On stocke la vue à convertir en PDF, en n'oubliant pas les paramètres twig si la vue comporte des données dynamiques
            // if ($loader->exists($template)) {
            $vars = ['patient' => $patient];


            $mpdf->showImageErrors = true;

            $template = 'patient/badge.html.twig';
            $htmlContent = $this->twig->render($template, $vars);
            $mpdf->WriteHTML($htmlContent);

            // Ajouter une page blanche pour le verso
            ++$received;
            $numberOfPagesGenerated = $mpdf->page;
            // }

            // Ajouter une page blanche pour le verso après chaque patient
            // if ($index < $count - 1) {
            //     $mpdf->AddPage();
            // }
            $mpdf->AddPage();
        }


        if ($received >= 1) {
            $mpdf->Output('Badge_All_Selected_Patients.pdf', 'I');
        }
    }

    public function getNumberPageOfPdf($pdfContent)
    {
        $numberOfPages = 0;
        // Utilisez la méthode Output sans le paramètre 'I' pour obtenir le PDF sous forme de chaîne
        // $pdfContent = $mpdf->Output('Badge_All_Selected_Patients.pdf', 'S');

        // Obtenez le nombre de pages en utilisant une expression régulière
        if (preg_match_all('/\/Type\s*\/Page\b/', $pdfContent, $matches)) {
            $numberOfPages = count($matches[0]);
            echo 'Nombre de pages : ' . $numberOfPages;
        }

        return $numberOfPages;
    }
}
