<?php

namespace PS\GestionBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use PS\GestionBundle\Validator\Constraints as PSAssert;
use PS\ParametreBundle\Validator\Constraints\Valid as CValid;
use PS\ParametreBundle\Entity\Affection;
use PS\ParametreBundle\Entity\Specialite;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ExclusionPolicy("all")
 * @PSAssert\AssuranceConstraint()
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\ConsultationRepository")
 * @GRID\Source(columns="id,refConsultation,dateConsultation,patient_nom_complet,patient.id,patient.personne.nom,patient.personne.prenom,medecin.hopital.nom,medecin_nom_complet,medecin.personne.nom,medecin.personne.prenom,motif,diagnostique,specialite.nom,statut")
 * @GRID\Column(id="patient_nom_complet", type="join", title="Patient", columns={"patient.personne.nom", "patient.personne.prenom"}, operatorsVisible=false, operators={"rlike"}, defaultOperator="rlike")
 * @GRID\Column(id="medecin_nom_complet", type="join", title="Medecin", columns={"medecin.personne.nom", "medecin.personne.prenom"}, operatorsVisible=false)
 */
class Consultation
{
    /**
     * @Expose
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @GRID\Column(title="ID", primary=true, visible=false, operators={"eq"}, defaultOperator="eq")
     * @Groups({"consultation", "ordonnance"})
     */
    private $id;

    /**
     * @Expose
     * @SerializedName("date")
     * @ORM\Column(type="date")
     * @Assert\NotBlank(groups={"Constante"})
     * @GRID\Column(title="Date", operatorsVisible=false)
     * @Groups({"consultation"})
     */
    private $dateConsultation;

    /**
     * @ORM\ManyToOne(targetEntity="Patient", inversedBy="consultations")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     * @GRID\Column(field="patient.personne.nom", visible=false)
     * @GRID\Column(field="patient.personne.prenom", visible=false)
     * @GRID\Column(field="patient.id", visible=false)
     */
    private $patient;

    /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Specialite")
     * @Assert\NotBlank()
     * @GRID\Column(field="specialite.nom", title="consultation.form.specialite.label", filter="select", operatorsVisible=false)
     * @Groups({"consultation"})
     */
    private $specialite;

    /**
     * @Expose
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="consultation.form.message", groups={"Consultation"})
     * @GRID\Column(title="consultation.form.motif", filterable=false)
     * @Groups({"consultation"})
     */
    private $motif;

    /**
     * @Expose
     * @SerializedName("diagnostic")
     * @ORM\Column(type="text")
     * @GRID\Column(title="consultation.form.diagnostique", filterable=false)
     * @Groups({"consultation"})
     */
    private $diagnostique;

