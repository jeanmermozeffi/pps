<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Exclude;
use PS\GestionBundle\Entity\BadgeEdittion;
use Doctrine\Common\Collections\Collection;
use APY\DataGridBundle\Grid\Mapping as GRID;
use PS\ParametreBundle\Entity\LigneAttribut;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\VirtualProperty;
use Doctrine\Common\Collections\ArrayCollection;
use PS\GestionBundle\Entity\HistoriqueMessageBadge;
use Symfony\Component\Validator\Constraints as Assert;
use PS\GestionBundle\Validator\Constraints as PSAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\PatientRepository")
 * @GRID\Source(columns="id,ville.nom,personne.dateInscription,personne.nom,personne.prenom,nom_complet,personne.contact,personne.corporate.raisonSociale,identifiant,profession,societe,sexe,personne.datenaissance")
 * @GRID\Column(id="nom_complet", type="join", title="personne.nom_prenom", columns={"personne.nom", "personne.prenom"}, operatorsVisible=false)
 * @UniqueEntity("identifiant", message="patient.duplicate_id")
 * @UniqueEntity("matricule", message="patient.duplicate_matricule")
 * @PSAssert\PassConstraint
 */

class Patient
{
    /**
     * @Expose
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @GRID\Column(title="ID", primary=true, filterable=false, visible=false)
     * @Groups({"patient", "patient-associe", "info-patient"})
     */
    private $id;

    /**
     * @Expose
     * @ORM\Column(type="string",length=50, nullable=true)
     * @GRID\Column(title="patient.form.identifiant", operatorsVisible=false, operators={"rlike"}, defaultOperator="rlike", size="15", align="center")
     * @Groups({"patient", "info-patient"})
     */
    private $identifiant;

    /**
     * @Expose
     * @ORM\Column(type="string",length=255, nullable=true)
     * @GRID\Column(visible=false)
     * @Groups({"patient", "info-patient"})
     */
    private $pin;

    /**
     * @Expose
     * @ORM\Column(type="string",length=50, nullable=true)
     * @GRID\Column(visible=false)
     * @Groups({"patient"})
     */
    private $poids;


    /**
     * @Expose
     * @ORM\Column(type="string",length=25, nullable=true, unique=true)
     * @GRID\Column(title="patient.form.matricule", visible=false)
     * @Groups({"patient"})
     */
    private $matricule;

    private $email;

    /**
     * @Expose
     * @ORM\Column(type="smallint", nullable=true)
     * @GRID\Column(visible=false)
     * @Groups({"patient"})
     */
    private $taille;

    /**
     * @Expose
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\GroupeSanguin")
     * @GRID\Column(visible=false)
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid
     * @Groups({"patient", "groupe-sanguin", "info-patient"})
     */
    private $groupeSanguin;


    /**
     * @Expose
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Religion")
     * @GRID\Column(visible=false)
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid
     * @Groups({"patient"})
     */
    private $religion;

    /**
     * @Expose
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Nationalite")
     * @GRID\Column(visible=false)
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid
     * @Groups({"patient"})
     */
    private $nationalite;

    /**
     * @Expose
     * @ORM\Column(type="integer", nullable=true)
     * @GRID\Column(visible=false)
     * @Groups({"patient"})
     */
    private $nombreEnfant;

    /**
     * @Expose
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Ville", cascade={"persist"})
     * @GRID\Column(field="ville.nom", title="Ville", sortable=false,  filter="select", visible=true)
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"ville"})
     */
    private $ville;

    /**
     * @Expose
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Pays", cascade={"persist"})
     * @GRID\Column(visible=false)
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"pays"})
     */
    private $pays;

    /**
     * @Expose
     * @ORM\Column(type="string",length=255, nullable=true)
     * @GRID\Column(visible=false)
     * @Groups({"patient"})
     */
    private $adresse;

    /**
     * @Expose
     * @ORM\Column(type="string",length=255, nullable=true)
     * @GRID\Column(visible=false)
     * @Groups({"patient"})
     */
    private $lieuhabitation;

    /**
     * @Expose
     * @ORM\Column(type="string",length=255, nullable=true)
     * @GRID\Column(title="patient.form.profession", operatorsVisible=false, sortable=false, operators={"rlike"}, defaultOperator="rlike", filterable=false)
     * @Groups({"patient", "info-comp"})
     */
    private $profession;

    /**
     * @Expose
     * @ORM\Column(type="string",length=255, nullable=true)
     * @GRID\Column(title="patient.form.societe", operatorsVisible=false, sortable=false, filterable=false, operators={"rlike"}, defaultOperator="rlike")
     * @Groups({"patient"})
     */
    private $societe;

