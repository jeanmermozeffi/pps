<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\LigneAllergieRepository")
 */
class LigneAllergie
{
    /**
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="PS\GestionBundle\Entity\Patient", inversedBy="allergies")
     * @Assert\NotBlank()
     */
    private $patient;

    /**
     * @ORM\ManyToOne(targetEntity="Allergie")
     * @Assert\NotBlank()
     */
    private $allergie;

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
     * Get commentaire
     *
     * @return integer 
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

     /**
     * Set commentaire
     * @param $commentaire string
     * @return $this
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;
        return $this;
    }

   
    /**
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\Patient $patient
     * @return LigneAllergie
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
     * Set Allergie
     *
     * @param \PS\ParametreBundle\Entity\Allergie $allergie
     * @return LigneAllergie
     */
    public function setAllergie(\PS\ParametreBundle\Entity\Allergie $allergie = null)
    {
        $this->allergie = $allergie;
    
        return $this;
    }

    /**
     * Get Allergie
     *
     * @return \PS\ParametreBundle\Entity\Allergie
     */
    public function getAllergie()
    {
        return $this->allergie;
    }
}