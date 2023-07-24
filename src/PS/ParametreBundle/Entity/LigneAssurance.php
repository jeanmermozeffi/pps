<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\SerializedName;


/**
 * @ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\LigneAssuranceRepository")
 */
class LigneAssurance
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
     * @Assert\NotBlank()
     */
    private $patient;

    /**
     * @Expose
     * @ORM\ManyToOne(targetEntity="Assurance")
     * @Assert\NotBlank(message="Veuillez sélectionner une assurance")
     * @Groups({"patient-assurance"})
     */
    private $assurance;

    /**
     * @Expose
     * @ORM\Column(type="float")
     * @Assert\NotNull(message="Veuillez renseigner le taux de couverture")
     * @Groups({"patient-assurance"})
     */
    private $taux;

    /**
     * @Expose
     * @ORM\Column(type="string",length=100)
     * @Assert\NotBlank(message="Veuillez renseigner le numéro de l'assuré")
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
     * Set taux
     *
     * @param string $taux
     * @return LigneAssurance
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
     * @return LigneAssurance
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
     * @return LigneAssurance
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
     * Set assurance
     *
     * @param \PS\ParametreBundle\Entity\Assurance $assurance
     * @return LigneAssurance
     */
    public function setAssurance(\PS\ParametreBundle\Entity\Assurance $assurance = null)
    {
        $this->assurance = $assurance;

        return $this;
    }

    /**
     * Get assurance
     *
     * @return \PS\ParametreBundle\Entity\Assurance
     */
    public function getAssurance()
    {
        return $this->assurance;
    }
}
