<?php

namespace PS\UtilisateurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\VirtualProperty;
use Symfony\Component\Validator\Constraints as Assert;
use PS\ParametreBundle\Entity\Image;

/**
 * @ExclusionPolicy("all")
 * Personne
 *
 * @ORM\Table(name="personne")
 * @ORM\Entity(repositoryClass="PS\UtilisateurBundle\Repository\PersonneRepository")
 */
class Personne
{
    /**
     * @Expose
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"personne", "associe"})
     */
    private $id;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     * @Groups({"personne"})
     * @Assert\NotBlank()
     *
     */
    private $nom;

    /**
     * @Expose
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     * @Groups({"personne"})
     */
    private $prenom;

    /**
     * @Expose
     * @ORM\Column(name="datenaissance", type="date", options={"default": "1970-01-01"}, nullable=true)
     * @Groups({"personne"})
     * @Assert\NotBlank(message="Veuillez renseigner la date de naissance")
     */
    private $datenaissance;

    /**
     * @Expose
     * @ORM\OneToOne(targetEntity="PS\ParametreBundle\Entity\Image", cascade={"persist"})
     * @Groups({"personne", "associe"})
     */
    private $photo;

    /**
     * @ORM\OneToOne(targetEntity="\PS\UtilisateurBundle\Entity\Utilisateur", cascade={"persist", "remove"}, mappedBy="personne")
     * @Groups({"personne"})
     */
    private $utilisateur;

    /**
     * @Exclude
     * @ORM\ManyToOne(targetEntity="\PS\GestionBundle\Entity\Corporate", inversedBy="personnes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $corporate;

    /**
     * @Expose
     * @ORM\Column(type="datetime")
     * @Groups({"personne"})
     */
    private $dateInscription;

    /**
     * @ORM\OneToOne(targetEntity="\PS\GestionBundle\Entity\Patient", mappedBy="personne")
     * @Groups({"associe"})
     */
    private $patient;

    /**
     * @Exclude
     * @ORM\OneToOne(targetEntity="\PS\GestionBundle\Entity\Medecin", mappedBy="personne")
     */
    private $medecin;

    /**
     * @Expose
     * @ORM\Column(type="string",length=25, nullable=true)
     * @Groups({"personne", "associe"})
     */
    private $contact;

    /**
     * @Exclude
     */
    private $nomComplet;

    /**
     * @Exclude
     * @ORM\Column(type="string", nullable=true)
     */
    private $signature;

    /**
     * @Exclude
     * @ORM\OneToMany(targetEntity="PersonneHopital",mappedBy="personne",cascade={"all"}, orphanRemoval=true))
     */
    private $personneHopital;

    /**
     * @Exclude
     */
    private $hopital;


    



    
    public function __construct()
    {
        $this->dateInscription = new \DateTime();

        if (is_null($this->datenaissance)) {
            $this->datenaissance = new \DateTime();
        }
    }

