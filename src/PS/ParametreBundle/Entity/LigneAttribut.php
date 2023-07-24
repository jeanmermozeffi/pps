<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Groups;


/**
 * @ORM\Entity
 */
class LigneAttribut
{
    /**
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="PS\GestionBundle\Entity\Patient", inversedBy="ligneAttributs")
     * @Assert\NotBlank()
     */
    private $patient;

    /**
     * @ORM\ManyToOne(targetEntity="Attribut")
     * @Assert\NotBlank()
     * @Groups({"patient-attribut"})
     */
    private $attribut;




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
     * @return LigneAttribut
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
     * Set Attribut
     *
     * @param \PS\ParametreBundle\Entity\Attribut $attribut
     * @return LigneAttribut
     */
    public function setAttribut(\PS\ParametreBundle\Entity\Attribut $attribut = null)
    {
        $this->attribut = $attribut;
    
        return $this;
    }

    /**
     * Get Attribut
     *
     * @return \PS\ParametreBundle\Entity\Attribut
     */
    public function getAttribut()
    {
        return $this->attribut;
    }
}