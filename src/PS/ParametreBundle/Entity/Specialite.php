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
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\SpecialiteRepository")
 * @GRID\Source(columns="id,identifiant,nom", sortable=false, filterable=false)
 */
class Specialite
{
    /**
     * @Expose
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @GRID\Column(title="ID", primary=true)
     * @Groups({"specialite"})
     */
    private $id;
    
    /**
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank()
     * @GRID\Column(title="specialite.form.identifiant")
     */    
    private $identifiant;

    /**
     * @Expose
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank()
     * @GRID\Column(title="specialite.form.nom")
     * @Groups({"specialite"})
     */    
    private $nom;

    /**
     * 
     * @ORM\OneToMany(targetEntity="PS\GestionBundle\Entity\CorporateSpecialite", mappedBy="specialites", cascade={"persist"})
     */
    private $corporates;

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
     * @return Specialite
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
     * Set nom
     *
     * @param string $nom
     * @return Specialite
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
     * Constructor
     */
    public function __construct()
    {
       
        $this->corporates = new \Doctrine\Common\Collections\ArrayCollection();
    }



    /**
     * Add corporate
     *
     * @param \PS\GestionBundle\Entity\CorporateSpecialite $corporate
     *
     * @return Specialite
     */
    public function addCorporate(\PS\GestionBundle\Entity\CorporateSpecialite $corporate)
    {
        $this->corporates[] = $corporate;
        $corporate->setSpecialite($this);
        return $this;
    }

    /**
     * Remove corporate
     *
     * @param \PS\GestionBundle\Entity\CorporateSpecialite $corporate
     */
    public function removeCorporate(\PS\GestionBundle\Entity\CorporateSpecialite $corporate)
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
}
