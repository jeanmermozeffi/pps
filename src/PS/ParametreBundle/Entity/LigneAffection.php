<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\LigneAffectionRepository")
 */
class LigneAffection
{
    /**
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="PS\GestionBundle\Entity\Patient", inversedBy="affections")
     * @Assert\NotBlank()
     */
    private $patient;

    /**
     * @ORM\ManyToOne(targetEntity="Affection")
     * @Assert\NotBlank()
     */
    private $affection;


    /**
     * @ORM\Column(type="text")
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
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\Patient $patient
     * @return LigneAffection
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
     * Set Affection
     *
     * @param \PS\ParametreBundle\Entity\Affection $affection
     * @return LigneAffection
     */
    public function setAffection(\PS\ParametreBundle\Entity\Affection $affection = null)
    {
        $this->affection = $affection;
    
        return $this;
    }

    /**
     * Get Affection
     *
     * @return \PS\ParametreBundle\Entity\Affection
     */
    public function getAffection()
    {
        return $this->affection;
    }
}