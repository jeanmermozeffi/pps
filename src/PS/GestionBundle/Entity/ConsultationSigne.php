<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
/**
 * ConsultationSigne
 * @ExclusionPolicy("all")
 * @ORM\Table(name="consultation_signe")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\ConsultationSigneRepository")
 */
class ConsultationSigne
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
     * @Expose
     * @var string
     *
     * @ORM\Column(name="signe", type="text")
     * @Assert\NotBlank()
     * @Groups({"consultation-signe"})
     */
    private $signe;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=20)
     */
    private $type;



     /**
     * @ORM\ManyToOne(targetEntity="Consultation", inversedBy="signes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $consultation;



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
     * Set signe
     *
     * @param string $signe
     *
     * @return ConsultationSigne
     */
    public function setSigne($signe)
    {
        $this->signe = $signe;

        return $this;
    }

    /**
     * Get signe
     *
     * @return string
     */
    public function getSigne()
    {
        return $this->signe;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return ConsultationSigne
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
     * Set consultation.
     *
     * @param \PS\GestionBundle\Entity\Consultation $consultation
     *
     * @return ConsultationConstante
     */
    public function setConsultation(\PS\GestionBundle\Entity\Consultation $consultation)
    {
        $this->consultation = $consultation;

        return $this;
    }

    /**
     * Get consultation.
     *
     * @return \PS\GestionBundle\Entity\Consultation
     */
    public function getConsultation()
    {
        return $this->consultation;
    }
}

