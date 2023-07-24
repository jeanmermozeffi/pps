<?php

namespace PS\ParametreBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;

/**
 * PatientTraitement
 * @ExclusionPolicy("all")
 * @ORM\Table(name="patient_traitement")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\PatientTraitementRepository")
 */
class PatientTraitement
{
    /**
     * @Expose
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"patient-traitement"})
     */
    private $id;

    /**
     * @SerializedName("libelle")
     * @Expose
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="lib_traitement", type="string", length=200)
     * @Groups({"patient-traitement"})
     */
    private $libelle;


    /**
     * @ORM\ManyToOne(targetEntity="PS\GestionBundle\Entity\Patient", inversedBy="traitements")
     * @Assert\NotBlank()
     */
    private $patient;




    /**
     * @Expose
     * @var DateTime
     * @ORM\Column(name="date_debut", type="date", nullable=true)
     * @Groups({"patient-traitement"})
     */
    private $dateDebut;


    /**
     * @Expose
     * @var DateTime
     * @ORM\Column(name="date_fin", type="date", nullable=true)
     * @Groups({"patient-traitement"})
     */
    private $dateFin;


    /**
     * @Expose
     * @ORM\Column(type="boolean")
     * @Groups({"patient-traitement"})
     */
    private $visible;


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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return PatientTraitement
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

   

    /**
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\Patient $patient
     *
     * @return PatientTraitement
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
     * Set visible
     *
     * @param boolean $visible
     *
     * @return PatientTraitement
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return PatientTraitement
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return PatientTraitement
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }
}
