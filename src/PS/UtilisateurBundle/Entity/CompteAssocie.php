<?php

namespace PS\UtilisateurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use APY\DataGridBundle\Grid\Mapping as GRID;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\ExclusionPolicy;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * CompteAssocie
 * @ExclusionPolicy("all")
 *
 * @ORM\Table(name="compte_associe")
 * @ORM\Entity(repositoryClass="PS\UtilisateurBundle\Repository\CompteAssocieRepository")
 * @UniqueEntity(fields={"patient", "associe"}, message="Vous avez déjà associé ce couple ID/PIN à votre compte")
 * 
 */
class CompteAssocie
{

    /**
     * @Expose
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(visible=false)
     * @Groups({"patient-associe"})
     */
    private $id;

    
    
    private $identifiant;

   
    private $pin;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="PS\GestionBundle\Entity\Patient", inversedBy="associes")
     * @ORM\JoinColumn(nullable=false)
     * 
    */
    private $patient;


     /**
     * @Expose
     * @ORM\ManyToOne(targetEntity="PS\GestionBundle\Entity\Patient", fetch="EAGER", inversedBy="parents")
     * @ORM\JoinColumn(nullable=false)
     * @GRID\Column(field="associe.identifiant", title="ID", operatorsVisible=false, operators={"eq"}, defaultOperator="eq")
     * @GRID\Column(field="associe.pin", title="PIN", operatorsVisible=false, operators={"eq"}, defaultOperator="eq")
     * @GRID\Column(field="associe.personne.nom",  title="Nom")
     * @GRID\Column(field="associe.personne.prenom", title="Prénom")
     * 
     * @GRID\Column(field="associe.id", visible=false)
     * @Groups({"patient-associe"})
     * 
    */
    private $associe;

    /**
     * @Expose
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\LienParente")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="Veuillez sélectionner le lien")
     * @GRID\Column(field="lien.libLienParente", title="Lien", operatorsVisible=false, operators={"rlike"}, defaultOperator="rlike", size="15", align="center")
     * @Groups({"lien-parente"})
     * 
     */
    private $lien;


    

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
     * Set identifiant
     *
     * @param string $identifiant
     *
     * @return CompteAssocie
     */
    public function setIdentifiant($identifiant)
    {
        $this->identifiant = $identifiant;

        return $this;
    }

    /**
     * Get identifiant
     *
     * @return string
     */
    public function getIdentifiant()
    {
        return $this->identifiant;
    }

    /**
     * Set pin
     *
     * @param string $pin
     *
     * @return CompteAssocie
     */
    public function setPin($pin)
    {
        $this->pin = $pin;

        return $this;
    }

    /**
     * Get pin
     *
     * @return string
     */
    public function getPin()
    {
        return $this->pin;
    }

    /**
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\Patient $patient
     *
     * @return CompteAssocie
     */
    public function setPatient(\PS\GestionBundle\Entity\Patient $patient)
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
     * Set lien
     *
     * @param \PS\ParametreBundle\Entity\LienParente $lien
     *
     * @return CompteAssocie
     */
    public function setLien(\PS\ParametreBundle\Entity\LienParente $lien)
    {
        $this->lien = $lien;

        return $this;
    }

    /**
     * Get lien
     *
     * @return \PS\ParametreBundle\Entity\LienParente
     */
    public function getLien()
    {
       
        return $this->lien;
    }

    

    /**
     * Set associe
     *
     * @param \PS\GestionBundle\Entity\Patient $associe
     *
     * @return CompteAssocie
     */
    public function setAssocie(\PS\GestionBundle\Entity\Patient $associe)
    {
        $this->associe = $associe;

        return $this;
    }

    /**
     * Get associe
     *
     * @return \PS\GestionBundle\Entity\Patient
     */
    public function getAssocie()
    {
        return $this->associe;
    }
}
