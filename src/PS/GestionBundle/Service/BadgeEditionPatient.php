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
            'mirrorMargins' => true,
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
        $initialPageCount = $mpdf->page;
        // $finalPageCount

        // Générez le contenu HTML pour chaque patient
        $htmlContent = ''; // Initialisez la variable en dehors de la boucle

        foreach ($selectedPatients as $patient) {
            $vars = ['patient' => $patient];
            $template = 'patient/badge.html.twig';
            $templateResto = 'patient/badge_resto.html.twig';
            $templateVerso = 'patient/badge_verso.html.twig';

            // On stocke la vue à convertir en PDF, en n'oubliant pas les paramètres twig si la vue comporte des données dynamiques
            if ($loader->exists($templateResto)) {
                $html = $this->twig->render($templateResto, $vars);

                $mpdf->WriteHTML($html);
                
                if (--$count > 0) {
                    // $mpdf->AddPage();
                }

                $mpdf->WriteHTML($templateVerso);

                $mpdf->showImageErrors = true;

                // Ajouter une page blanche pour le verso
                ++$received;
                $numberOfPagesGenerated = $mpdf->page;
            }
        }

        // Utilisez WriteHTML en dehors de la boucle pour convertir tout le contenu en un seul PDF
        // $mpdf->WriteHTML($htmlContent);

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
