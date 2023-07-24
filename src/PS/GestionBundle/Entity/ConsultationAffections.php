<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Expose;


/**
 * @ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\ConsultationAffectionsRepository")
 */
class ConsultationAffections
{
    /**
     * @Expose
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @Groups({"consultation-affection"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="PS\GestionBundle\Entity\Consultation", inversedBy="affections")
     * @Assert\NotBlank()
     */
    private $consultation;


    /**
     * @Expose
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank()
     * @Groups({"consultation-affection"})
     */
    private $affection;

    /**
     * @Expose
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank()
     * @Groups({"consultation-affection"})
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
     * Set affection
     *
     * @param string $affection
     * @return ConsultationAffections
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
     * Set commentaire
     *
     * @param string $commentaire
     * @return ConsultationAffections
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
     * Set consultation
     *
     * @param \PS\GestionBundle\Entity\Consultation $consultation
     * @return ConsultationAffections
     */
    public function setConsultation(\PS\GestionBundle\Entity\Consultation $consultation = null)
    {
        $this->consultation = $consultation;

        return $this;
    }

    /**
     * Get consultation
     *
     * @return \PS\GestionBundle\Entity\Consultation 
     */
    public function getConsultation()
    {
        return $this->consultation;
    }
}
