<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;


/**
 * @ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\PatientAllergiesRepository")
 */
class PatientAllergies
{
    /**
     * @Expose
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @Groups({"patient-allergie"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="PS\GestionBundle\Entity\Patient", inversedBy="allergies")
     * @Assert\NotBlank()
     */
    private $patient;

    /**
     * @Expose
     * @ORM\Column(type="string",length=255)
     * @Assert\NotNull()
     * @Groups({"patient-allergie"})
     */
    private $allergie;

    /**
     * @Expose
     * @ORM\Column(type="string",length=255, nullable=true, options={"default": ""})
     * @Groups({"patient-allergie"})
     */
    private $commentaire;

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
     * Set allergie
     *
     * @param string $allergie
     * @return PatientAllergies
     */
    public function setAllergie($allergie)
    {
        $this->allergie = $allergie;

        return $this;
    }

    /**
     * Get allergie
     *
     * @return string 
     */
    public function getAllergie()
    {
        return $this->allergie;
    }

    /**
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\Patient $patient
     * @return PatientAllergies
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
     * Set commentaire
     *
     * @param string $commentaire
     * @return PatientAllergies
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string 
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }
}
