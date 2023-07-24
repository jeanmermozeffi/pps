<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use APY\DataGridBundle\Grid\Mapping as GRID;
/**
 * Suivi
 *
 * @ORM\Table(name="suivi")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\SuiviRepository")
 * @GRID\Source(columns="id,date,hopital.nom,medecin_nom_complet,medecin.personne.nom,medecin.personne.prenom,patient_nom_complet,patient.personne.nom,patient.personne.prnom,affection")
 * @GRID\Column(id="patient_nom_complet", type="join", title="Patient", columns={"patient.personne.nom", "patient.personne.prenom"}, operatorsVisible=false, operators={"rlike"}, defaultOperator="rlike")
 * @GRID\Column(id="medecin_nom_complet", type="join", title="Medecin", columns={"medecin.personne.nom", "medecin.personne.prenom"}, operatorsVisible=false, filter="select", selectFrom="values")
 */
class Suivi
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(visible=false)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @GRID\Column(title="Date", inputType="date")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="details", type="text")
     * @Assert\NotBlank(message="Veuillez renseigner les détails")
     */
    private $details;



    /**
     * @var string
     *
     * @ORM\Column(name="motif", type="text")
     * @Assert\NotBlank(message="Veuillez renseigner le motif")
     */
    private $motif;





    


     /**
     * @ORM\ManyToOne(targetEntity="Patient")
     * @ORM\JoinColumn(nullable=false)
     * @GRID\Column(field="patient.personne.nom", visible=false)
     * @GRID\Column(field="patient.personne.prenom", visible=false)
     */
    private $patient;



     /**
     * @ORM\ManyToOne(targetEntity="Medecin")
     * @GRID\Column(field="medecin.personne.nom", visible=false)
     * @GRID\Column(field="medecin.personne.prenom", visible=false)
     */
    private $medecin;



     /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Hopital")
     * @ORM\JoinColumn(nullable=false)
     * @GRID\Column(field="hopital.nom")
     */
    private $hopital;




    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\PatientAffections", inversedBy="suivis")
     * @Assert\NotBlank(message="Veuillez renseigner l'affection")
     * @GRID\Column(title="Affection", field="affection.affection")
     */
    private $affection;


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
     * @return Suivi
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
     * Set details
     *
     * @param string $details
     *
     * @return Suivi
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\Patient $patient
     *
     * @return Suivi
     */
    public function setPatient(\PS\GestionBundle\Entity\Patient $patient)
    {
        $this->patient = $patient;

        return $this;
    }

    /**
     * Get patient
     *
     * @return \PS\GestionBundle\Entity\Patient
     */
    public function getPatient()
    {
        return $this->patient;
    }

    /**
     * Set medecin
     *
     * @param \PS\GestionBundle\Entity\Medecin $medecin
     *
     * @return Suivi
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
     * Set hopital
     *
     * @param \PS\ParametreBundle\Entity\Hopital $hopital
     *
     * @return Suivi
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
     * Set motif
     *
     * @param string $motif
     *
     * @return Suivi
     */
    public function setMotif($motif)
    {
        $this->motif = $motif;

        return $this;
    }

    /**
     * Get motif
     *
     * @return string
     */
    public function getMotif()
    {
        return $this->motif;
    }

    /**
     * Set affection
     *
     * @param \PS\ParametreBundle\Entity\PatientAffections $affection
     *
     * @return Suivi
     */
    public function setAffection(\PS\ParametreBundle\Entity\PatientAffections $affection = null)
    {
        $this->affection = $affection;

        return $this;
    }

    /**
     * Get affection
     *
     * @return \PS\ParametreBundle\Entity\PatientAffections
     */
    public function getAffection()
    {
        return $this->affection;
    }
}
