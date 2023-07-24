<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;

/**
 * PatientConstante
 * @ExclusionPolicy("all")
 * @ORM\Table(name="patient_constante")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\PatientConstanteRepository")
 */
class PatientConstante
{
    /**
     * @Expose
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"patient-constante"})
     */
    private $id;

    /**
     * @Expose
     * @var string
     *
     * @ORM\Column(name="valeur", type="string", length=10)
     * @Groups({"patient-constante"})
     */
    private $valeur;


    /**
     * @Expose
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Groups({"patient-constante"})
     */
    private $date;



    /**
     * @ORM\ManyToOne(targetEntity="Patient")
     * @ORM\JoinColumn(nullable=false)
     */
    private $patient;


     /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Constante")
     * @ORM\JoinColumn(nullable=false)
     */
    private $constante;


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
     * Set valeur
     *
     * @param string $valeur
     *
     * @return PatientConstante
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;

        return $this;
    }

    /**
     * Get valeur
     *
     * @return string
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\Patient $patient
     *
     * @return PatientConstante
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
     * Set constante
     *
     * @param \PS\ParametreBundle\Entity\Constante $constante
     *
     * @return PatientConstante
     */
    public function setConstante(\PS\ParametreBundle\Entity\Constante $constante)
    {
        $this->constante = $constante;

        return $this;
    }

    /**
     * Get constante
     *
     * @return \PS\ParametreBundle\Entity\Constante
     */
    public function getConstante()
    {
        return $this->constante;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return PatientConstante
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
}
