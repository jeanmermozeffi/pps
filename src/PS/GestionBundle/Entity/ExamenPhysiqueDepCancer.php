<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * ExamenPhysiqueDepCancer
 *
 * @ORM\Table(name="examen_physique_dep_cancer")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\ExamenPhysiqueDepCancerRepository")
 */
class ExamenPhysiqueDepCancer
{
    const EXAMENS = [
        1 => 'Masse',
        2 => 'Ecoulement Sanguin',
        3 => 'Présence ganglions',
        4 => 'Normal',
        5 => 'Retraction Mamaire',
        6 => 'Ulcération'
    ];
    
    const ANOMALIES = [1 => 'Mammographie', 'Echographie mammaire', 'Micro biopsie',
        'Cytologie du liquide mammaire', 'Prescription médicamenteuse', 'Surveillance'
    ];
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var array
     *
     * @ORM\Column(name="seinDroit", type="json_array")
     * @Assert\Count(min=1, minMessage="Veuillez sélectionner un résultat examen sein droit")
     */
    private $seinDroit = [];

    /**
     * @var array
     *
     * @ORM\Column(name="seinGauche", type="json_array")
     * @Assert\Count(min=1, minMessage="Veuillez sélectionner un résultat examen sein gauche")
     */
    private $seinGauche = [];


    /**
     * @ORM\OneToOne(targetEntity="\PS\GestionBundle\Entity\FicheDepistageCancer", mappedBy="examenPhysiqueDepCancer")
     */
    private $ficheDepistageCancer;

     /**
     * @var array
     *
     * @ORM\Column(name="anomalie", type="json_array")
     */
    private $anomalies = [];


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
     * Set seinDroit
     *
     * @param array $seinDroit
     * @return ExamenPhysiqueDepCancer
     */
    public function setSeinDroit($seinDroit)
    {
        $this->seinDroit = $seinDroit;
    
        return $this;
    }

    /**
     * Get seinDroit
     *
     * @return array 
     */
    public function getSeinDroit()
    {
        return $this->seinDroit;
    }

    /**
     * Set seinGauche
     *
     * @param array $seinGauche
     * @return ExamenPhysiqueDepCancer
     */
    public function setSeinGauche($seinGauche)
    {
        $this->seinGauche = $seinGauche;
    
        return $this;
    }

    /**
     * Get seinGauche
     *
     * @return array 
     */
    public function getSeinGauche()
    {
        return $this->seinGauche;
    }

    /**
     * Set ficheDepistageCancer
     *
     * @param \PS\GestionBundle\Entity\FicheDepistageCancer $ficheDepistageCancer
     * @return ExamenPhysiqueDepCancer
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

    /**
     * Get the value of anomalies
     *
     * @return  array
     */ 
    public function getAnomalies()
    {
        return $this->anomalies;
    }

    /**
     * Set the value of anomalies
     *
     * @param  array  $anomalies
     *
     * @return  self
     */ 
    public function setAnomalies(array $anomalies)
    {
        $this->anomalies = $anomalies;

        return $this;
    }
}
