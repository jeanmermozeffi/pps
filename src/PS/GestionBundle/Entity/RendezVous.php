<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Since;
use Symfony\Component\Validator\Constraints as Assert;
use PS\GestionBundle\Validator\Constraints as PSAssert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;


/**
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\RendezVousRepository")
 * @ExclusionPolicy("all")
 * @PSAssert\DateTimeAvailabilityConstraint()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"rendezvous"="RendezVous", "consultation"="RendezVousConsultation"})
 */
class RendezVous
{
    /**
     * @Expose
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @Groups({"rdv"})
     */
    private $id;

    /**
     * @Expose
     * @SerializedName("date")
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     * @Groups({"rdv"})
     */
    private $dateRendezVous;

    /**
     * @Expose
     * @SerializedName("libelle")
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Groups({"rdv"})
     */
    private $libRendezVous;

    /**
     * @Expose
     * @ORM\Column(type="smallint", options={"default": 0})
     * @Assert\Choice(choices={0, 1, -1, 2})
     * @SerializedName("statut")
     * @Groups({"rdv"})
     */
    private $statutRendezVous;

    /**
     * @Expose
     * @ORM\Column(type="datetime", options={"default": "0000-00-00 00:00:00"}, nullable=true)
     * @Groups({"rdv"})
     * @SerializedName("dateAnnulation")
     */
    private $dateAnnulationRendezVous = null;

    /**
     * @Expose
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"rdv"})
     * @SerializedName("motifAnnulation")
     */
    private $motifAnnulationRendezVous;

    /**
     * @ORM\ManyToOne(targetEntity="Patient", inversedBy="rendezvous")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"rdv"})
     */
    private $patient;

    /**
     * @Expose
     * @ORM\ManyToOne(targetEntity="Medecin", inversedBy="rendezvous")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"medecin"})
     * @Assert\NotBlank(message="Veuillez sélectionner un médecin")
     */
    private $medecin;
    /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Specialite")
     * @Expose
     * @Groups({"specialite"})
     */
    private $specialite;


     /**
     * @Expose
     * @Groups({"hopital"})
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Hopital")
     */
    private $hopital;


    /**
     * @Exclude
     *
     * @ORM\Column(name="type_rendez_vous", type="boolean", options={"default": 1})
     */
    private $typeRendezVous;


    const STATUS_PENDING = 0;
    const STATUS_CANCELED = -1;
    const STATUS_DONE = 1;






    /**
     * @ORM\Column(type="text")
     * @Exclude
     */
    //private $noteRendezVous;



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
     * Set dateRendezVous
     *
     * @param \DateTime $dateRendezVous
     * @return RendezVous
     */
    public function setDateRendezVous($dateRendezVous)
    {
        $this->dateRendezVous = $dateRendezVous;
        return $this;
    }

    public function __construct()
    {
        /*if (is_null($this->dateAnnulationRendezVous)) {
            $this->dateAnnulationRendezVous = '0000-00-00 00:00:00';
        }*/
    }

    /**
     * Get dateRendezVous
     *
     * @return \DateTime
     */
    public function getDateRendezVous()
    {
        return $this->dateRendezVous;
    }

    /**
     * Set heureRendezVous
     *
     * @param \DateTime $heureRendezVous
     * @return RendezVous
     */
    public function setHeureRendezVous($heureRendezVous)
    {
        $this->heureRendezVous = $heureRendezVous;

        return $this;
    }

    /**
     * Get heureRendezVous
     *
     * @return \DateTime
     */
    public function getHeureRendezVous()
    {
        return $this->heureRendezVous;
    }

    /**
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\Patient $patient
     * @return RendezVous
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
     * Set medecin
     *
     * @param \PS\GestionBundle\Entity\Medecin $medecin
     * @return RendezVous
     */
    public function setMedecin(\PS\GestionBundle\Entity\Medecin $medecin = null)
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
     * Set libRendezVous
     *
     * @param string $libRendezVous
     * @return RendezVous
     */
    public function setLibRendezVous($libRendezVous)
    {
        $this->libRendezVous = $libRendezVous;

        return $this;
    }

    /**
     * Get libRendezVous
     *
     * @return string
     */
    public function getLibRendezVous()
    {
        return $this->libRendezVous;
    }

    /**
     * Set statutRendezVous
     *
     * @param integer $statutRendezVous
     * @return RendezVous
     */
    public function setStatutRendezVous($statutRendezVous)
    {
        $this->statutRendezVous = $statutRendezVous;

        return $this;
    }

    /**
     * Get statutRendezVous
     *
     * @return integer
     */
    public function getStatutRendezVous()
    {
        return $this->statutRendezVous;
    }

    /**
     * Set dateAnnulationRendezVous
     *
     * @param \DateTime $dateAnnulationRendezVous
     * @return RendezVous
     */
    public function setDateAnnulationRendezVous($dateAnnulationRendezVous)
    {
        $this->dateAnnulationRendezVous = $dateAnnulationRendezVous;

        return $this;
    }

    /**
     * Get dateAnnulationRendezVous
     *
     * @return \DateTime
     */
    public function getDateAnnulationRendezVous()
    {
        return $this->dateAnnulationRendezVous;
    }

    /**
     * Set motifAnnulationRendezVous
     *
     * @param string $motifAnnulationRendezVous
     * @return RendezVous
     */
    public function setMotifAnnulationRendezVous($motifAnnulationRendezVous)
    {
        $this->motifAnnulationRendezVous = $motifAnnulationRendezVous;

        return $this;
    }

    /**
     * Get motifAnnulationRendezVous
     *
     * @return string
     */
    public function getMotifAnnulationRendezVous()
    {
        return $this->motifAnnulationRendezVous;
    }


    /**
     * Set noteRendezVous
     *
     * @param string $noteRendezVous
     *
     * @return RendezVous
     */
    /*public function setNoteRendezVous($noteRendezVous)
    {
        $this->noteRendezVous = $noteRendezVous;

        return $this;
    }*/

    /**
     * Get noteRendezVous
     *
     * @return string
     */
    /*public function getNoteRendezVous()
    {
        return $this->noteRendezVous;
    }*/

    /**
     * Set specialite
     *
     * @param \PS\ParametreBundle\Entity\Specialite $specialite
     *
     * @return RendezVous
     */
    public function setSpecialite(\PS\ParametreBundle\Entity\Specialite $specialite = null)
    {
        $this->specialite = $specialite;

        return $this;
    }

    /**
     * Get specialite
     *
     * @return \PS\ParametreBundle\Entity\Specialite
     */
    public function getSpecialite()
    {
        return $this->specialite;
    }



    /**
     * Set typeRendezVous
     *
     * @param boolean $typeRendezVous
     *
     * @return RendezVous
     */
    public function setTypeRendezVous($typeRendezVous)
    {
        $this->typeRendezVous = $typeRendezVous;

        return $this;
    }

    /**
     * Get typeRendezVous
     *
     * @return boolean
     */
    public function getTypeRendezVous()
    {
        return $this->typeRendezVous;
    }


    /**
     * Set hopital
     *
     * @param \PS\ParametreBundle\Entity\Hopital $hopital
     * @return RendezVous
     */
    public function setHopital(\PS\ParametreBundle\Entity\Hopital $hopital = null)
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
}
