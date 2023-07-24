<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\ExclusionPolicy;


/**
 * Alerte
 * @ExclusionPolicy("all")
 * @ORM\Table(name="alerte")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\AlerteRepository")
 */
class Alerte
{
   
    /**
     * @var int
     * @Expose
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"alerte-patient", "ligne-alerte"})
     */
    private $id;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="libelle", type="string", length=100)
     * @Assert\NotBlank(message="Veuillez renseigner le libellé")
     * @Groups({"alerte-patient", "ligne-alerte"})
     */
    private $libelle;


    /**
     * @var string
     * @Expose
     * @ORM\Column(type="text")
     * @Groups({"alerte-patient", "ligne-alerte"})
     */
    private $commentaire;



     /**
     * @ORM\ManyToOne(targetEntity="Patient", inversedBy="alertes")
     * @ORM\JoinColumn(nullable=false)
     * @GRID\Column(field="patient.personne.nom", visible=false)
     * @GRID\Column(field="patient.personne.prenom", visible=false)
     * @GRID\Column(field="patient.pin", title="PIN")
     * @GRID\Column(field="patient.identifiant", title="ID")
     * @GRID\Column(field="patient.id", visible=false)
     */
    private $patient;


     /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\TypeAlerte")
     * @ORM\JoinColumn(nullable=true)
     */
    private $type;


    /**
     * @ORM\OneToMany(targetEntity="LigneAlerte", mappedBy="alerte", cascade={"persist", "remove"})
     */
    private $lignes;




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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Alerte
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    
    /**
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\Patient $patient
     *
     * @return Alerte
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
     * Set type
     *
     * @param \PS\ParametreBundle\Entity\TypeAlerte $type
     *
     * @return Alerte
     */
    public function setType(\PS\ParametreBundle\Entity\TypeAlerte $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \PS\ParametreBundle\Entity\TypeAlerte
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return Alerte
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
     * Constructor
     */
    public function __construct()
    {
        $this->lignes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ligne
     *
     * @param \PS\GestionBundle\Entity\LigneAlerte $ligne
     *
     * @return Alerte
     */
    public function addLigne(\PS\GestionBundle\Entity\LigneAlerte $ligne)
    {
        $this->lignes[] = $ligne;
        $ligne->setAlerte($this);
        return $this;
    }

    /**
     * Remove ligne
     *
     * @param \PS\GestionBundle\Entity\LigneAlerte $ligne
     */
    public function removeLigne(\PS\GestionBundle\Entity\LigneAlerte $ligne)
    {
        $this->lignes->removeElement($ligne);
    }

    /**
     * Get lignes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLignes()
    {
        return $this->lignes;
    }
}
