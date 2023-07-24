<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;


/**
 * @ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\PatientAssuranceRepository")
 * 
 */
class PatientAssurance
{
    /**
     * @Expose
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @Groups({"patient-assurance"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="PS\GestionBundle\Entity\Patient", inversedBy="assurances")
     * @Assert\NotNull()
     */
    private $patient;

    /**
     * @ORM\Column(type="string",length=255)
     * @Assert\NotNull()
     * @Groups({"patient-assurance"})
     */
    private $assurance;

    /**
     * @Expose
     * @ORM\Column(type="float")
     * @Assert\NotNull(message="Veuillez renseigner le taux")
     * @Groups({"patient-assurance"})
     */
    private $taux;

    /**
     * @Expose
     * @ORM\Column(type="string",length=50)
     * @Assert\NotNull()
     * @Groups({"patient-assurance"})
     */
    private $numero;


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
     * Set assurance
     *
     * @param string $assurance
     * @return PatientAssurance
     */
    public function setAssurance($assurance)
    {
        $this->assurance = $assurance;

        return $this;
    }

    /**
     * Get assurance
     *
     * @return string 
     */
    public function getAssurance()
    {
        return $this->assurance;
    }

    /**
     * Set taux
     *
     * @param string $taux
     * @return PatientAssurance
     */
    public function setTaux($taux)
    {
        $this->taux = $taux;

        return $this;
    }

    /**
     * Get taux
     *
     * @return string 
     */
    public function getTaux()
    {
        return floatval($this->taux);
    }

    /**
     * Set numero
     *
     * @param integer $numero
     * @return PatientAssurance
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\Patient $patient
     * @return PatientAssurance
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
     * @VirtualProperty
     * @SerializedName("assurance")
     * @Expose
     * @Groups({"patient-assurance"})
     */
    public function getAssuranceId()
    {
        return $this->getAssurance()->getId();
    }
}
