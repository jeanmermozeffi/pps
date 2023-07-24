<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use APY\DataGridBundle\Grid\Mapping as GRID;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;


/**
 * @ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="\PS\ParametreBundle\Repository\HopitalRepository")
 * @GRID\Source(columns="id,nom,ville.nom", sortable=false)
 */
class Hopital
{
    /**
     * @Expose
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @GRID\Column(title="ID", primary=true, filterable=false)
     * @Groups({"hopital"})
     */
    private $id;

    /**
     * @Expose
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank()
     * @GRID\Column(title="hopital.form.nom")
     * @Groups({"hopital"})
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity="Ville", fetch="EAGER")
     * @Assert\NotBlank()
     * @GRID\Column(title="hopital.form.ville", field="ville.nom", filter="select", operatorsVisible=false, filterable=true)
     */
    private $ville;



    /**
     * @Expose
     * @ORM\ManyToOne(targetEntity="Pays", fetch="EAGER")
     * @Assert\NotBlank()
     * @GRID\Column(title="hopital.form.pays", field="pays.nom", filter="select", operatorsVisible=false, filterable=true)
     * @Groups({"hopital", "pays"})
     */
    private $pays;

    /**
     * @Expose
     * @Assert\Valid()
     * @ORM\OneToOne(targetEntity="InfoHopital", mappedBy="hopital", cascade={"persist", "remove"}, fetch="EAGER")
     * @GRID\Column(field="info.nomResponsable", title="hopital.form.info.nomResponsable")
     * @GRID\Column(field="info.emailHopital", title="hopital.form.info.emailHopital")
     * @GRID\Column(field="info.localisationHopital", title="hopital.form.info.localisationHopital")
     * @GRID\Column(field="info.contacts", title="hopital.form.info.contacts")
     * @GRID\Column(field="info.fax", title="hopital.form.info.fax")
     * @Grid\Column(field="info.pointVente", title="hopital.form.info.pointVente")
     * @Groups({"hopital", "info-hopital"})
     * 
     */
    private $info;


    /**
     * @Exclude
     * @ORM\OneToMany(targetEntity="PS\GestionBundle\Entity\CorporateHopital", mappedBy="hopital", cascade={"persist"})
     */
    private $corporates;



    /**
     * @Expose
     * @ORM\ManyToMany(targetEntity="Specialite", cascade={"persist"})
     * @ORM\JoinTable(name="specialite_hopital")
     * @Groups({"specialite-hopital", "specialite"})
     */
    private $specialites;


    /**
     * @Exclude
     * @ORM\ManyToMany(targetEntity="Assurance", cascade={"persist"})
     * @ORM\JoinTable(name="assurance_hopital")
     */
    private $assurances;


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
     * Set nom
     *
     * @param string $nom
     * @return Hopital
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
     * Set ville
     *
     * @param \PS\ParametreBundle\Entity\Ville $ville
     * @return Hopital
     */
    public function setVille(\PS\ParametreBundle\Entity\Ville $ville = null)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return \PS\ParametreBundle\Entity\Ville
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set info
     *
     * @param \PS\ParametreBundle\Entity\InfoHopital $info
     *
     * @return Hopital
     */
    public function setInfo(\PS\ParametreBundle\Entity\InfoHopital $info = null)
    {
        $this->info = $info;
        $info->setHopital($this);

        return $this;
    }

    /**
     * Get info
     *
     * @return \PS\ParametreBundle\Entity\InfoHopital
     */
    public function getInfo()
    {
        return $this->info;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->corporates = new \Doctrine\Common\Collections\ArrayCollection();
        $this->specialites = new \Doctrine\Common\Collections\ArrayCollection();
        $this->assurances = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add corporate
     *
     * @param \PS\GestionBundle\Entity\CorporateHopital $corporate
     *
     * @return Hopital
     */
    public function addCorporate(\PS\GestionBundle\Entity\CorporateHopital $corporate)
    {
        $this->corporates[] = $corporate;
        $corporate->setHopital($this);
        return $this;
    }

    /**
     * Remove corporate
     *
     * @param \PS\GestionBundle\Entity\CorporateHopital $corporate
     */
    public function removeCorporate(\PS\GestionBundle\Entity\CorporateHopital $corporate)
    {
        $this->corporates->removeElement($corporate);
    }

    /**
     * Get corporates
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCorporates()
    {
        return $this->corporates;
    }

    /**
     * Add specialite
     *
     * @param \PS\ParametreBundle\Entity\Specialite $specialite
     *
     * @return Hopital
     */
    public function addSpecialite(\PS\ParametreBundle\Entity\Specialite $specialite)
    {
        $this->specialites[] = $specialite;

        return $this;
    }

    /**
     * Remove specialite
     *
     * @param \PS\ParametreBundle\Entity\Specialite $specialite
     */
    public function removeSpecialite(\PS\ParametreBundle\Entity\Specialite $specialite)
    {
        $this->specialites->removeElement($specialite);
    }

    /**
     * Get specialites
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSpecialites()
    {
        return $this->specialites;
    }

    /**
     * Add assurance
     *
     * @param \PS\ParametreBundle\Entity\Assurance $assurance
     *
     * @return Hopital
     */
    public function addAssurance(\PS\ParametreBundle\Entity\Assurance $assurance)
    {
        $this->assurances[] = $assurance;

        return $this;
    }

    /**
     * Remove assurance
     *
     * @param \PS\ParametreBundle\Entity\Assurance $assurance
     */
    public function removeAssurance(\PS\ParametreBundle\Entity\Assurance $assurance)
    {
        $this->assurances->removeElement($assurance);
    }

    /**
     * Get assurances
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAssurances()
    {
        return $this->assurances;
    }

    /**
     * Set pays
     *
     * @param \PS\ParametreBundle\Entity\Pays $pays
     *
     * @return Hopital
     */
    public function setPays(\PS\ParametreBundle\Entity\Pays $pays = null)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return \PS\ParametreBundle\Entity\Pays
     */
    public function getPays()
    {
        return $this->pays;
    }
}
