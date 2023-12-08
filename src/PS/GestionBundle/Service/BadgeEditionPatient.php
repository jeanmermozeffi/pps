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
        $htmlContent = ''; // Initialisez la variable en dehors de la boucle

        foreach ($selectedPatients as $patient) {
            $vars = ['patient' => $patient];
            $template = 'patient/badge.html.twig';

            // On stocke la vue à convertir en PDF, en n'oubliant pas les paramètres twig si la vue comporte des données dynamiques
            if ($loader->exists($template)) {
                $htmlContent .= $this->twig->render($template, $vars);

                // Ajouter une page blanche pour le verso
                if (++$received % 2 == 0) {
                    $mpdf->AddPage();
                }
            }
        }

        // Utilisez WriteHTML en dehors de la boucle pour convertir tout le contenu en un seul PDF
        $mpdf->WriteHTML($this->twig->render($htmlContent));

        if ($received >= 1) {
            $mpdf->Output('Badge_All_Selected_Patients.pdf', 'I');
        }
    }
}