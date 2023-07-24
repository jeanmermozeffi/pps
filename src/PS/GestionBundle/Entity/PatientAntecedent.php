<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PatientAntecedent
 *
 * @ORM\Table(name="patient_antecedent")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\PatientAntecedentRepository")
 */
class PatientAntecedent
{


    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $antecedent;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=50)
     */
    private $type;


    /**
     * @ORM\ManyToOne(targetEntity="PS\GestionBundle\Entity\Patient", inversedBy="ligneAntecedents")
     * @Assert\NotBlank()
     */
    private $patient;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lien;






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
     * Set type
     *
     * @param string $type
     *
     * @return PatientAntecedent
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set groupe
     *
     * @param string $groupe
     *
     * @return PatientAntecedent
     */
    public function setGroupe($groupe)
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * Get groupe
     *
     * @return string
     */
    public function getGroupe()
    {
        return $this->groupe;
    }

    /**
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\Patient $patient
     *
     * @return PatientAntecedent
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
     * Set antecedent
     *
     * @param string $antecedent
     *
     * @return PatientAntecedent
     */
    public function setAntecedent(string $antecedent)
    {
        $this->antecedent = $antecedent;

        return $this;
    }

    /**
     * Get antecedent
     *
     * @return string
     */
    public function getAntecedent()
    {
        return $this->antecedent;
    }

    /**
     * Set lien
     *
     * @param string $lien
     *
     * @return PatientAntecedent
     */
    public function setLien($lien)
    {
        $this->lien = (string)$lien;

        return $this;
    }

    /**
     * Get lien
     *
     * @return string
     */
    public function getLien()
    {
        return $this->lien;
    }
}
