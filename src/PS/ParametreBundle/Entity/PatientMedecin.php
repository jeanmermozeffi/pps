<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * @ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\PatientMedecinRepository")
 * 
 */
class PatientMedecin
{
    /**
     * @Expose
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @Groups({"patient-medecin"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="PS\GestionBundle\Entity\Patient", inversedBy="medecins")
     * @Assert\NotBlank()
     */
    private $patient;

    /**
     * @Expose
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Specialite")
     * @Assert\NotBlank()
     * @Groups({"patient-medecin", "specialite"})
     */
    private $specialite;


    /**
     * @Expose
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank()
     * @Groups({"patient-medecin"})
     */
    private $medecin;

    /**
     * @Expose
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank()
     * @Groups({"patient-medecin"})
     */
    private $hopital;

    /**
     * @Expose
     * @ORM\Column(type="string",length=50)
     * @Assert\NotBlank()
     * @Groups({"patient-medecin"})
     */
    private $contact;




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
     * Set medecin
     *
     * @param string $medecin
     * @return PatientMedecin
     */
    public function setMedecin($medecin)
    {
        $this->medecin = $medecin;

        return $this;
    }

    /**
     * Get medecin
     *
     * @return string 
     */
    public function getMedecin()
    {
        return $this->medecin;
    }

    /**
     * Set hopital
     *
     * @param string $hopital
     * @return PatientMedecin
     */
    public function setHopital($hopital)
    {
        $this->hopital = $hopital;

        return $this;
    }

    /**
     * Get hopital
     *
     * @return string 
     */
    public function getHopital()
    {
        return $this->hopital;
    }

    /**
     * Set contact
     *
     * @param integer $contact
     * @return PatientMedecin
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return integer 
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\Patient $patient
     * @return PatientMedecin
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
     * Set specialite
     *
     * @param \PS\ParametreBundle\Entity\Specialite $specialite
     * @return PatientMedecin
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
}
