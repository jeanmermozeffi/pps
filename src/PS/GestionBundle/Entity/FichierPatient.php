<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FichierPatient
 *
 * @ORM\Table(name="fichier_patient")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\FichierPatientRepository")
 */
class FichierPatient
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
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="PS\GestionBundle\Entity\Patient")
     * @ORM\JoinColumn(nullable=false)
     */
    private $patient;

     /**
     * @ORM\ManyToOne(targetEntity="PS\GestionBundle\Entity\Medecin")
     * @ORM\JoinColumn(nullable=false)
     */
    private $medecin;

    /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Hopital")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hopital;


    
    /**
     * @ORM\OneToOne(targetEntity="PS\ParametreBundle\Entity\Fichier", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $fichier;


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
     * Set description
     *
     * @param string $description
     *
     * @return FichierPatient
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\Patient $patient
     *
     * @return FichierPatient
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
     * @return FichierPatient
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
     * @return FichierPatient
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
     * Set fichier
     *
     * @param \PS\ParametreBundle\Entity\Fichier $fichier
     *
     * @return FichierPatient
     */
    public function setFichier(\PS\ParametreBundle\Entity\Fichier $fichier)
    {
        if ($fichier && $fichier->getFile()) {
            $this->fichier = $fichier;

        }
        
        return $this;
    }

    /**
     * Get fichier
     *
     * @return \PS\ParametreBundle\Entity\Fichier
     */
    public function getFichier()
    {
        return $this->fichier;
    }
}
