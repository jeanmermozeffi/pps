<?php

namespace PS\ParametreBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use APY\DataGridBundle\Grid\Mapping as GRID;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\ExclusionPolicy;



/**
 * @ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\PatientVaccinRepository")
 * @GRID\Source(columns="id, vaccin, date, rappel, hopital.nom, details")
 */
class PatientVaccin
{
    /**
     * @Expose
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @Groups({"patient-vaccination"})
     * @GRID\Column(visible=false)
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="PS\GestionBundle\Entity\Patient", inversedBy="vaccinations")
     * @Assert\NotBlank()
     * 
     */
    private $patient;

    /**
     * @Expose
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank()
     * @Groups({"patient-vaccination"})
     * @GRID\Column(title="Vaccin", operatorsVisible=false, operators={"like"}, defaultOperator="like")
     */
    private $vaccin;

    /**
     * @Expose
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     * @Groups({"patient-vaccination"})
     * @GRID\Column(title="Date", inputType="date", operatorsVisible=false, operators={"like"}, defaultOperator="like")
     */
    private $date;

    /**
     * @Expose
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"patient-vaccination"})
     * @GRID\Column(title="Rappel", inputType="date", operatorsVisible=false, operators={"like"}, defaultOperator="like")
     */
    private $rappel;


    /**
     * @Expose
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"patient-vaccination"})
     * @GRID\Column(title="Détails", filterable=false)
     */
    private $details;


    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"patient-vaccin"})
     */
    private $dose;



    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"patient-vaccin"})
     */
    private $lot;



    /**
     * @ORM\ManyToOne(targetEntity="Hopital")
     * @GRID\Column(field="hopital.nom", title="Lieu", operatorsVisible=false, operators={"like"}, defaultOperator="like", inputType="select", selectFrom="source")
     */
    private $hopital;



    /**
     * @ORM\ManyToOne(targetEntity="PS\UtilisateurBundle\Entity\Personne")
     */
    private $personne;



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
     * Set vaccin
     *
     * @param string $vaccin
     * @return PatientVaccin
     */
    public function setVaccin($vaccin)
    {
        $this->vaccin = $vaccin;

        return $this;
    }

    /**
     * Get vaccin
     *
     * @return string 
     */
    public function getVaccin()
    {
        return $this->vaccin;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return PatientVaccin
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
        if ($this->date && $this->date->format('Y') != '-0001') {
            return $this->date;
        }
    }

    /**
     * Set rappel
     *
     * @param \DateTime $rappel
     * @return PatientVaccin
     */
    public function setRappel($rappel)
    {
        $this->rappel = $rappel;

        return $this;
    }

    /**
     * Get rappel
     *
     * @return \DateTime 
     */
    public function getRappel()
    {
        return $this->rappel;
    }

    /**
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\Patient $patient
     * @return PatientVaccin
     */
    public function setPatient(\PS\GestionBundle\Entity\Patient $patient = null)
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
     * Set details
     *
     * @param string $details
     *
     * @return PatientVaccin
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
     * Set hopital.
     *
     * @param \PS\ParametreBundle\Entity\Hopital|null $hopital
     *
     * @return Lignevaccin
     */
    public function setHopital(\PS\ParametreBundle\Entity\Hopital $hopital = null)
    {
        $this->hopital = $hopital;

        return $this;
    }

    /**
     * Get hopital.
     *
     * @return \PS\ParametreBundle\Entity\Hopital|null
     */
    public function getHopital()
    {
        return $this->hopital;
    }


    /**
     * Set dose
     *
     * @param string $dose
     *
     * @return PatientVaccin
     */
    public function setDose($dose)
    {
        $this->dose = $dose;

        return $this;
    }

    /**
     * Get dose
     *
     * @return string
     */
    public function getDose()
    {
        return $this->dose;
    }

    /**
     * Set lot
     *
     * @param string $lot
     *
     * @return PatientVaccin
     */
    public function setLot($lot)
    {
        $this->lot = $lot;

        return $this;
    }

    /**
     * Get lot
     *
     * @return string
     */
    public function getLot()
    {
        return $this->lot;
    }

    /**
     * Set personne
     *
     * @param \PS\UtilisateurBundle\Entity\Personne $personne
     *
     * @return PatientVaccin
     */
    public function setPersonne(\PS\UtilisateurBundle\Entity\Personne $personne = null)
    {
        $this->personne = $personne;

        return $this;
    }

    /**
     * Get personne
     *
     * @return \PS\UtilisateurBundle\Entity\Personne
     */
    public function getPersonne()
    {
        return $this->personne;
    }
}
