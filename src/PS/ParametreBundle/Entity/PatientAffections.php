<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;

/**
 * @ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\PatientAffectionsRepository")
 */
class PatientAffections
{
    /**
     * @Expose
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @Groups({"patient-affection"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="PS\GestionBundle\Entity\Patient", inversedBy="affections")
     * @Assert\NotBlank()
     */
    private $patient;

    /**
     * @Expose
     * @ORM\Column(type="string",length=255)
     * @Assert\NotNull()
     * @Groups({"patient-affection"})
     */
    private $affection;

    /**
     * @Expose
     * @ORM\Column(type="string",length=255, nullable=true, options={"default": ""})
     * @Groups({"patient-affection"})
     */
    private $commentaire;



    /**
     * @Expose
     * @ORM\Column(type="boolean")
     *  @Groups({"patient-affection"})
     */
    private $visible;


    /**
     * @Expose
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"patient-affection"})
     */
    private $date;


    public function __construct()
    {
        $this->setCommentaire('');
    }


    public function fromArray($array)
    {
        foreach ($array as $field => $value) {
            $method = 'set' . ucfirst($field);
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
     * Set affection
     *
     * @param string $affection
     * @return PatientAffections
     */
    public function setAffection($affection)
    {
        $this->affection = $affection;

        return $this;
    }

    /**
     * Get affection
     *
     * @return string 
     */
    public function getAffection()
    {
        return $this->affection;
    }

    /**
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\Patient $patient
     * @return PatientAffections
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
     * @return PatientAffections
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



    /**
     * Set visible
     *
     * @param boolean $visible
     *
     * @return PatientAffections
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
     * Set date
     *
     * @param \DateTime $date
     * @return Consultation
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
}
