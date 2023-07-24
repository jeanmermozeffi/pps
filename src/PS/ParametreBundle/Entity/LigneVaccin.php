<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 */
class LigneVaccin
{
    /**
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="PS\GestionBundle\Entity\Patient", inversedBy="vaccinations")
     * @Assert\NotBlank()
     */
    private $patient;

    /**
     * @ORM\ManyToOne(targetEntity="Vaccin")
     * @Assert\NotBlank()
     */
    private $vaccin;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     */
    private $date;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     */
    private $rappel;

    public function fromArray($array)
    {
        foreach ($array as $field => $value) {
            $method = 'set'.ucfirst($field);
            if (method_exists($this, $method)) {
                $this->{$method}($value);
            }
        }
    }



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
     * Set date
     *
     * @param \DateTime $date
     * @return Ligne_vaccin
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
     * Set rappel
     *
     * @param \DateTime $rappel
     * @return Ligne_vaccin
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
     * @return Ligne_vaccin
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
     * Set vaccin
     *
     * @param \PS\ParametreBundle\Entity\Vaccin $vaccin
     * @return Ligne_vaccin
     */
    public function setVaccin(\PS\ParametreBundle\Entity\Vaccin $vaccin = null)
    {
        $this->vaccin = $vaccin;
    
        return $this;
    }

    /**
     * Get vaccin
     *
     * @return \PS\ParametreBundle\Entity\Vaccin
     */
    public function getVaccin()
    {
        return $this->vaccin;
    }
}