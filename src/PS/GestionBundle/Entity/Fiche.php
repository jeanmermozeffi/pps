<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Fiche
 *
 * @ORM\Table(name="fiche")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\FicheRepository")
 * @GRID\Source(columns="id,reference,date,nom_personne,personne.personne.nom,personne.personne.prenom, patient.personne.nom,patient.personne.prenom,nom_complet")
 * @GRID\Column(id="nom_complet", type="join", title="Patient", columns={"patient.personne.nom", "patient.personne.prenom"}, operatorsVisible=false)
 * @GRID\Column(id="nom_personne", type="join", title="Personne/Infirmier", columns={"personne.nom", "personne.prenom"}, operatorsVisible=false)
 */
class Fiche
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(visible=false)
     */
    private $id;


     /**
     * @ORM\ManyToOne(targetEntity="Patient", inversedBy="fiches")
     * @ORM\JoinColumn(nullable=false)
     * @GRID\Column(field="patient.personne.nom", visible=false)
     * @GRID\Column(field="patient.personne.prenom", visible=false)
     * @GRID\Column(field="patient.personne.contact", title="Contact", visible=false)
     */
    private $patient;

    /**
     * @ORM\Column(type="datetime")
     * @GRID\Column(title="Date")
     */
    private $date;

     /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Hopital")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hopital;


     /**
     * @ORM\ManyToOne(targetEntity="PS\UtilisateurBundle\Entity\Personne")
     * @GRID\Column(field="personne.nom", visible=false)
     * @GRID\Column(field="personne.prenom", visible=false)
     */
    private $personne;



    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @GRID\Column(title="N° Fiche")
     */
    private $reference;



    /**
     * @var int
     *
     * @ORM\Column(name="cpn", type="smallint")
     */
    private $cpn;

    /**
     * @var string
     *
     * @ORM\Column(name="gestite", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $gestite;

    /**
     * @var string
     *
     * @ORM\Column(name="parite", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $parite;


     /**
     * @var string
     *
     * @ORM\Column(type="boolean")
     */
    private $hgpo;


    /**
     * @ORM\Column(type="text")
    */
    private $traitement;


     /**
     * @ORM\Column(type="text", name="compte_rendu", nullable=true)
    */
    private $compteRendu;


     /**
     * @ORM\Column(type="text", name="info_hgpo")
    */
    private $infoHgpo;



     /**
     * @var string
     *
     * @ORM\Column(name="type_hgpo", type="string", length=255, nullable=true)
     */
    private $typeHgpo;

    /**
     * @var string
     *
     * @ORM\Column(name="age_gestationnel", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $ageGestationnel;


    /**
     * @ORM\OneToMany(targetEntity="FicheAntecedent", mappedBy="fiche", cascade={"persist", "remove"})
    */
    private $antecedents;


    /**
     * @ORM\OneToMany(targetEntity="FicheAnalyse", mappedBy="fiche", cascade={"persist", "remove"})
    */
    private $analyses;



    /**
     * @ORM\OneToMany(targetEntity="FicheConstante", mappedBy="fiche", cascade={"persist", "remove"})
    */
    private $constantes;



     /**
     * @ORM\OneToMany(targetEntity="FicheGlycemie", mappedBy="fiche", cascade={"persist", "remove"})
    */
    private $glycemies;




    /**
     * @ORM\OneToMany(targetEntity="FicheComplication", mappedBy="fiche", cascade={"persist", "remove"})
    */
    private $complications;




     /**
     * @ORM\OneToMany(targetEntity="FicheGc", mappedBy="fiche", cascade={"persist", "remove"})
    */
    private $gcs;


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
     * Set cpn
     *
     * @param integer $cpn
     *
     * @return Fiche
     */
    public function setCpn($cpn)
    {
        $this->cpn = $cpn;

        return $this;
    }

    /**
     * Get cpn
     *
     * @return int
     */
    public function getCpn()
    {
        return $this->cpn;
    }

    /**
     * Set gestite
     *
     * @param string $gestite
     *
     * @return Fiche
     */
    public function setGestite($gestite)
    {
        $this->gestite = $gestite;

        return $this;
    }

    /**
     * Get gestite
     *
     * @return string
     */
    public function getGestite()
    {
        return $this->gestite;
    }

    /**
     * Set parite
     *
     * @param string $parite
     *
     * @return Fiche
     */
    public function setParite($parite)
    {
        $this->parite = $parite;

        return $this;
    }

    /**
     * Get parite
     *
     * @return string
     */
    public function getParite()
    {
        return $this->parite;
    }

    /**
     * Set ageGestationnel
     *
     * @param string $ageGestationnel
     *
     * @return Fiche
     */
    public function setAgeGestationnel($ageGestationnel)
    {
        $this->ageGestationnel = $ageGestationnel;

        return $this;
    }

    /**
     * Get ageGestationnel
     *
     * @return string
     */
    public function getAgeGestationnel()
    {
        return $this->ageGestationnel;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->antecedents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->analyses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->constantes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->glycemies = new \Doctrine\Common\Collections\ArrayCollection();
        $this->complications = new \Doctrine\Common\Collections\ArrayCollection();
        $this->gcs = new \Doctrine\Common\Collections\ArrayCollection();
        //$this->traitements = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setInfoHgpo('');
        $this->setTraitement('');
        $this->setCompteRendu('');
        $this->setGestite('');
        $this->setParite('');
        $this->setTypeHgpo('');
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Fiche
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
     * Set reference
     *
     * @param integer $reference
     *
     * @return Fiche
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return integer
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set hgpo
     *
     * @param boolean $hgpo
     *
     * @return Fiche
     */
    public function setHgpo($hgpo)
    {
        $this->hgpo = $hgpo;

        return $this;
    }

    /**
     * Get hgpo
     *
     * @return boolean
     */
    public function getHgpo()
    {
        return $this->hgpo;
    }

    /**
     * Set typeHgpo
     *
     * @param string $typeHgpo
     *
     * @return Fiche
     */
    public function setTypeHgpo($typeHgpo)
    {
        $this->typeHgpo = $typeHgpo;

        return $this;
    }

    /**
     * Get typeHgpo
     *
     * @return string
     */
    public function getTypeHgpo()
    {
        return $this->typeHgpo;
    }

    /**
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\Patient $patient
     *
     * @return Fiche
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
     * Set hopital
     *
     * @param \PS\ParametreBundle\Entity\Hopital $hopital
     *
     * @return Fiche
     */
    public function setHopital(\PS\ParametreBundle\Entity\Hopital $hopital)
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
     * Set personne
     *
     * @param \PS\UtilisateurBundle\Entity\Personne $personne
     *
     * @return Fiche
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
     * Add antecedent
     *
     * @param \PS\GestionBundle\Entity\FicheAntecedent $antecedent
     *
     * @return Fiche
     */
    public function addAntecedent(\PS\GestionBundle\Entity\FicheAntecedent $antecedent)
    {
        $this->antecedents[] = $antecedent;
        $antecedent->setFiche($this);
        return $this;
    }

    /**
     * Remove antecedent
     *
     * @param \PS\GestionBundle\Entity\FicheAntecedent $antecedent
     */
    public function removeAntecedent(\PS\GestionBundle\Entity\FicheAntecedent $antecedent)
    {
        $this->antecedents->removeElement($antecedent);
    }

    /**
     * Get antecedents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAntecedents()
    {
        return $this->antecedents;
    }

    /**
     * Add analysis
     *
     * @param \PS\GestionBundle\Entity\FicheAnalyse $analysis
     *
     * @return Fiche
     */
    public function addAnalysis(\PS\GestionBundle\Entity\FicheAnalyse $analysis)
    {
        $this->analyses[] = $analysis;
        $analysis->setFiche($this);
        return $this;
    }

    /**
     * Remove analysis
     *
     * @param \PS\GestionBundle\Entity\FicheAnalyse $analysis
     */
    public function removeAnalysis(\PS\GestionBundle\Entity\FicheAnalyse $analysis)
    {
        $this->analyses->removeElement($analysis);
    }

    /**
     * Get analyses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnalyses()
    {
        return $this->analyses;
    }

    /**
     * Add constante
     *
     * @param \PS\GestionBundle\Entity\FicheConstante $constante
     *
     * @return Fiche
     */
    public function addConstante(\PS\GestionBundle\Entity\FicheConstante $constante)
    {
        $this->constantes[] = $constante;
        $constante->setFiche($this);
        return $this;
    }

    /**
     * Remove constante
     *
     * @param \PS\GestionBundle\Entity\FicheConstante $constante
     */
    public function removeConstante(\PS\GestionBundle\Entity\FicheConstante $constante)
    {
        $this->constantes->removeElement($constante);
    }

    /**
     * Get constantes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConstantes()
    {
        return $this->constantes;
    }

    /**
     * Add glycemy
     *
     * @param \PS\GestionBundle\Entity\FicheGlycemie $glycemy
     *
     * @return Fiche
     */
    public function addGlycemy(\PS\GestionBundle\Entity\FicheGlycemie $glycemy)
    {
        $this->glycemies[] = $glycemy;
        $glycemy->setFiche($this);
        return $this;
    }

    /**
     * Remove glycemy
     *
     * @param \PS\GestionBundle\Entity\FicheGlycemie $glycemy
     */
    public function removeGlycemy(\PS\GestionBundle\Entity\FicheGlycemie $glycemy)
    {
        $this->glycemies->removeElement($glycemy);
    }

    /**
     * Get glycemies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGlycemies()
    {
        return $this->glycemies;
    }

    /**
     * Add complication
     *
     * @param \PS\GestionBundle\Entity\FicheComplication $complication
     *
     * @return Fiche
     */
    public function addComplication(\PS\GestionBundle\Entity\FicheComplication $complication)
    {
        $this->complications[] = $complication;
        $complication->setFiche($this);
        return $this;
    }

    /**
     * Remove complication
     *
     * @param \PS\GestionBundle\Entity\FicheComplication $complication
     */
    public function removeComplication(\PS\GestionBundle\Entity\FicheComplication $complication)
    {
        $this->complications->removeElement($complication);
    }

    /**
     * Get complications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComplications()
    {
        return $this->complications;
    }

    /**
     * Add gc
     *
     * @param \PS\GestionBundle\Entity\FicheGc $gc
     *
     * @return Fiche
     */
    public function addGc(\PS\GestionBundle\Entity\FicheGc $gc)
    {
        $this->gcs[] = $gc;
        $gc->setFiche($this);
        return $this;
    }

    /**
     * Remove gc
     *
     * @param \PS\GestionBundle\Entity\FicheGc $gc
     */
    public function removeGc(\PS\GestionBundle\Entity\FicheGc $gc)
    {
        $this->gcs->removeElement($gc);
    }

    /**
     * Get gcs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGcs()
    {
        return $this->gcs;
    }

    /**
     * Add traitement
     *
     * @param \PS\GestionBundle\Entity\FicheTraitement $traitement
     *
     * @return Fiche
     */
    public function addTraitement(\PS\GestionBundle\Entity\FicheTraitement $traitement)
    {
        $this->traitements[] = $traitement;
        $traitement->setFiche($this);
        return $this;
    }

    /**
     * Remove traitement
     *
     * @param \PS\GestionBundle\Entity\FicheTraitement $traitement
     */
    public function removeTraitement(\PS\GestionBundle\Entity\FicheTraitement $traitement)
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
     * Set traitement
     *
     * @param string $traitement
     *
     * @return Fiche
     */
    public function setTraitement($traitement)
    {
        $this->traitement = $traitement;

        return $this;
    }

    /**
     * Get traitement
     *
     * @return string
     */
    public function getTraitement()
    {
        return $this->traitement;
    }

    /**
     * Set infoHgpo
     *
     * @param string $infoHgpo
     *
     * @return Fiche
     */
    public function setInfoHgpo($infoHgpo)
    {
        $this->infoHgpo = $infoHgpo;

        return $this;
    }

    /**
     * Get infoHgpo
     *
     * @return string
     */
    public function getInfoHgpo()
    {
        return $this->infoHgpo;
    }

    /**
     * Set compteRendu
     *
     * @param string $compteRendu
     *
     * @return Fiche
     */
    public function setCompteRendu($compteRendu)
    {
        $this->compteRendu = $compteRendu;

        return $this;
    }

    /**
     * Get compteRendu
     *
     * @return string
     */
    public function getCompteRendu()
    {
        return $this->compteRendu;
    }
}
