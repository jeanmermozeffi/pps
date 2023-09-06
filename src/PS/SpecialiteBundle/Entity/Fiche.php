<?php

namespace PS\SpecialiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * Fiche
 *
 * @ORM\Table(name="fiche_specialite")
 * @ORM\Entity(repositoryClass="PS\SpecialiteBundle\Repository\FicheRepository")
 *  @GRID\Source(columns="id,dateFiche,numFiche,specialite.nom", filterable=false, sortable=false)
 *  @GRID\Column(id="nom_complet", type="join", title="Nom et prénoms", columns={"patient.personne.nom", "patient.personne.prenom"}, operatorsVisible=false)
 */
class Fiche
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
     * @var datetime_immutable
     *
     * @ORM\Column(name="date_fiche", type="datetime")
     * @GRID\Column(title="Date de création")
     */
    private $dateFiche;

    

   

    /**
     * @ORM\Column(type="boolean", name="accord_patient")
     */
    private $accordPatient;


    /**
     * @ORM\Column(type="string", name="distance_hopital_ref", length=10)
     */
    private $distanceHopitalRef;


    /**
     * @ORM\Column(name="num_fiche", type="integer")
     * @GRID\Column(title="Numéro")
     */
    private $numFiche;


    /**
     * @ORM\OneToMany(targetEntity="HistoriqueFiche", mappedBy="fiche", cascade={"persist"})
     */
    private $historiques;

   


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
     * Set dateFiche
     *
     * @param datetime_immutable $dateFiche
     *
     * @return Fiche
     */
    public function setDateFiche($dateFiche)
    {
        $this->dateFiche = $dateFiche;

        return $this;
    }

    /**
     * Get dateFiche
     *
     * @return datetime_immutable
     */
    public function getDateFiche()
    {
        return $this->dateFiche;
    }


    /**
     * Set numFiche
     *
     * @param integer $numFiche
     *
     * @return Fiche
     */
    public function setNumFiche($numFiche)
    {
        $this->numFiche = $numFiche;

        return $this;
    }

    /**
     * Get numFiche
     *
     * @return integer
     */
    public function getNumFiche()
    {
        return $this->numFiche;
    }

    /**
     * Set specialite
     *
     * @param \PS\ParametreBundle\Entity\Specialite $specialite
     *
     * @return Fiche
     */
    public function setSpecialite(\PS\ParametreBundle\Entity\Specialite $specialite)
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
     * Set accordPatient
     *
     * @param boolean $accordPatient
     *
     * @return Fiche
     */
    public function setAccordPatient($accordPatient)
    {
        $this->accordPatient = $accordPatient;

        return $this;
    }

    /**
     * Get accordPatient
     *
     * @return boolean
     */
    public function getAccordPatient()
    {
        return $this->accordPatient;
    }

    /**
     * Set distanceHopitalRef
     *
     * @param string $distanceHopitalRef
     *
     * @return Fiche
     */
    public function setDistanceHopitalRef($distanceHopitalRef)
    {
        $this->distanceHopitalRef = $distanceHopitalRef;

        return $this;
    }

    /**
     * Get distanceHopitalRef
     *
     * @return string
     */
    public function getDistanceHopitalRef()
    {
        return $this->distanceHopitalRef;
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
     * @param \PS\SpecialiteBundle\Entity\HistoriqueFiche $historique
     *
     * @return Fiche
     */
    public function addHistorique(\PS\SpecialiteBundle\Entity\HistoriqueFiche $historique)
    {
        $this->historiques[] = $historique;
        $historique->setFiche($this);

        return $this;
    }

    /**
     * Remove historique
     *
     * @param \PS\SpecialiteBundle\Entity\HistoriqueFiche $historique
     */
    public function removeHistorique(\PS\SpecialiteBundle\Entity\HistoriqueFiche $historique)
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
}
