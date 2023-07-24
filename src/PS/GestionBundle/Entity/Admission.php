<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Admission
 *
 * @ORM\Table(name="admission")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\AdmissionRepository")
 * @GRID\Source(columns="id,date,patient.identifiant,patient.pin,patient_nom_complet,patient.id,patient.personne.nom,patient.personne.prenom,prestation.libelle", operatorsVisible=false, operators={"rlike"}, defaultOperator="rlike")
 * @GRID\Column(id="patient_nom_complet", type="join", title="Patient", columns={"patient.personne.nom", "patient.personne.prenom"}, operatorsVisible=false, operators={"rlike"}, defaultOperator="rlike")
 */
class Admission
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
     * @Assert\NotBlank(message="admission.form.message")
     * @GRID\Column(title="admission.form.message")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="details", type="text")
     */
    private $details;



     /**
     * @ORM\ManyToOne(targetEntity="Patient")
     * @ORM\JoinColumn(nullable=false)
     * @GRID\Column(field="patient.personne.nom", visible=false)
     * @GRID\Column(field="patient.personne.prenom", visible=false)
     * @GRID\Column(field="patient.pin", title="admission.form.pin")
     *  @GRID\Column(field="patient.identifiant", title="admission.form.pin")
     * @GRID\Column(field="patient.id", visible=false)
     */
    private $patient;


     /**
     * 
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Hopital")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hopital;


     /**
     * 
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Prestation")
     * @ORM\JoinColumn(nullable=false)
     * @GRID\Column(field="prestation.libelle", title="admission.form.prestation", filter="select", selectFrom="values")
     */
    private $prestation;


    public function __construct()
    {
        $this->setDetails('');
    }


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
     * @return Admission
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
     * @return Admission
     */
    public function setDetails(string $details)
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
     * @return Consultation
     */
    public function setPatient(Patient $patient = null)
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
     * Set hopital
     *
     * @param \PS\ParametreBundle\Entity\Hopital $hopital
     *
     * @return Consultation
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
     * Set prestation
     *
     * @param \PS\ParametreBundle\Entity\Prestation $prestation
     *
     * @return Admission
     */
    public function setPrestation(\PS\ParametreBundle\Entity\Prestation $prestation)
    {
        $this->prestation = $prestation;

        return $this;
    }

    /**
     * Get prestation
     *
     * @return \PS\ParametreBundle\Entity\Prestation
     */
    public function getPrestation()
    {
        return $this->prestation;
    }
}
