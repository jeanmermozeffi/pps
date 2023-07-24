<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * InfoHygieneVie
 *
 * @ORM\Table(name="info_hygiene_vie")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\InfoHygieneVieRepository")
 */
class InfoHygieneVie
{
    const FREQ_ALCOOL = ['Non' => 0, 'Oui' => 1, 'Modéremment' => 2];
    const FREQ_TABAC = ['Non' => 0, 'Oui' => 1, 'Occasionnelement' => 2, '> 1 paquet/J' => 3, '< 1 paquet/J' => 4];
    const NAT_CONSOMMATION = ['Fruits' => 1, 'Légumes' => 2, 'Graisse' => 3, 'Sucréries' => 4, 'Aucune des données citées' => 0];
    const FREQ_PHYSIQUES = ['Non' => 0, 'Presque Chaque Jour' => 1, 'Plusieurs fois par semaine' => 2, 'Environ une fois/semaine' => 3, 'Jusqu\'a 3 fois par mois' => 4];
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="frequenceActPhysique", type="integer")
     * @Assert\NotBlank(message="Veuillez sélectionner une fréquence")
     */
    private $frequenceActPhysique;

    /**
     * @var array
     *
     * @ORM\Column(name="natureConsommation", type="json_array")
     * @Assert\Count(min=1, minMessage="Veuillez sélectionner au moins votre consommation")
     */
    private $natureConsommation = [];

    /**
     * @var int
     *
     * @ORM\Column(name="consommationAlcool", type="integer")
     * @Assert\NotBlank(message="Veuillez sélectionner une fréquence de consommation d'alcool")
     */
    private $consommationAlcool;

    /**
     * @var int
     *
     * @ORM\Column(name="consommationTabac", type="integer")
     * @Assert\NotBlank(message="Veuillez sélectionner une fréquence de consommation de tabac")
     */
    private $consommationTabac;

    /**
     * @ORM\OneToOne(targetEntity="\PS\GestionBundle\Entity\FicheDepistageCancer", mappedBy="infoHygieneVie")
     */
    private $ficheDepistageCancer;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set frequenceActPhysique
     *
     * @param integer $frequenceActPhysique
     * @return InfoHygieneVie
     */
    public function setFrequenceActPhysique($frequenceActPhysique)
    {
        $this->frequenceActPhysique = $frequenceActPhysique;
    
        return $this;
    }

    /**
     * Get frequenceActPhysique
     *
     * @return integer 
     */
    public function getFrequenceActPhysique()
    {
        return $this->frequenceActPhysique;
    }

    /**
     * Set natureConsommation
     *
     * @param array $natureConsommation
     * @return InfoHygieneVie
     */
    public function setNatureConsommation($natureConsommation)
    {
        $this->natureConsommation = $natureConsommation;
    
        return $this;
    }

    /**
     * Get natureConsommation
     *
     * @return array 
     */
    public function getNatureConsommation()
    {
        return $this->natureConsommation;
    }

    /**
     * Set consommationAlcool
     *
     * @param integer $consommationAlcool
     * @return InfoHygieneVie
     */
    public function setConsommationAlcool($consommationAlcool)
    {
        $this->consommationAlcool = $consommationAlcool;
    
        return $this;
    }

    /**
     * Get consommationAlcool
     *
     * @return integer 
     */
    public function getConsommationAlcool()
    {
        return $this->consommationAlcool;
    }

    /**
     * Set consommationTabac
     *
     * @param integer $consommationTabac
     * @return InfoHygieneVie
     */
    public function setConsommationTabac($consommationTabac)
    {
        $this->consommationTabac = $consommationTabac;
    
        return $this;
    }

    /**
     * Get consommationTabac
     *
     * @return integer 
     */
    public function getConsommationTabac()
    {
        return $this->consommationTabac;
    }

    /**
     * Set ficheDepistageCancer
     *
     * @param \PS\GestionBundle\Entity\FicheDepistageCancer $ficheDepistageCancer
     * @return InfoHygieneVie
     */
    public function setFicheDepistageCancer(\PS\GestionBundle\Entity\FicheDepistageCancer $ficheDepistageCancer = null)
    {
        $this->ficheDepistageCancer = $ficheDepistageCancer;
    
        return $this;
    }

    /**
     * Get ficheDepistageCancer
     *
     * @return \PS\GestionBundle\Entity\FicheDepistageCancer 
     */
    public function getFicheDepistageCancer()
    {
        return $this->ficheDepistageCancer;
    }
}
