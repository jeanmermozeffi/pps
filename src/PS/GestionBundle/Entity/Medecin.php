<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use APY\DataGridBundle\Grid\Mapping as GRID;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;


/**
 * Medecin
 * @ExclusionPolicy("all")
 * @ORM\Table(name="medecin")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\MedecinRepository")
 * @GRID\Source(columns="id, nom_complet, personne.nom, personne.prenom,personne.utilisateur.username, personne.contact, hopital.nom, contact", sortable=false)
 * @GRID\Column(id="nom_complet", type="join", title="Nom et prénoms", columns={"personne.nom", "personne.prenom"}, operatorsVisible=false)
 * @GRID\Column(id="contact", type="join", title="Contact", columns={"personne.contact"}, operatorsVisible=false)
 */
class Medecin
{
    /**
     * @var int
     * @Expose
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(title="Id", filterable=false, primary=true)
     * @Groups({"medecin"})
     */
    private $id;

    /**
     * @Expose
     * @Assert\Valid()
     * @ORM\OneToOne(targetEntity="\PS\UtilisateurBundle\Entity\Personne", cascade={"persist"}, inversedBy="medecin")
     * @GRID\Column(field="personne.nom", title="Nom et prénoms", operatorsVisible=false, joinType="inner",visible=false)
     * @GRID\Column(field="personne.prenom", operatorsVisible=false, joinType="inner",visible=false)
     * @GRID\Column(field="personne.utilisateur.username", operatorsVisible=false, joinType="inner",visible=false)
     * @GRID\Column(field="personne.contact", operatorsVisible=false, joinType="inner",visible=false)
     * @Groups({"medecin", "personne"})
     */
    private $personne;

    /**
     * @Expose
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Hopital")
     * @Assert\NotBlank()
     * @GRID\Column(field="hopital.nom", title="medecin.form.hopital", operatorsVisible=false, filter="select")
     * @Groups({"medecin", "hopital"})
     */
    private $hopital;


    /**
     * @ORM\OneToMany(targetEntity="RendezVous", mappedBy="medecin")
     */
    private $rendezvous;


     /**
     * @ORM\OneToMany(targetEntity="Consultation", mappedBy="medecin")
     */
    private $consultations;


    //private $specialites;
    //
     /**
     * @Exclude
     * @ORM\ManyToMany(targetEntity="PS\ParametreBundle\Entity\Specialite", cascade={"persist"})
     * @ORM\JoinTable(name="specialite_medecin")
     */
    private $specialites;


    
     /**
     * @var int
     *
     * @ORM\Column(name="matricule", type="string", length=20, options={"default":""}, nullable=true)
     */
    private $matricule;



    
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
     * Set personne
     *
     * @param \PS\UtilisateurBundle\Entity\Personne $personne
     * @return Medecin
     */
    public function setPersonne(\PS\UtilisateurBundle\Entity\Personne $personne = null)
    {
        $this->personne = $personne;

        return $this;
    }

    /**
     * Get personne
     *
     * @return \PS\UtilisateurBundle\Entity\Personne 
     */
    public function getPersonne()
    {
        return $this->personne;
    }

    /**
     * Set hopital
     *
     * @param \PS\ParametreBundle\Entity\Hopital $hopital
     * @return Medecin
     */
    public function setHopital(\PS\ParametreBundle\Entity\Hopital $hopital = null)
    {
        $this->hopital = $hopital;

        return $this;
    }

    /**
     * Get hopital
     *
     * @return \PS\ParametreBundle\Entity\Hopital 
     */
    public function getHopital()
    {
        return $this->hopital;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rendezvous = new \Doctrine\Common\Collections\ArrayCollection();
        $this->specialites = new \Doctrine\Common\Collections\ArrayCollection();
        $this->consultations = new \Doctrine\Common\Collections\ArrayCollection();
    }




    /**
     * Add rendezvous
     *
     * @param \PS\GestionBundle\Entity\RendezVous $rendezvous
     * @return Medecin
     */
    public function addRendezvous(\PS\GestionBundle\Entity\RendezVous $rendezvous)
    {
        $this->rendezvous[] = $rendezvous;
    
        return $this;
    }

    /**
     * Remove rendezvous
     *
     * @param \PS\GestionBundle\Entity\RendezVous $rendezvous
     */
    public function removeRendezvous(\PS\GestionBundle\Entity\RendezVous $rendezvous)
    {
        $this->rendezvous->removeElement($rendezvous);
    }

    /**
     * Get rendezvous
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRendezvous()
    {
        return $this->rendezvous;
    }



     /**
     * Add rendezvous
     *
     * @param \PS\GestionBundle\Entity\RendezVous $rendezvous
     * @return Medecin
     */
    public function addConsultation(\PS\GestionBundle\Entity\Consultation $consultation)
    {
        $this->consultations[] = $consultation;
    
        return $this;
    }

    /**
     * Remove rendezvous
     *
     * @param \PS\GestionBundle\Entity\RendezVous $rendezvous
     */
    public function removeConsutation(\PS\GestionBundle\Entity\Consultation $consultation)
    {
        $this->consultations->removeElement($consultation);
    }

    /**
     * Get rendezvous
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getConsultations()
    {
        return $this->consultations;
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
        if ($this->specialites->contains($specialite)) { 
            return; 
        }
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


    public function setSpecialites($specialites)
    {
       $oldSpecialites = $this->getSpecialites();

       
        //dump($specialites);exit;
        foreach ($specialites as $specialite) {
            $this->addSpecialite($specialite);
        }
        return $this;
    }


     /**
     * Set matricule
     *
     * @param string $matricule
     *
     * @return Medecin
     */
    public function setMatricule($matricule)
    {
        $this->matricule = $matricule;

        return $this;
    }

    /**
     * Get matricule
     *
     * @return string
     */
    public function getMatricule()
    {
        return $this->matricule;
    }

}
