<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\TelephoneRepository")
 */
class Telephone
{

    const CHOICES = [
        "Epoux"
        , "Epouse"
        , "Père"
        , "Mère"
        , "Cousin"
        , "Cousine"
        , "Frère"
        , "Soeur"
        , "Belle-Soeur"
        , "Beau-Frère"
        , "Grand-mère"
        , "Grand-père"
        , "Enfant"
        , "Marraine"
        , "Parrain"
        , "N/A"
        , "Tante"
        , "Oncle"
        , "Belle-mère"
        , "Beau-Père"
        , "Petit-fils"
        , "Petite-fille"
        , "Filleul(e)"
        , "Neveu"
        , "Nièce"
        , "Employeur"
        , "Fiancé"
        , "Fiancée"
        , "Tuteur"
        , "Tutrice"
    ];
    /**
     * @Expose
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @Groups({"patient-contact"})
     */
    private $id;

    /**
     * @Expose
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank()
     * @Groups({"patient-contact"})
     */
    private $nom;

    /**
     * @Expose
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank()
     * @Groups({"patient-contact"})
     */
    private $numero;

    /**
     * @Expose
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank()
     * @Groups({"patient-contact"})
     */
    private $lien;

    /**
     * @ORM\ManyToOne(targetEntity="PS\GestionBundle\Entity\Patient", inversedBy="telephones")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\NotBlank()
     */
    private $patient;

    /**
     * @ORM\Column(type="boolean")
     */
    private $sms;


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
     * Set numero
     *
     * @param string $numero
     * @return Telephone
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\Patient $patient
     * @return Telephone
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
     * Set nom
     *
     * @param string $nom
     * @return Telephone
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set lien
     *
     * @param string $lien
     * @return Telephone
     */
    public function setLien($lien)
    {
        $this->lien = $lien;

        return $this;
    }

    /**
     * Get lien
     *
     * @return string 
     */
    public function getLien()
    {
        return $this->lien;
    }


    public function setSms(bool $sms): self
    {
        $this->sms = $sms;
        return $this;
    }

    public function getSms(): ?bool
    {
        return $this->sms;
    }
}