    /**
     * @ORM\OneToMany(targetEntity="PS\ParametreBundle\Entity\PatientAssurance",mappedBy="patient",cascade={"persist", "remove"}, orphanRemoval=true))
     * @GRID\Column(visible=false)
     * @Groups({"assurances", "patient"})
     */
    private $assurances;

    /**
     * @ORM\OneToMany(targetEntity="PS\ParametreBundle\Entity\LigneAttribut",mappedBy="patient",cascade={"persist", "remove"}, orphanRemoval=true))
     * @GRID\Column(visible=false)
     * @Groups({"patient"})
     */
    private $ligneAttributs;


    /**
     * @Expose
     * @SerializedName("assurances")
     * @ORM\OneToMany(targetEntity="PS\ParametreBundle\Entity\LigneAssurance",mappedBy="patient",cascade={"persist", "remove"}, orphanRemoval=true))
     * @GRID\Column(visible=false)
     * @Groups({"patient-assurance", "assurance"})
     * @Assert\Valid
     */
    private $ligneAssurances;

    /**
     * @var mixed
     */
    private $attributs;




    /**
     * @Expose
     * @ORM\OneToMany(targetEntity="PS\ParametreBundle\Entity\Telephone",mappedBy="patient",cascade={"persist", "remove"}, orphanRemoval=true)
     * @GRID\Column(visible=false)
     * @Groups({"patient-contact"})
     * @Assert\Valid()
     */
    private $telephones;

    /**
     * @Expose
     * @ORM\Column(type="string",length=1, nullable=true)
     * @Assert\Choice(choices = {"M", "F"}, message="patient.invalid_sexe")
     * @Assert\NotBlank()
     * @GRID\Column(title="patient.form.sexe.label", filter="select" , sortable=false, operatorsVisible=false)
     * @Groups({"patient", "info-comp"})
     */
    private $sexe;

    /**
     * @Expose
     * @ORM\OneToMany(targetEntity="PS\ParametreBundle\Entity\PatientAllergies",mappedBy="patient",cascade={"persist", "remove"}, orphanRemoval=true)
     * @GRID\Column(visible=false)
     * @Groups({"patient-allergie"})
     * @Assert\Valid()
     */
    private $allergies;

    /**
     * @Expose
     * @ORM\OneToMany(targetEntity="PS\ParametreBundle\Entity\PatientAffections",mappedBy="patient",cascade={"persist", "remove"}, orphanRemoval=true)
     * @GRID\Column(visible=false)
     * @Groups({"patient-affection"})
     * @Assert\Valid()
     */
    private $affections;




    /**
     * @Expose
     * @ORM\OneToMany(targetEntity="PS\ParametreBundle\Entity\PatientVaccin", mappedBy="patient", cascade={"persist", "remove"}, orphanRemoval=true)
     * @GRID\Column(visible=false)
     * @Groups({"patient-vaccination"})
     * @ORM\OrderBy({"date"="asc"})
     * @Assert\Valid()
     */
    private $vaccinations;

    /**
     * @Expose
     * @ORM\OneToMany(targetEntity="PS\ParametreBundle\Entity\PatientMedecin", mappedBy="patient", cascade={"persist", "remove"}, orphanRemoval=true)
     * @GRID\Column(visible=false)
     * @Groups({"patient-medecin"})
     * @Assert\Valid()
     */
    private $medecins;


    /**
     * @Expose
     * @ORM\OneToMany(targetEntity="PS\ParametreBundle\Entity\PatientTraitement", mappedBy="patient", cascade={"persist", "remove"}, orphanRemoval=true)
     * @GRID\Column(visible=false)
     * @Groups({"patient-traitement"})
     * @Assert\Valid()
     */
    private $traitements;

    /**
     * @Expose
     * @ORM\OneToOne(targetEntity="\PS\UtilisateurBundle\Entity\Personne", inversedBy="patient", cascade={"persist", "remove"})
     * @GRID\Column(title="personne.form.datenaissance", operatorsVisible=false, field="personne.datenaissance", type="date", filterable=false, sortable=false)
     * @GRID\Column(field="personne.nom", visible=false)
     * @GRID\Column(field="personne.prenom", visible=false)
     * @GRID\Column(field="personne.dateInscription", title="Date d'inscription", type="date", inputType="date", defaultOperator="eq", operators={"eq"})
     * @GRID\Column(field="personne.contact", title="personne.form.contact", operatorsVisible=false)
     * @GRID\Column(field="personne.corporate.raisonSociale", title="personne.form.corporate", sortable=false,  filter="select")
     * @Groups({"patient", "patient-associe", "info-patient"})
     * @Assert\Valid()
     */
    private $personne;

    /**
     * @ORM\OneToMany(targetEntity="RendezVous", mappedBy="patient")
     * @Groups({"patient"})
     */
    private $rendezvous;

    /**
     * @Exclude
     */
    private $contact;