    /**
     * Get medecin
     *
     * @return \PS\GestionrBundle\Entity\Medecin
     */
    public function getMedecin()
    {
        return $this->medecin;
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
     * @return Personne
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
     * Set prenom
     *
     * @param string $prenom
     * @return Personne
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @Expose
     * @VirtualProperty
     * @Groups({"personne"})
     */
    public function getNomComplet()
    {
        return trim($this->nom . ' ' . $this->prenom);
    }

    /**
     * Set photo
     *
     * @param \stdClass $photo
     * @return Personne
     */
    public function setPhoto($photo)
    {
        if (!is_null($photo->getFile())) {
            $this->photo = $photo;
        }

        return $this;
    }

    /**
     * Get photo
     *
     * @return \stdClass
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set photo
     *
     * @param \stdClass $signature
     * @return Personne
     */
    public function setSignature($signature)
    {
        if ($signature) {
            $this->signature = $signature;
        }

        return $this;
    }

    /**
     * Get photo
     *
     * @return \stdClass
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * @return mixed
     */
    public function getAge()
    {
        $now = new \DateTime();

        $age = $this->getDatenaissance();

        if ($age != null && $age->format('Y') != '-0001') {
            $interval = date_diff($now, $age);
            return $interval->format("%Y");
        } else {
            return null;
        }

    }

    /**
     * Set datenaissance
     *
     * @param \DateTime $datenaissance
     * @return Personne
     */
    public function setDatenaissance($datenaissance)
    {

        $this->datenaissance = $datenaissance;

        return $this;
    }

    /**
     * Get datenaissance
     *
     * @return \DateTime
     */
    public function getDatenaissance()
    {
        if (!is_null($this->datenaissance) && $this->datenaissance->getTimestamp() !== false) {
            return $this->datenaissance;
        }

        return new \DateTime();
    }

    /**
     * Set utilisateur
     *
     * @param \PS\UtilisateurBundle\Entity\Utilisateur $utilisateur
     * @return Personne
     */
    public function setUtilisateur(\PS\UtilisateurBundle\Entity\Utilisateur $utilisateur = null)
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
     * Set corporate
     *
     * @param \PS\GestionBundle\Entity\Corporate $corporate
     *
     * @return Patient
     */
    public function setCorporate(\PS\GestionBundle\Entity\Corporate $corporate = null)
    {
        $this->corporate = $corporate;

        return $this;
    }

    /**
     * Get corporate
     *
     * @return \PS\GestionBundle\Entity\Corporate
     */
    public function getCorporate()
    {
        return $this->corporate;
    }

    public function getSmsContact()
    {
        if ($this->contact) {
            $contact = preg_split('/[ \/,-]/', $this->contact);
            return isset($contact[0]) ? $contact[0] : null;
        }
    }

    /**
     * Set dateInscription
     *
     * @param \DateTime $dateInscription
     *
     * @return Utilisateur
     */
    public function setDateInscription($dateInscription)
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    /**
     * Get dateInscription
     *
     * @return \DateTime
     */
    public function getDateInscription()
    {
        return $this->dateInscription;
    }

    public function getSignatureRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__ . '/../../../../data';
    }

    /**
     * Set contact
     *
     * @param string $contact
     *
     * @return Personne
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
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\Patient $patient
     *
     * @return Personne
     */
    public function setPatient(\PS\GestionBundle\Entity\Patient $patient = null)
    {
        $this->patient = $patient;

        return $this;
    }

    /**
     * Set medecin
     *
     * @param \PS\GestionBundle\Entity\Medecin $medecin
     *
     * @return Personne
     */
    public function setMedecin(\PS\GestionBundle\Entity\Medecin $medecin = null)
    {
        $this->medecin = $medecin;

        return $this;
    }

    // Important
    /**
     * @param $hopital
     * @return mixed
     */
    public function setHopital($hopital)
    {
        if (!$hopital) {
            return $this;
        }

        $personneHopital = new PersonneHopital();

        $personneHopital->setPersonne($this);
        $personneHopital->setHopital($hopital);

        $this->addPersonneHopital($personneHopital);

    }

    public function getHopital()
    {

        $hopital = [];
        foreach ($this->personneHopital as $utilisateur) {
            $hopital[] = $utilisateur->getHopital();
        }

        return current($hopital);
    }

    /**
     * Add PersonneHopital
     *
     * @param \PS\UtilisateurBundle\Entity\PersonneHopital $personneHopital
     *
     * @return Patient
     */
    public function addPersonneHopital(\PS\UtilisateurBundle\Entity\PersonneHopital $personneHopital)
    {
        $this->personneHopital[] = $personneHopital;

        return $this;
    }

    /**
     * Remove PersonneHopital
     *
     * @param \PS\UtilisateurBundle\Entity\PersonneHopital $personneHopital
     */
    public function removePersonneHopital(\PS\UtilisateurBundle\Entity\PersonneHopital $personneHopital)
    {
        $this->personneHopital->removeElement($personneHopital);
    }

    /**
     * Get PersonneHopitals
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPersonneHopital()
    {
        return $this->personneHopital;
    }

}
