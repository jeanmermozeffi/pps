<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PS\ParametreBundle\Validator\Constraints as PSAssert;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Questionnaire
 *
 * @ORM\Table(name="questionnaire")
 * @ORM\InheritanceType("JOINED")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\QuestionnaireRepository")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"questionnaire"="Questionnaire", "depistage"="QuestionnaireDepistage"})
 */
class Questionnaire
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(visible=false)
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="introduction", type="text")
     */
    protected $introduction;

    /**
     * @var string
     *
     * @ORM\Column(name="conclusion", type="text")
     */
    protected $conclusion;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     * @GRID\Column(title="Libellé")
     */
    protected $libelle;


    /**
     * @ORM\OneToMany(targetEntity="LigneQuestionnaire", cascade={"persist"}, mappedBy="questionnaire")
     * @PSAssert\Valid(groups={"ligneQ"})
     * @ORM\OrderBy({"ordre"="ASC"})
     */
    protected $lignes;



     /**
     * @ORM\OneToMany(targetEntity="DiagnosticQuestionnaire", mappedBy="questionnaire", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Assert\Valid()
     */
    protected $diagnostics;



    /**
     * @ORM\OneToMany(targetEntity="PatientQuestionnaire", cascade={"persist"}, mappedBy="questionnaire")
     * @PSAssert\Valid(groups={"ligneQ"})
     */
    protected $patients;



   



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
     * Set introduction
     *
     * @param string $introduction
     *
     * @return Questionnaire
     */
    public function setIntroduction(string $introduction)
    {
        $this->introduction = $introduction;

        return $this;
    }

    /**
     * Get introduction
     *
     * @return string
     */
    public function getIntroduction()
    {
        return $this->introduction;
    }

    /**
     * Set conclusion
     *
     * @param string $conclusion
     *
     * @return Questionnaire
     */
    public function setConclusion(string $conclusion)
    {
        $this->conclusion = $conclusion;

        return $this;
    }

    /**
     * Get conclusion
     *
     * @return string
     */
    public function getConclusion()
    {
        return $this->conclusion;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Questionnaire
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
     * Constructor
     */
    public function __construct()
    {
        $this->lignes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->patients = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ligne
     *
     * @param \PS\GestionBundle\Entity\LigneQuestionnaire $ligne
     *
     * @return Questionnaire
     */
    public function addLigne(\PS\GestionBundle\Entity\LigneQuestionnaire $ligne)
    {
        $this->lignes[] = $ligne;
        $ligne->setQuestionnaire($this);
        return $this;
    }

    /**
     * Remove ligne
     *
     * @param \PS\GestionBundle\Entity\LigneQuestionnaire $ligne
     */
    public function removeLigne(\PS\GestionBundle\Entity\LigneQuestionnaire $ligne)
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

    /**
     * Add patient
     *
     * @param \PS\GestionBundle\Entity\PatientQuestionnaire $patient
     *
     * @return Questionnaire
     */
    public function addPatient(\PS\GestionBundle\Entity\PatientQuestionnaire $patient)
    {
        $this->patients[] = $patient;

        return $this;
    }

    /**
     * Remove patient
     *
     * @param \PS\GestionBundle\Entity\PatientQuestionnaire $patient
     */
    public function removePatient(\PS\GestionBundle\Entity\PatientQuestionnaire $patient)
    {
        $this->patients->removeElement($patient);
    }

    /**
     * Get patients
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPatients()
    {
        return $this->patients;
    }

    /**
     * Add diagnostic
     *
     * @param \PS\GestionBundle\Entity\DiagnosticQuestionnaire $diagnostic
     *
     * @return Questionnaire
     */
    public function addDiagnostic(\PS\GestionBundle\Entity\DiagnosticQuestionnaire $diagnostic)
    {
        $this->diagnostics[] = $diagnostic;
        $diagnostic->setQuestionnaire($this);
        return $this;
    }

    /**
     * Remove diagnostic
     *
     * @param \PS\GestionBundle\Entity\DiagnosticQuestionnaire $diagnostic
     */
    public function removeDiagnostic(\PS\GestionBundle\Entity\DiagnosticQuestionnaire $diagnostic)
    {
        $this->diagnostics->removeElement($diagnostic);
    }

    /**
     * Get diagnostics
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDiagnostics()
    {
        return $this->diagnostics;
    }
}
