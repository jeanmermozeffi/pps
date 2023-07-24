<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * TraitementQuestionnaire
 *
 * @ORM\Table(name="traitement_questionnaire")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\TraitementQuestionnaireRepository")
 */
class TraitementQuestionnaire
{
    const OPTIONS = [
        'Vous êtes positif, restez chez vous. Le service d\'urgence vous contactera.',
        'Vous êtes négatif, cependant prenez votre température 2 fois par jour',
        'Vous êtes négatif, respectez les consignes de sécurité'
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="info", type="text")
     * 
     */
    private $info;

     /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Hopital")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hopital;

     /**
     * @ORM\ManyToOne(targetEntity="Medecin")
     * @ORM\JoinColumn(nullable=false)
     */
    private $medecin;


     /**
     * @ORM\ManyToOne(targetEntity="PatientQuestionnaire", inversedBy="traitements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $patient;

     /**
     * @ORM\ManyToOne(targetEntity="DiagnosticQuestionnaire")
     */
    private $diagnostic;



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return TraitementQuestionnaire
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set diagnostic
     *
     * @param string $diagnostic
     *
     * @return TraitementQuestionnaire
     */
    public function setDiagnostic($diagnostic)
    {
        $this->diagnostic = $diagnostic;

        return $this;
    }

    /**
     * Get diagnostic
     *
     * @return string
     */
    public function getDiagnostic()
    {
        return $this->diagnostic;
    }

    /**
     * Set hopital
     *
     * @param \PS\ParametreBundle\Entity\Hopital $hopital
     *
     * @return TraitementQuestionnaire
     */
    public function setHopital(\PS\ParametreBundle\Entity\Hopital $hopital)
    {
        $this->hopital = $hopital;

        return $this;
    }

    /**
     * Get hopital
     *
     * @return \PS\ParametreBundle\Entity\Hopital
     */
    public function getHopital()
    {
        return $this->hopital;
    }

    /**
     * Set medecin
     *
     * @param \PS\GestionBundle\Entity\Medecin $medecin
     *
     * @return TraitementQuestionnaire
     */
    public function setMedecin(\PS\GestionBundle\Entity\Medecin $medecin)
    {
        $this->medecin = $medecin;

        return $this;
    }

    /**
     * Get medecin
     *
     * @return \PS\GestionBundle\Entity\Medecin
     */
    public function getMedecin()
    {
        return $this->medecin;
    }

    /**
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\PatientQuestionnaire $patient
     *
     * @return TraitementQuestionnaire
     */
    public function setPatient(\PS\GestionBundle\Entity\PatientQuestionnaire $patient)
    {
        $this->patient = $patient;

        return $this;
    }

    /**
     * Get patient
     *
     * @return \PS\GestionBundle\Entity\PatientQuestionnaire
     */
    public function getPatient()
    {
        return $this->patient;
    }

    /**
     * Set resultat
     *
     * @param string $resultat
     *
     * @return TraitementQuestionnaire
     */
    public function setResultat($resultat)
    {
        $this->resultat = $resultat;

        return $this;
    }

    /**
     * Get resultat
     *
     * @return string
     */
    public function getResultat()
    {
        return $this->resultat;
    }

    /**
     * Set info
     *
     * @param string $info
     *
     * @return TraitementQuestionnaire
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Get info
     *
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }
}
