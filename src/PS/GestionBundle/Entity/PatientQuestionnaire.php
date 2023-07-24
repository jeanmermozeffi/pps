<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PS\ParametreBundle\Validator\Constraints as PSAssert;
use APY\DataGridBundle\Grid\Mapping as GRID;
/**
 * PatientQuestionnaire
 *
 * @ORM\Table(name="patient_questionnaire")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\PatientQuestionnaireRepository")
 * @GRID\Source(columns="id,patient_nom_complet,patient.personne.nom,patient.personne.prenom,questionnaire.libelle,date,statut")
 * @GRID\Column(id="patient_nom_complet", type="join", title="Patient", columns={"patient.personne.nom", "patient.personne.prenom"}, operatorsVisible=false, operators={"rlike"}, defaultOperator="rlike", role="ROLE_MEDECIN")
 */
class PatientQuestionnaire
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @GRID\Column(title="Date de soumission", filterable=false)
     */
    private $date;

   
    /**
     * @ORM\ManyToOne(targetEntity="Patient", inversedBy="questionnaires")
     * @ORM\JoinColumn(nullable=false)
     * @GRID\Column(field="patient.personne.nom", visible=false)
     * @GRID\Column(field="patient.personne.prenom", visible=false)
     * @GRID\Column(field="patient.id", visible=false)
     */
    private $patient;


    /**
     * @ORM\ManyToOne(targetEntity="Questionnaire", inversedBy="patients")
     * @ORM\JoinColumn(nullable=false)
     * @GRID\Column(field="questionnaire.libelle", title="Questionnaire", filter="select")
     */
    private $questionnaire;



     /**
     * @ORM\ManyToOne(targetEntity="DiagnosticQuestionnaire")
     * @GRID\Column(field="diagnostic.libelle", title="Diagnostic", filter="select")
     */
    private $diagnostic;




    /**
     * @ORM\OneToMany(targetEntity="DonneeQuestionnaire", cascade={"persist"}, mappedBy="patient")
     * @PSAssert\Valid(groups={"donnee"})
     */
    private $donnees;



    /**
     * @ORM\OneToMany(targetEntity="TraitementQuestionnaire", cascade={"persist"}, mappedBy="patient")
     * @PSAssert\Valid(groups={"traitement"})
     * @ORM\OrderBy({"date"="DESC"})
     */
    private $traitements;


      /**
     * @var int
     *
     * @ORM\Column(name="pourcentage", columnDefinition="TINYINT(2)")
     */
    private $pourcentage;



    /**
     * @var bool
     *
     * @ORM\Column(name="statut", type="boolean")
     * @GRID\Column(title="Statut", operators={"rlike"}, defaultOperator="rlike", filter="select"
    , selectFrom="values"
    , safe=false
    , align="center"
    , values={"1": "Traité", "0": "En attente du médecin"}
    , size= 40)
     */
    private $statut = false;



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
     * @return PatientQuestionnaire
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
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\Patient $patient
     *
     * @return PatientQuestionnaire
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
     * Set questionnaire
     *
     * @param \PS\GestionBundle\Entity\Questionnaire $questionnaire
     *
     * @return PatientQuestionnaire
     */
    public function setQuestionnaire(\PS\GestionBundle\Entity\Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;

        return $this;
    }

    /**
     * Get questionnaire
     *
     * @return \PS\GestionBundle\Entity\Questionnaire
     */
    public function getQuestionnaire()
    {
        return $this->questionnaire;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->donnees = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set statut
     *
     * @param boolean $statut
     *
     * @return PatientQuestionnaire
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return boolean
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Add donnee
     *
     * @param \PS\GestionBundle\Entity\DonneeQuestionnaire $donnee
     *
     * @return PatientQuestionnaire
     */
    public function addDonnee(\PS\GestionBundle\Entity\DonneeQuestionnaire $donnee)
    {
        $this->donnees[] = $donnee;
        $donnee->setPatient($this);
        return $this;
    }

    /**
     * Remove donnee
     *
     * @param \PS\GestionBundle\Entity\DonneeQuestionnaire $donnee
     */
    public function removeDonnee(\PS\GestionBundle\Entity\DonneeQuestionnaire $donnee)
    {
        $this->donnees->removeElement($donnee);
    }

    /**
     * Get donnees
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDonnees()
    {
        return $this->donnees;
    }

    /**
     * Add traitement
     *
     * @param \PS\GestionBundle\Entity\TraitementQuestionnaire $traitement
     *
     * @return PatientQuestionnaire
     */
    public function addTraitement(\PS\GestionBundle\Entity\TraitementQuestionnaire $traitement)
    {
        $this->traitements[] = $traitement;
        $traitement->setPatient($this);
        return $this;
    }

    /**
     * Remove traitement
     *
     * @param \PS\GestionBundle\Entity\TraitementQuestionnaire $traitement
     */
    public function removeTraitement(\PS\GestionBundle\Entity\TraitementQuestionnaire $traitement)
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
     * Set pourcentage
     *
     * @param string $pourcentage
     *
     * @return PatientQuestionnaire
     */
    public function setPourcentage($pourcentage)
    {
        $this->pourcentage = $pourcentage;

        return $this;
    }

    /**
     * Get pourcentage
     *
     * @return string
     */
    public function getPourcentage()
    {
        return $this->pourcentage;
    }

    /**
     * Set diagnostic
     *
     * @param \PS\GestionBundle\Entity\DiagnosticQuestionnaire $diagnostic
     *
     * @return PatientQuestionnaire
     */
    public function setDiagnostic(\PS\GestionBundle\Entity\DiagnosticQuestionnaire $diagnostic = null)
    {
        $this->diagnostic = $diagnostic;

        return $this;
    }

    /**
     * Get diagnostic
     *
     * @return \PS\GestionBundle\Entity\DiagnosticQuestionnaire
     */
    public function getDiagnostic()
    {
        return $this->diagnostic;
    }
}