    /**
     * @Assert\Valid()
     * @ORM\OneToMany(targetEntity="\PS\UtilisateurBundle\Entity\CompteAssocie", mappedBy="patient", cascade={"persist", "remove"}, fetch="EAGER")
     * @Groups({"associe-patient"})
     * @Assert\Valid()
     */
    private $associes;

    /**
     * @Assert\Valid()
     * @ORM\OneToMany(targetEntity="\PS\UtilisateurBundle\Entity\CompteAssocie", mappedBy="associe", cascade={"persist", "remove"}, fetch="EAGER")
     * @Groups({"associe-patient"})
     */
    private $parents;


    /**
     * @Assert\Valid()
     * @ORM\OneToMany(targetEntity="Abonnement", mappedBy="patient", cascade={"persist", "remove"})
     * @Groups({"abonnement-patient"})
     */
    private $abonnements;



    /**
     * @Exclude
     * @ORM\OneToMany(targetEntity="PatientAntecedent", mappedBy="patient",cascade={"persist", "remove"}, orphanRemoval=true)
     * @GRID\Column(visible=false)
     * @Groups({"patient-antecedent", "patient"})
     */
    private $ligneAntecedents;


    /**
     * @var mixed
     */
    private $antecedents;



    /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Region")
     * @GRID\Column(visible=false)
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"patient"})
     */
    private $region;




    /**
     * @Expose
     * @ORM\Column(type="string",length=255, nullable=true)
     * @GRID\Column(visible=false)
     * @Groups({"patient"})
     */
    private $ethnie;


    /** 
     * @Expose
     * @ORM\Column(type="text")
     * @GRID\Column(visible=false)
     * @Groups({"patient"})
     */
    private $regime;



    /**
     * @Exclude
     * @Assert\Valid()
     * @ORM\OneToMany(targetEntity="Fiche", mappedBy="patient", cascade={"persist", "remove"})
     * @Groups({"abonnement-patient"})
     */
    private $fiches;


    /**
     * @Exclude
     * @ORM\OneToOne(targetEntity="Inscription",mappedBy="patient",cascade={"persist", "remove"})
     */
    private $inscription;




    /**
     * @Expose
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Statut")
     * @GRID\Column(visible=false)
     * @Groups({"patient"})
     */
    private $statut;




    /**
     * @Exclude
     * @Assert\Valid()
     * @ORM\OneToMany(targetEntity="Suivi", mappedBy="patient", cascade={"persist", "remove"})
     * @Groups({"abonnement-patient"})
     */
    private $suivis;



    /**
     * @Exclude
     * @ORM\OneToMany(targetEntity="Consultation", mappedBy="patient", cascade={"persist", "remove"})
     * @Groups({"patient"})
     */
    private $consultations;



    /**
     * @Exclude
     * @Assert\Valid()
     * @ORM\OneToMany(targetEntity="PatientQuestionnaire", mappedBy="patient", cascade={"persist", "remove"})
     */
    private $questionnaires;

    /**
     * @ORM\OneToMany(targetEntity=BadgeEdittion::class, mappedBy="patient")
     */
    private $badgeEdittions;

    /**
     * @ORM\OneToMany(targetEntity=HistoriqueMessageBadge::class, mappedBy="patient")
     */
    private $historiqueBadges;



    /**
     * Constructor
     */
    public function __construct()
    {
        //$this->modeAccouchement = new \Doctrine\Common\Collections\ArrayCollection();
        $this->assurances      = new \Doctrine\Common\Collections\ArrayCollection();
        $this->telephones      = new \Doctrine\Common\Collections\ArrayCollection();
        $this->allergies       = new \Doctrine\Common\Collections\ArrayCollection();
        $this->traitements       = new \Doctrine\Common\Collections\ArrayCollection();
        $this->affections      = new \Doctrine\Common\Collections\ArrayCollection();
        $this->medecins        = new \Doctrine\Common\Collections\ArrayCollection();
        $this->vaccinations    = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ligneAttributs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ligneAssurances = new \Doctrine\Common\Collections\ArrayCollection();
        $this->attributs       = new \Doctrine\Common\Collections\ArrayCollection();
        $this->associes        = new \Doctrine\Common\Collections\ArrayCollection();
        $this->parents         = new \Doctrine\Common\Collections\ArrayCollection();
        $this->abonnements         = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ligneAntecedents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->antecedents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fiches = new \Doctrine\Common\Collections\ArrayCollection();
        $this->suivis      = new \Doctrine\Common\Collections\ArrayCollection();
        $this->consultations      = new \Doctrine\Common\Collections\ArrayCollection();
        $this->questionnaires         = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setRegime('');
        $this->badgeEdittions = new ArrayCollection();
        $this->historiqueBadges = new ArrayCollection();
    }


    public function getContact()
    {
        return $this->getPersonne()->getContact();
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
     * Set identifiant
     *
     * @param string $identifiant
     * @return Patient
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
     * @return Patient
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
     * Set nombreEnfant
     *
     * @param integer $nombreEnfant
     * @return Patient
     */
    public function setNombreEnfant($nombreEnfant)
    {
        $this->nombreEnfant = $nombreEnfant;

        return $this;
    }

    /**
     * Get nombreEnfant
     *
     * @return integer
     */
    public function getNombreEnfant()
    {
        return $this->nombreEnfant;
    }

    /**
     * Set contact



    /**
     * Set contact
     *
     * @param string $taille
     * @return Patient
     */
    public function setTaille($taille)
    {
        $this->taille = $taille;

        return $this;
    }

    /**
     * Get poids
     *
     * @return int
     */
    public function getTaille()
    {
        return $this->taille;
    }

    /**
     * Get poids
     *
     * @return int
     */
    public function getPoids()
    {
        return $this->poids;
    }

    /**
     * Set contact
     *
     * @param string $poids
     * @return Patient
     */
    public function setPoids($poids)
    {
        $this->poids = $poids;

        return $this;
    }

    // Important
    /**
     * @return mixed
     */
    public function getAttribut()
    {
        $attributs = new \Doctrine\Common\Collections\ArrayCollection();

        foreach ($this->ligneAttributs as $ligne) {
            $attributs[] = $ligne->getAttribut();
        }

        return $attributs;
    }

    // Important
    /**
     * @param $attributs
     */
    public function setAttribut($attributs)
    {
        foreach ($attributs as $attribut) {
            $ligne = new LigneAttribut();

            $ligne->setPatient($this);
            $ligne->setAttribut($attribut);
            //$ligne->setValeur(1);

            $this->addLigneAttribut($ligne);
        }
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     * @return Patient
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set lieuhabitation
     *
     * @param string $lieuhabitation
     * @return Patient
     */
    public function setLieuhabitation($lieuhabitation)
    {
        $this->lieuhabitation = $lieuhabitation;

        return $this;
    }

    /**
     * Get lieuhabitation
     *
     * @return string
     */
    public function getLieuhabitation()
    {
        return $this->lieuhabitation;
    }

    /**
     * Set profession
     *
     * @param string $profession
     * @return Patient
     */
    public function setProfession($profession)
    {
        $this->profession = $profession;

        return $this;
    }

    /**
     * Get profession
     *
     * @return string
     */
    public function getProfession()
    {
        return $this->profession;
    }

    /**
     * Set societe
     *
     * @param string $societe
     * @return Patient
     */
    public function setSociete($societe)
    {
        $this->societe = $societe;

        return $this;
    }

    /**
     * Get societe
     *
     * @return string
     */
    public function getSociete()
    {
        return $this->societe;
    }

    /**
     * Set sexe
     *
     * @param string $sexe
     * @return Patient
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe
     *
     * @return string
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * Set groupeSanguin
     *
     * @param \PS\ParametreBundle\Entity\GroupeSanguin $groupeSanguin
     * @return Patient
     */
    public function setGroupeSanguin(\PS\ParametreBundle\Entity\GroupeSanguin $groupeSanguin = null)
    {
        $this->groupeSanguin = $groupeSanguin;

        return $this;
    }

    /**
     * Get groupeSanguin
     *
     * @return \PS\ParametreBundle\Entity\GroupeSanguin
     */
    public function getGroupeSanguin()
    {
        return $this->groupeSanguin;
    }

    /**
     * Add modeAccouchement
     *
     * @param \PS\ParametreBundle\Entity\ModeAccouchement $modeAccouchement
     * @return Patient
     */
    public function addModeAccouchement(\PS\ParametreBundle\Entity\ModeAccouchement $modeAccouchement)
    {
        $this->modeAccouchement[] = $modeAccouchement;

        return $this;
    }

    /**
     * Remove modeAccouchement
     *
     * @param \PS\ParametreBundle\Entity\ModeAccouchement $modeAccouchement
     */
    public function removeModeAccouchement(\PS\ParametreBundle\Entity\ModeAccouchement $modeAccouchement)
    {
        $this->modeAccouchement->removeElement($modeAccouchement);
    }

    /**
     * Get modeAccouchement
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    /*public function getModeAccouchement()
    {
    return $this->modeAccouchement;
    }*/

    /**
     * Set ville
     *
     * @param \PS\ParametreBundle\Entity\Ville $ville
     * @return Patient
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
     * @return Patient
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
     * Get pays
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get pays
     *
     * @return string
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Add assurance
     *
     * @param \PS\ParametreBundle\Entity\PatientAssurance $assurance
     * @return Patient
     */
    public function addAssurance(\PS\ParametreBundle\Entity\PatientAssurance $assurance)
    {
        $this->assurances[] = $assurance;

        $assurance->setPatient($this);

        return $this;
    }

    /**
     * Remove assurance
     *
     * @param \PS\ParametreBundle\Entity\PatientAssurance $assurance
     */
    public function removeAssurance(\PS\ParametreBundle\Entity\PatientAssurance $assurance)
    {
        $this->assurances->removeElement($assurance);
    }

    /**
     * Get assurance
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAssurances()
    {
        return $this->assurances;
    }

    /**
     * Add telephone
     *
     * @param \PS\ParametreBundle\Entity\Telephone $telephone
     * @return Patient
     */
    public function addTelephone(\PS\ParametreBundle\Entity\Telephone $telephone)
    {
        $this->telephones[] = $telephone;

        $telephone->setPatient($this);

        return $this;
    }

    /**
     * Remove telephone
     *
     * @param \PS\ParametreBundle\Entity\Telephone $telephone
     */
    public function removeTelephone(\PS\ParametreBundle\Entity\Telephone $telephone)
    {
        $this->telephones->removeElement($telephone);
    }

    /**
     * Get telephone
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTelephones()
    {
        return $this->telephones;
    }

    /**
     * Add vaccination
     *
     * @param \PS\ParametreBundle\Entity\PatientVaccin $vaccination
     * @return Patient
     */
    public function addVaccination(\PS\ParametreBundle\Entity\PatientVaccin $vaccination)
    {
        $this->vaccinations[] = $vaccination;

        $vaccination->setPatient($this);

        return $this;
    }

    /**
     * Remove vaccination
     *
     * @param \PS\ParametreBundle\Entity\PatientVaccin $vaccination
     */
    public function removeVaccination(\PS\ParametreBundle\Entity\PatientVaccin $vaccination)
    {
        $this->vaccinations->removeElement($vaccination);
    }

    /**
     * Get vaccination
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVaccinations()
    {
        return $this->vaccinations;
    }

    /**
     * Remove vaccination
     *
     * @param \PS\ParametreBundle\Entity\PatientVaccin $vaccination
     */
    public function removeAttribut(\PS\ParametreBundle\Entity\LigneAttribut $attribut)
    {
        $this->attributs->removeElement($attribut);
    }

    /**
     * Get vaccination
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAttributs()
    {
        return $this->attributs;
    }

    /**
     * Set personne
     *
     * @param \PS\UtilisateurBundle\Entity\Personne $personne
     * @return Patient
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
     * Add affections
     *
     * @param \PS\ParametreBundle\Entity\PatientAffections $affections
     * @return Patient
     */
    public function addAffection(\PS\ParametreBundle\Entity\PatientAffections $affections)
    {
        $this->affections[] = $affections;

        $affections->setPatient($this);

        return $this;
    }

    /**
     * Remove affections
     *
     * @param \PS\ParametreBundle\Entity\PatientAffections $affections
     */
    public function removeAffection(\PS\ParametreBundle\Entity\PatientAffections $affections)
    {
        $this->affections->removeElement($affections);
    }

    /**
     * Get affections
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAffections()
    {
        return $this->affections;
    }

    /**
     * Add allergies
     *
     * @param \PS\ParametreBundle\Entity\Allergie $allergies
     * @return Patient
     */
    public function addAllergy(\PS\ParametreBundle\Entity\PatientAllergies $allergies)
    {
        $this->allergies[] = $allergies;

        $allergies->setPatient($this);

        return $this;
    }

    /**
     * Remove allergies
     *
     * @param \PS\ParametreBundle\Entity\PatientAllergies $allergies
     */
    public function removeAllergy(\PS\ParametreBundle\Entity\PatientAllergies $allergies)
    {
        $this->allergies->removeElement($allergies);
    }

    /**
     * Get allergies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAllergies()
    {
        return $this->allergies;
    }

    /**
     * Add rendezvous
     *
     * @param \PS\GestionBundle\Entity\RendezVous $rendezvous
     * @return Patient
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
     * Add medecins
     *
     * @param \PS\ParametreBundle\Entity\PatientMedecin $medecins
     * @return Patient
     */
    public function addMedecin(\PS\ParametreBundle\Entity\PatientMedecin $medecins)
    {
        $this->medecins[] = $medecins;
        $medecins->setPatient($this);

        return $this;
    }

    /**
     * Remove medecins
     *
     * @param \PS\ParametreBundle\Entity\PatientMedecin $medecins
     */
    public function removeMedecin(\PS\ParametreBundle\Entity\PatientMedecin $medecins)
    {
        $this->medecins->removeElement($medecins);
    }

    /**
     * Get medecins
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMedecins()
    {
        return $this->medecins;
    }



    /**
     * Set religion
     *
     * @param \PS\ParametreBundle\Entity\Religion $religion
     *
     * @return Patient
     */
    public function setReligion(\PS\ParametreBundle\Entity\Religion $religion = null)
    {
        $this->religion = $religion;

        return $this;
    }

    /**
     * Get religion
     *
     * @return \PS\ParametreBundle\Entity\Religion
     */
    public function getReligion()
    {
        return $this->religion;
    }

    /**
     * Set nationalite
     *
     * @param \PS\ParametreBundle\Entity\Nationalite $nationalite
     *
     * @return Patient
     */
    public function setNationalite(\PS\ParametreBundle\Entity\Nationalite $nationalite = null)
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    /**
     * Get nationalite
     *
     * @return \PS\ParametreBundle\Entity\Nationalite
     */
    public function getNationalite()
    {
        return $this->nationalite;
    }

    /**
     * Add traitement
     *
     * @param \PS\ParametreBundle\Entity\PatientTraitement $traitement
     *
     * @return Patient
     */
    public function addTraitement(\PS\ParametreBundle\Entity\PatientTraitement $traitement)
    {
        $this->traitements[] = $traitement;
        $traitement->setPatient($this);
        return $this;
    }

    /**
     * Remove traitement
     *
     * @param \PS\ParametreBundle\Entity\PatientTraitement $traitement
     */
    public function removeTraitement(\PS\ParametreBundle\Entity\PatientTraitement $traitement)
    {
        $this->traitements->removeElement($traitement);
    }

    /**
     * Get traitements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTraitements()
    {
        return $this->traitements;
    }







    /**
     * Add ligneAssurance
     *
     * @param \PS\ParametreBundle\Entity\LigneAssurance $ligneAssurance
     *
     * @return Patient
     */
    public function addLigneAssurance(\PS\ParametreBundle\Entity\LigneAssurance $ligneAssurance)
    {
        $this->ligneAssurances[] = $ligneAssurance;
        $ligneAssurance->setPatient($this);
        return $this;
    }

    /**
     * Remove ligneAssurance
     *
     * @param \PS\ParametreBundle\Entity\LigneAssurance $ligneAssurance
     */
    public function removeLigneAssurance(\PS\ParametreBundle\Entity\LigneAssurance $ligneAssurance)
    {
        $this->ligneAssurances->removeElement($ligneAssurance);
    }

    /**
     * Get ligneAssurances
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLigneAssurances()
    {
        return $this->ligneAssurances;
    }

    /**
     * Add ligneAttribut
     *
     * @param \PS\ParametreBundle\Entity\LigneAttribut $ligneAttribut
     *
     * @return Patient
     */
    public function addLigneAttribut(\PS\ParametreBundle\Entity\LigneAttribut $ligneAttribut)
    {
        $this->ligneAttributs[] = $ligneAttribut;

        return $this;
    }

    /**
     * Remove ligneAttribut
     *
     * @param \PS\ParametreBundle\Entity\LigneAttribut $ligneAttribut
     */
    public function removeLigneAttribut(\PS\ParametreBundle\Entity\LigneAttribut $ligneAttribut)
    {
        $this->ligneAttributs->removeElement($ligneAttribut);
    }

    /**
     * Get ligneAttributs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLigneAttributs()
    {
        return $this->ligneAttributs;
    }


    /**
     * Add assocy
     *
     * @param \PS\UtilisateurBundle\Entity\CompteAssocie $assocy
     *
     * @return Personne
     */
    public function addAssocy(\PS\UtilisateurBundle\Entity\CompteAssocie $assocy)
    {

        if (!$this->associes->contains($assocy)) {
            $this->associes[] = $assocy;
            $assocy->setPatient($this);
        }

        return $this;
    }

    /**
     * Remove assocy
     *
     * @param \PS\UtilisateurBundle\Entity\CompteAssocie $assocy
     */
    public function removeAssocy(\PS\UtilisateurBundle\Entity\CompteAssocie $assocy)
    {
        $this->associes->removeElement($assocy);
    }

    /**
     * @return mixed
     */
    public function getAssocies()
    {
        $associes = $this->associes;
        foreach ($associes as $associe) {

            if ($associe->getAssocie()) {
                $associe->setIdentifiant($associe->getAssocie()->getIdentifiant());
                $associe->setPin($associe->getAssocie()->getPin());
            }
        }

        return $associes;
    }

    /**
     * Add parent
     *
     * @param \PS\UtilisateurBundle\Entity\CompteAssocie $parent
     *
     * @return Patient
     */
    public function addParent(\PS\UtilisateurBundle\Entity\CompteAssocie $parent)
    {
        $this->parents[] = $parent;

        return $this;
    }

    /**
     * Remove parent
     *
     * @param \PS\UtilisateurBundle\Entity\CompteAssocie $parent
     */
    public function removeParent(\PS\UtilisateurBundle\Entity\CompteAssocie $parent)
    {
        $this->parents->removeElement($parent);
    }

    /**
     * Get parents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParents()
    {
        return $this->parents;
    }

    /**
     * @param Patient $patient
     * @return mixed
     */
    public function isParentOf(Patient $patient)
    {
        $result = false;
        foreach ($this->getAssocies() as $compte) {
            if ($compte->getAssocie() == $patient) {
                $result = true;
                break;
            }
        }

        return $result;
    }

    /**
     * @param Patient $patient
     * @return mixed
     */
    public function isChildOf(Patient $patient)
    {
        $result = false;
        foreach ($this->getParents() as $compte) {
            if ($compte->getPatient() == $patient) {
                $result = true;
            }
        }

        return $result;
    }



    /**
     * Add abonnement
     *
     * @param \PS\GestionBundle\Entity\Abonnement $abonnement
     *
     * @return Patient
     */
    public function addAbonnement(\PS\GestionBundle\Entity\Abonnement $abonnement)
    {
        $this->abonnements[] = $abonnement;
        $abonnement->setPatient($this);
        return $this;
    }

    /**
     * Remove abonnement
     *
     * @param \PS\GestionBundle\Entity\Abonnement $abonnement
     */
    public function removeAbonnement(\PS\GestionBundle\Entity\Abonnement $abonnement)
    {
        $this->abonnements->removeElement($abonnement);
    }

    /**
     * Get abonnements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAbonnements()
    {
        return $this->abonnements;
    }


    public function getLastPack()
    {
        $abonnements = $this->getAbonnements()->filter(function ($abonnement) {
            return $abonnement->getPack() && $abonnement->getPack()->getOrdre() != -1;
        });

        return $abonnements->last();
    }



    public function setLigneAntecedents($ligneAntecedents)
    {
        $oldSpecialites = $this->getLigneAntecedents();


        //dump($specialites);exit;
        foreach ($ligneAntecedents as $antecedent) {
            $this->addLigneAntecedent($antecedent);
        }
        return $this;
    }

    /**
     * Add ligneAntecedent
     *
     * @param \PS\GestionBundle\Entity\PatientAntecedent $ligneAntecedent
     *
     * @return Patient
     */
    public function addLigneAntecedent(\PS\GestionBundle\Entity\PatientAntecedent $ligneAntecedent)
    {
        if (!$this->ligneAntecedents->contains($ligneAntecedent)) {
            $this->ligneAntecedents[] = $ligneAntecedent;
            $ligneAntecedent->setPatient($this);
        }

        return $this;
    }

    /**
     * Remove ligneAntecedent
     *
     * @param \PS\GestionBundle\Entity\PatientAntecedent $ligneAntecedent
     */
    public function removeLigneAntecedent(\PS\GestionBundle\Entity\PatientAntecedent $ligneAntecedent)
    {
        $this->ligneAntecedents->removeElement($ligneAntecedent);
    }

    /**
     * Get ligneAntecedents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLigneAntecedents()
    {
        return $this->ligneAntecedents;
    }



    // Important
    /**
     * @return mixed
     */
    public function getAntecedents()
    {
        $antecedents = new \Doctrine\Common\Collections\ArrayCollection();

        foreach ($this->ligneAntecedents as $ligne) {
            $antecedents[] = $ligne->getAntecedent();
        }

        return $antecedents;
    }

    // Important
    /**
     * @param $attributs
     */
    public function setAntecedents($antecedents)
    {
        foreach ($antecedents as $antecedent) {
            $ligne = new PatientAntecedent();

            $ligne->setPatient($this);
            $ligne->setAntecedent($antecedent);
            //$ligne->setValeur(1);

            $this->addLigneAntecedent($ligne);
        }

        return $this;
    }



    /**
     * Add fich
     *
     * @param \PS\GestionBundle\Entity\Fiche $fich
     *
     * @return Patient
     */
    public function addFich(\PS\GestionBundle\Entity\Fiche $fich)
    {
        $this->fiches[] = $fich;

        return $this;
    }

    /**
     * Remove fich
     *
     * @param \PS\GestionBundle\Entity\Fiche $fich
     */
    public function removeFich(\PS\GestionBundle\Entity\Fiche $fich)
    {
        $this->fiches->removeElement($fich);
    }

    /**
     * Get fiches
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFiches()
    {
        return $this->fiches;
    }



    /**
     * Set region
     *
     * @param \PS\ParametreBundle\Entity\Region $region
     * @return Patient
     */
    public function setRegion(\PS\ParametreBundle\Entity\Region $region = null)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return \PS\ParametreBundle\Entity\Region
     */
    public function getRegion()
    {
        return $this->region;
    }


    /**
     * Set ethnie
     *
     * @param string $ethnie
     * @return Patient
     */
    public function setEthnie(string $ethnie)
    {
        $this->ethnie = $ethnie;

        return $this;
    }

    /**
     * Get ethnie
     *
     * @return string
     */
    public function getEthnie()
    {
        return $this->ethnie;
    }


    /**
     * Set ethnie
     *
     * @param string $ethnie
     * @return Patient
     */
    public function setMatricule(?string $matricule)
    {
        $this->matricule = $matricule;

        return $this;
    }

    /**
     * Get ethnie
     *
     * @return string
     */
    public function getMatricule()
    {
        return $this->matricule;
    }



    /**
     * Set regime
     *
     * @param string $regime
     *
     * @return Patient
     */
    public function setRegime($regime)
    {
        $this->regime = (string) $regime;

        return $this;
    }

    /**
     * Get regime
     *
     * @return string
     */
    public function getRegime()
    {
        return $this->regime;
    }


    /**
     * Add consultation
     *
     * @param \PS\GestionBundle\Entity\Consultation $consultation
     *
     * @return Patient
     */
    public function addConsultation(\PS\GestionBundle\Entity\Consultation $consultation)
    {
        $this->consultations[] = $consultation;

        return $this;
    }

    /**
     * Remove consultation
     *
     * @param \PS\GestionBundle\Entity\Consultation $consultation
     */
    public function removeConsultation(\PS\GestionBundle\Entity\Consultation $consultation)
    {
        $this->consultations->removeElement($consultation);
    }

    /**
     * Get consultations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConsultations()
    {
        return $this->consultations;
    }



    /**
     * Set statut
     *
     * @param \PS\ParametreBundle\Entity\Statut $statut
     *
     * @return Patient
     */
    public function setStatut(\PS\ParametreBundle\Entity\Statut $statut = null)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return \PS\ParametreBundle\Entity\Statut
     */
    public function getStatut()
    {
        return $this->statut;
    }


    /**
     * Add questionnaire
     *
     * @param \PS\GestionBundle\Entity\PatientQuestionnaire $questionnaire
     *
     * @return Patient
     */
    public function addQuestionnaire(\PS\GestionBundle\Entity\PatientQuestionnaire $questionnaire)
    {
        $this->questionnaires[] = $questionnaire;

        return $this;
    }

    /**
     * Remove questionnaire
     *
     * @param \PS\GestionBundle\Entity\PatientQuestionnaire $questionnaire
     */
    public function removeQuestionnaire(\PS\GestionBundle\Entity\PatientQuestionnaire $questionnaire)
    {
        $this->questionnaires->removeElement($questionnaire);
    }

    /**
     * Get questionnaires
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestionnaires()
    {
        return $this->questionnaires;
    }


    public function setInscription(Inscription $inscription)
    {
        $this->inscription = $inscription;
        $inscription->setPatient($this);
        return $this;
    }

    public function getInscription()
    {
        return $this->inscription;
    }


    /**
     * @return mixed
     */
    public function getAssociesIncluded()
    {
        $results = [];
        foreach ($this->getAssocies() as $patient) {
            $results[] = $patient->getAssocie();
        }
        $results[] = $this;
        return $results;
    }


    public function getUtilisateur()
    {
        return $this->getPersonne()->getUtilisateur();
    }

    /**
     * @return Collection<int, BadgeEdittion>
     */
    public function getBadgeEdittions(): Collection
    {
        return $this->badgeEdittions;
    }

    public function addBadgeEdittion(BadgeEdittion $badgeEdittion): self
    {
        if (!$this->badgeEdittions->contains($badgeEdittion)) {
            $this->badgeEdittions[] = $badgeEdittion;
            $badgeEdittion->setPatient($this);
        }

        return $this;
    }

    public function removeBadgeEdittion(BadgeEdittion $badgeEdittion): self
    {
        if ($this->badgeEdittions->removeElement($badgeEdittion)) {
            // set the owning side to null (unless already changed)
            if ($badgeEdittion->getPatient() === $this) {
                $badgeEdittion->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, HistoriqueMessageBadge>
     */
    public function getHistoriqueBadges(): Collection
    {
        return $this->historiqueBadges;
    }

    public function addHistoriqueBadges(HistoriqueMessageBadge $historiqueBadge): self
    {
        if (!$this->historiqueBadges->contains($historiqueBadge)) {
            $this->historiqueBadges[] = $historiqueBadge;
            $historiqueBadge->setPatient($this);
        }

        return $this;
    }

    public function removeHistoriqueBadges(HistoriqueMessageBadge $historiqueBadge): self
    {
        if ($this->historiqueBadges->removeElement($historiqueBadge)) {
            // set the owning side to null (unless already changed)
            if ($historiqueBadge->getPatient() === $this) {
                $historiqueBadge->setPatient(null);
            }
        }

        return $this;
    }
}