    /**
     * @Expose
     * @ORM\ManyToOne(targetEntity="Medecin", inversedBy="consultations")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="consultation.form.medecin.message")
     * @GRID\Column(field="medecin.hopital.nom", title="consultation.form.medecin.hopital", filter="select", operatorsVisible=false)
     * @GRID\Column(field="medecin.personne.nom", title="consultation.form.medecin.nom", visible=false)
     * @GRID\Column(field="medecin.personne.prenom", title="consultation.form.medecin.prenoms", visible=false)
     * @Groups({"medecin"})
     */
    private $medecin;
    /**
     * @Expose
     * @ORM\OneToMany(targetEntity="ConsultationAffections", mappedBy="consultation", cascade={"persist"}, orphanRemoval=true)
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"consultation-details", "consultation-affection"})
     */
    private $affections;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"consultation"})
     */
    private $hospitalisation;

    /**
     * @Expose
     * @ORM\OneToMany(targetEntity="ConsultationTraitements", mappedBy="consultation", cascade={"persist"}, orphanRemoval=true)
     * @ORM\JoinColumn(nullable=true)
     *  @Assert\Valid()
     * @Groups({"consultation-details", "consultation-medicament"})
     *
     */
    private $medicaments;

    /**
     * @Expose
     * @ORM\OneToMany(targetEntity="ConsultationAnalyses", mappedBy="consultation", cascade={"persist"}, orphanRemoval=true)
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid()
     * @Groups({"consultation-details", "consultation-analyse"})
     */
    private $analyses;

    /**
     * @ORM\Column(type="decimal",scale=2, nullable=true)
     * @Groups({"consultation"})
     */
    private $poids;

    /**
     * @ORM\Column(type="integer",length=2, nullable=true)
     * @Groups({"consultation"})
     */
    private $temperature;

    /**
     * @ORM\Column(type="string",length=5, nullable=true)
     * @Groups({"consultation"})
     */
    private $tension;


    /**
     * @Expose
     * @SerializedName("diagnosticFinal")
     * @ORM\Column(type="text", name="diagnostic_final")
     * @GRID\Column(title="consultation.form.diagnostiqueFinal", filterable=false)
     * @Groups({"consultation"})
     */
    private $diagnostiqueFinal;

    /**
     * @Expose
     * @ORM\Column(type="smallint",length=1)
     * @Assert\Choice(choices={-1, 1}, message="Veuilleez choisir le statut")
     * @Groups({"consultation"})
     * @GRID\Column(title="consultation.form.statut", operators={"rlike"}, defaultOperator="rlike", filter="select"
    , selectFrom="values"
    , safe=false
    , align="center"
    , values={"1": "Validé", "-1": "En attente du médecin", "2": "Vérouillé", "3": "Annulé"})
     */
    private $statut;

    /**
     * @var mixed
     */
    private $expired;

    /**
     * @Exclude
     * @ORM\OneToMany(targetEntity="\PS\GestionBundle\Entity\HistoriqueConsultation", mappedBy="consultation", cascade={"persist"})
     * @ORM\OrderBy({"dateHistorique" = "DESC"})
     */
    private $actions;


    /**
     * @Exclude
     * @ORM\OneToOne(targetEntity="RendezVousConsultation", mappedBy="consultation", cascade={"persist"}, fetch="EAGER")
     */
    private $rdv;

    /**
     * @Exclude
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Hopital")
     */
    private $hopital;

    /**
     * @Exclude
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Assurance")
     */
    private $assurance;

    /**
     * @Expose
     * @SerializedName("reference")
     * @ORM\Column(name="ref_consultation", type="string", length=25)
     * @GRID\Column(title="consultation.form.refConsultation", operatorsVisible=false)
     * @Groups({"consultation"})
     */
    private $refConsultation;

    /**
     * @Exclude
     * @ORM\Column(type="text")
     */
    private $observation;

    /**
     * @Exclude
     * @ORM\Column(type="decimal", precision=10, scale=2, name="prix_consultation", options={"default": 0})
     */
    private $prixConsultation;

    /**
     * @Exclude
     * @ORM\OneToMany(targetEntity="InfoConsultation", mappedBy="consultation")
     * @ORM\OrderBy({"dateInfo" = "DESC"})
     */
    private $infos;



    /**
     * @Expose
     * @ORM\OneToMany(targetEntity="ConsultationSigne", mappedBy="consultation", cascade={"persist"}, orphanRemoval=true)
     * @Assert\Valid()
     * @Groups({"consultation-signe"})
     */
    private $signes;



    /**
     * @Exclude
     * @ORM\OneToMany(targetEntity="ConsultationConstante", mappedBy="consultation", cascade={"persist"})
     * @Assert\Valid()
     * @Groups({"consultation"})
     *
     */
    private $constantes;


    /**
     * @Exclude
     * @ORM\OneToMany(targetEntity="ConsultationAntecedent", mappedBy="consultation", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Assert\Valid()
     * @Groups({"consultation"})
     *
     */
    private $antecedents;


    /**
     * @var string
     *
     * @ORM\Column(name="histoire", type="text", nullable=true)
     */
    private $histoire;


     /**
     * @ORM\ManyToMany(targetEntity="PS\ParametreBundle\Entity\Fichier", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="fichier_consultation")
     * @Assert\Valid()
     */
    private $fichiers;

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
     * Set dateConsultation
     *
     * @param \DateTime $dateConsultation
     * @return Consultation
     */
    public function setDateConsultation($dateConsultation)
    {
        $this->dateConsultation = $dateConsultation;

        return $this;
    }

    /**
     * Get dateConsultation
     *
     * @return \DateTime
     */
    public function getDateConsultation()
    {
        return $this->dateConsultation;
    }

    /**
     * Set poids
     *
     * @param float $poids
     * @return Consultation
     */
    public function setPoids($poids)
    {
        $this->poids = $poids;

        return $this;
    }

    /**
     * Get poids
     *
     * @return float
     */
    public function getPoids()
    {
        return $this->poids;
    }

    /**
     * Set temperature
     *
     * @param integer $temperature
     * @return Consultation
     */
    public function setTemperature($temperature)
    {
        $this->temperature = $temperature;

        return $this;
    }

    /**
     * @return mixed
     */
    public function hasExpired()
    {
        return $this->dateConsultation < new \DateTime();
    }

    /**
     * @param $rdv
     */
    public function setRdv(\PS\GestionBundle\Entity\RendezVousConsultation $rdv)
    {
        if ($rdv && $rdv->getLibRendezVous() && $rdv->getDateRendezVous()) {

            $rdv->setConsultation($this);
            $rdv->setStatutRendezVous(false);
            $rdv->setTypeRendezVous(1);
            $rdv->setPatient($this->getPatient());
            $rdv->setMedecin($this->getMedecin());
            $rdv->setSpecialite($this->getSpecialite());

            $this->rdv = $rdv;
        }


        return $this;
    }

    public function getRdv()
    {
        return $this->rdv;
    }
    /**
     * Get temperature
     *
     * @return integer
     */
    public function getTemperature()
    {
        return $this->temperature;
    }

    /**
     * Set tension
     *
     * @param string $tension
     * @return Consultation
     */
    public function setTension($tension)
    {
        $this->tension = $tension;

        return $this;
    }

    /**
     * Get tension
     *
     * @return string
     */
    public function getTension()
    {
        return $this->tension;
    }

    /**
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\Patient $patient
     * @return Consultation
     */
    public function setPatient(Patient $patient = null)
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
     * Set medecin
     *
     * @param \PS\GestionBundle\Entity\Medecin $medecin
     * @return Consultation
     */
    public function setMedecin(Medecin $medecin = null)
    {
        $this->medecin = $medecin;

        return $this;
    }

    /**
     * Get medecin
     *
     * @return \PS\GestionBundle\Entity\Medecin
     */
    public function getMedecin()
    {
        return $this->medecin;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dateConsultation = new \DateTime();
        $this->affections       = new \Doctrine\Common\Collections\ArrayCollection();
        $this->analyses         = new \Doctrine\Common\Collections\ArrayCollection();
        $this->medicaments      = new \Doctrine\Common\Collections\ArrayCollection();
        $this->actions          = new \Doctrine\Common\Collections\ArrayCollection();
        $this->infos            = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rendezvous       = new \Doctrine\Common\Collections\ArrayCollection();
        $this->signes            = new \Doctrine\Common\Collections\ArrayCollection();

        $this->signes            = new \Doctrine\Common\Collections\ArrayCollection();
        //$this->donnees            = new \Doctrine\Common\Collections\ArrayCollection();
        $this->constantes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->donnees            = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fichiers       = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setObservation('');

        $this->setPrixConsultation(0);
        $this->setDiagnostique('');
        $this->setDiagnostiqueFinal('');
        $this->setMotif('');
        $this->setStatut(1);
    }

    /**
     * Set motif
     *
     * @param string $motif
     * @return Consultation
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
     * Set diagnostique
     *
     * @param string $diagnostique
     * @return Consultation
     */
    public function setDiagnostique($diagnostique)
    {
        $this->diagnostique = $diagnostique;

        return $this;
    }

    /**
     * Get diagnostique
     *
     * @return string
     */
    public function getDiagnostique()
    {
        return $this->diagnostique;
    }

    /**
     * Set specialite
     *
     * @param \PS\ParametreBundle\Entity\Specialite $specialite
     * @return Consultation
     */
    public function setSpecialite(Specialite $specialite = null)
    {
        $this->specialite = $specialite;

        return $this;
    }

    /**
     * Get specialite
     *
     * @return \PS\ParametreBundle\Entity\Specialite
     */
    public function getSpecialite()
    {
        return $this->specialite;
    }

    /**
     * Set hospitalisation
     *
     * @param string $hospitalisation
     * @return Consultation
     */
    public function setHospitalisation($hospitalisation)
    {
        $this->hospitalisation = $hospitalisation;

        return $this;
    }

    /**
     * Get hospitalisation
     *
     * @return string
     */
    public function getHospitalisation()
    {
        return $this->hospitalisation;
    }

    /**
     * Add affection
     *
     * @param \PS\GestionBundle\Entity\ConsultationAffections $affection
     * @return Consultation
     */
    public function addAffection(\PS\GestionBundle\Entity\ConsultationAffections $affection)
    {
        $this->affections[] = $affection;
        $affection->setConsultation($this);
        return $this;
    }

    /**
     * Remove affections
     *
     * @param \PS\GestionBundle\Entity\ConsultationAffections $affection
     */
    public function removeAffection(\PS\GestionBundle\Entity\ConsultationAffections $affection)
    {
        $this->affections->removeElement($affection);
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
     * Add medicament
     *
     * @param \PS\GestionBundle\Entity\ConsultationTraitements $medicament
     * @return Consultation
     */
    public function addMedicament(\PS\GestionBundle\Entity\ConsultationTraitements $medicament)
    {
        $this->medicaments[] = $medicament;

        $medicament->setConsultation($this);

        return $this;
    }

    /**
     * Remove medicaments
     *
     * @param \PS\GestionBundle\Entity\ConsultationTraitements $medicaments
     */
    public function removeMedicament(\PS\GestionBundle\Entity\ConsultationTraitements $medicaments)
    {
        $this->medicaments->removeElement($medicaments);
    }

    /**
     * Get medicaments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMedicaments()
    {
        return $this->medicaments;
    }

    /**
     * Add analyse
     *
     * @param \PS\GestionBundle\Entity\ConsultationAnalyses $analyse
     * @return Consultation
     */
    public function addAnalyse(\PS\GestionBundle\Entity\ConsultationAnalyses $analyse)
    {
        $this->analyses[] = $analyse;

        $analyse->setConsultation($this);

        return $this;
    }

    /**
     * Remove analyse
     *
     * @param \PS\GestionBundle\Entity\ConsultationAnalyses $analyse
     */
    public function removeAnalyse(\PS\GestionBundle\Entity\ConsultationAnalyses $analyse)
    {
        $this->analyses->removeElement($analyse);
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
     * Add analyses
     *
     * @param \PS\GestionBundle\Entity\ConsultationAnalyses $analyses
     * @return Consultation
     */
    public function addAnalyses(\PS\GestionBundle\Entity\ConsultationAnalyses $analyses)
    {
        $this->analyses[] = $analyses;

        $analyses->setConsultation($this);

        return $this;
    }

    /**
     * Remove analyses
     *
     * @param \PS\GestionBundle\Entity\ConsultationAnalyses $analyses
     */
    public function removeAnalyses(\PS\GestionBundle\Entity\ConsultationAnalyses $analyses)
    {
        $this->analyses->removeElement($analyses);
    }

    /**
     * Add analyses
     *
     * @param \PS\GestionBundle\Entity\ConsultationAnalyses $analyses
     * @return Consultation
     */
    public function addAnalysis(\PS\GestionBundle\Entity\ConsultationAnalyses $analyses)
    {
        $this->analyses[] = $analyses;

        return $this;
    }

    /**
     * Remove analyses
     *
     * @param \PS\GestionBundle\Entity\ConsultationAnalyses $analyses
     */
    public function removeAnalysis(\PS\GestionBundle\Entity\ConsultationAnalyses $analyses)
    {
        $this->analyses->removeElement($analyses);
    }

    /**
     * Set statut
     *
     * @param integer $statut
     *
     * @return Consultation
     */
    public function setStatut($statut)
    {
        if (is_null($statut)) {
            $statut = -1;
        }

        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return integer
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Add action
     *
     * @param \PS\GestionBundle\Entity\HistoriqueConsultation $action
     *
     * @return Consultation
     */
    public function addAction(\PS\GestionBundle\Entity\HistoriqueConsultation $action)
    {
        $this->actions[] = $action;
        $action->setConsultation($this);

        return $this;
    }

    /**
     * Remove action
     *
     * @param \PS\GestionBundle\Entity\HistoriqueConsultation $action
     */
    public function removeAction(\PS\GestionBundle\Entity\HistoriqueConsultation $action)
    {
        $this->actions->removeElement($action);
    }

    /**
     * Get actions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Set hopital
     *
     * @param \PS\ParametreBundle\Entity\Hopital $hopital
     *
     * @return Consultation
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
     * Add rendezvous
     *
     * @param \PS\GestionBundle\Entity\RendezVous $rendezvous
     *
     * @return Consultation
     */
    public function addRendezvous(\PS\GestionBundle\Entity\RendezVous $rendezvous)
    {
        $this->rendezvous[] = $rendezvous;
        $rendezvous->setConsultation($this);

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
     * Set refConsultation
     *
     * @param string $refConsultation
     *
     * @return Consultation
     */
    public function setRefConsultation($refConsultation)
    {
        $this->refConsultation = $refConsultation;

        return $this;
    }

    /**
     * Get refConsultation
     *
     * @return string
     */
    public function getRefConsultation()
    {
        return $this->refConsultation;
    }

    /**
     * Set assurance
     *
     * @param \PS\ParametreBundle\Entity\Assurance $assurance
     *
     * @return Consultation
     */
    public function setAssurance(\PS\ParametreBundle\Entity\Assurance $assurance = null)
    {
        $this->assurance = $assurance;

        return $this;
    }

    /**
     * Get assurance
     *
     * @return \PS\ParametreBundle\Entity\Assurance
     */
    public function getAssurance()
    {
        return $this->assurance;
    }

    /**
     * Set observation
     *
     * @param string $observation
     *
     * @return Consultation
     */
    public function setObservation($observation)
    {
        $this->observation = $observation;

        return $this;
    }

    /**
     * Get observation
     *
     * @return string
     */
    public function getObservation()
    {
        return $this->observation;
    }

    /**
     * Add info
     *
     * @param \PS\GestionBundle\Entity\InfoConsultation $info
     *
     * @return Consultation
     */
    public function addInfo(\PS\GestionBundle\Entity\InfoConsultation $info)
    {
        $this->infos[] = $info;

        return $this;
    }

    /**
     * Remove info
     *
     * @param \PS\GestionBundle\Entity\InfoConsultation $info
     */
    public function removeInfo(\PS\GestionBundle\Entity\InfoConsultation $info)
    {
        $this->infos->removeElement($info);
    }

    /**
     * Get infos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInfos()
    {
        return $this->infos;
    }

    /**
     * Dernière opération evnetuelle de l'assureur
     */
    public function getlastInfo()
    {
        if (!$this->infos->isEmpty()) {
            return $this->infos->last();
        }
    }

    /**
     * Set prixConsultation
     *
     * @param string $prixConsultation
     *
     * @return Consultation
     */
    public function setPrixConsultation($prixConsultation)
    {
        $this->prixConsultation = $prixConsultation;

        return $this;
    }

    /**
     * Get prixConsultation
     *
     * @return string
     */
    public function getPrixConsultation()
    {
        return $this->prixConsultation;
    }


    /**
     * Set diagnostiqueFinal
     *
     * @param string $diagnostiqueFinal
     *
     * @return Consultation
     */
    public function setDiagnostiqueFinal($diagnostiqueFinal)
    {
        $this->diagnostiqueFinal = $diagnostiqueFinal;

        return $this;
    }

    /**
     * Get diagnostiqueFinal
     *
     * @return string
     */
    public function getDiagnostiqueFinal()
    {
        return $this->diagnostiqueFinal;
    }



    /**
     * Add signe
     *
     * @param \PS\GestionBundle\Entity\ConsultationSigne $signe
     *
     * @return Consultation
     */
    public function addSigne(\PS\GestionBundle\Entity\ConsultationSigne $signe)
    {
        $this->signes[] = $signe;
        $signe->setConsultation($this);
        return $this;
    }

    /**
     * Remove signe
     *
     * @param \PS\GestionBundle\Entity\ConsultationSigne $signe
     */
    public function removeSigne(\PS\GestionBundle\Entity\ConsultationSigne $signe)
    {
        $this->signes->removeElement($signe);
    }

    /**
     * Get signes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSignes()
    {
        return $this->signes;
    }


    /**
     * Add constante.
     *
     * @param \PS\GestionBundle\Entity\ConsultationConstante $constante
     *
     * @return Consultation
     */
    public function addConstante(\PS\GestionBundle\Entity\ConsultationConstante $constante)
    {
        $this->constantes[] = $constante;
        $constante->setConsultation($this);
        $constante->setPatient($this->getPatient());
        return $this;
    }

    /**
     * Remove constante.
     *
     * @param \PS\GestionBundle\Entity\ConsultationConstante $constante
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeConstante(\PS\GestionBundle\Entity\ConsultationConstante $constante)
    {
        return $this->constantes->removeElement($constante);
    }

    /**
     * Get constantes.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConstantes()
    {
        return $this->constantes;
    }



    /**
     * Set histoire
     *
     * @param string $histoire
     *
     * @return InfoDiabete
     */
    public function setHistoire(?string $histoire)
    {
        $this->histoire = $histoire;

        return $this;
    }

    /**
     * Get histoire
     *
     * @return string
     */
    public function getHistoire()
    {
        return $this->histoire;
    }


    /**
     * Add antecedent
     *
     * @param \PS\GestionBundle\Entity\ConsultationAntecedent $antecedent
     *
     * @return Consultation
     */
    public function addAntecedent(\PS\GestionBundle\Entity\ConsultationAntecedent $antecedent)
    {
        $this->antecedents[] = $antecedent;
        $antecedent->setConsultation($this);
        return $this;
    }

    /**
     * Remove antecedent
     *
     * @param \PS\GestionBundle\Entity\ConsultationAntecedent $antecedent
     */
    public function removeAntecedent(\PS\GestionBundle\Entity\ConsultationAntecedent $antecedent)
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
     * Add fichier
     *
     * @param \PS\ParametreBundle\Entity\Fichier $fichier
     *
     * @return Consultation
     */
    public function addFichier(\PS\ParametreBundle\Entity\Fichier $fichier)
    {
        $this->fichiers[] = $fichier;

        return $this;
    }

    /**
     * Remove fichier
     *
     * @param \PS\ParametreBundle\Entity\Fichier $fichier
     */
    public function removeFichier(\PS\ParametreBundle\Entity\Fichier $fichier)
    {
        $this->fichiers->removeElement($fichier);
    }

    /**
     * Get fichiers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFichiers()
    {
        return $this->fichiers;
    }
}
