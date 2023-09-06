<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * Urgence
 * @ExclusionPolicy("all")
 * @ORM\Table(name="urgence")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\UrgenceRepository")
 * @GRID\Source(columns="id,date,motif")
 */
class Urgence
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
     * 
     * @ORM\Column(type="string",length=25)
     * @Assert\NotBlank(message="Veuillez renseigner le contact")
     */
    private $contact;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var bool
     *
     * @ORM\Column(name="etat", type="boolean")
     */
    private $etat = false;

    /**
     * @var string
     *
     * @ORM\Column(name="motif", type="text")
     *  @Assert\NotBlank(message="Veuillez renseigner le motif")
     */
    private $motif;


     /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Ville", fetch="EAGER")
     * @Assert\NotBlank()
     * @GRID\Column(title="Ville", field="ville.nom", filter="select", operatorsVisible=false, filterable=true)
     */    
    private $ville;


    /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Pays", fetch="EAGER")
     * @Assert\NotBlank()
     * @GRID\Column(title="Pays", field="pays.nom", filter="select", operatorsVisible=false, filterable=true)
     */    
    private $pays;



    /**
     * @var string
     *
     * @ORM\Column(name="localisation", type="text")
     * @Assert\NotBlank(message="Veuillez renseigner la localisation")
     */
    private $localisation;



     /**
     * @var string
     *
     * @ORM\Column(name="information", type="text")
     */
    private $info;




    /**
     * @ORM\OneToMany(targetEntity="HistoriqueUrgence", cascade={"persist"}, mappedBy="urgence")
     * @ORM\OrderBy({"date"="DESC"})
     */
    private $historiques;



     /**
     * @ORM\ManyToOne(targetEntity="PS\UtilisateurBundle\Entity\Utilisateur")
     * @ORM\JoinColumn(nullable=false)
    */
    private $utilisateur;



     /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="decimal",scale=8, precision=10)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="decimal",scale=8, precision=10)
     */
    private $longitude;




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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Urgence
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

    /**
     * Set etat
     *
     * @param boolean $etat
     *
     * @return Urgence
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return bool
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set motif
     *
     * @param string $motif
     *
     * @return Urgence
     */
    public function setMotif($motif)
    {
        $this->motif = $motif;

        return $this;
    }

    /**
     * Get motif
     *
     * @return string
     */
    public function getMotif()
    {
        return $this->motif;
    }

   
   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->historiques = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add historique
     *
     * @param \PS\GestionBundle\Entity\HistoriqueUrgence $historique
     *
     * @return Urgence
     */
    public function addHistorique(\PS\GestionBundle\Entity\HistoriqueUrgence $historique)
    {
        $this->historiques[] = $historique;

        return $this;
    }

    /**
     * Remove historique
     *
     * @param \PS\GestionBundle\Entity\HistoriqueUrgence $historique
     */
    public function removeHistorique(\PS\GestionBundle\Entity\HistoriqueUrgence $historique)
    {
        $this->historiques->removeElement($historique);
    }

    /**
     * Get historiques
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHistoriques()
    {
        return $this->historiques;
    }

    /**
     * Set localisation
     *
     * @param string $localisation
     *
     * @return Urgence
     */
    public function setLocalisation($localisation)
    {
        $this->localisation = $localisation;

        return $this;
    }

    /**
     * Get localisation
     *
     * @return string
     */
    public function getLocalisation()
    {
        return $this->localisation;
    }

    /**
     * Set ville
     *
     * @param \PS\ParametreBundle\Entity\Ville $ville
     *
     * @return Urgence
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
     * Set pays
     *
     * @param \PS\ParametreBundle\Entity\Pays $pays
     *
     * @return Urgence
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

    /**
     * Set contact
     *
     * @param string $contact
     *
     * @return Urgence
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }


    /**
     * Set utilisateur
     *
     * @param \PS\UtilisateurBundle\Entity\Utilisateur $utilisateur
     *
     * @return HistoriqueUrgence
     */
    public function setUtilisateur(\PS\UtilisateurBundle\Entity\Utilisateur $utilisateur)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return \PS\UtilisateurBundle\Entity\Utilisateur
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * Set info
     *
     * @param string $info
     *
     * @return Urgence
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Get info
     *
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return Urgence
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return Urgence
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
}
