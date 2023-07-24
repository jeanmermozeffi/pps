<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * LigneQuestionnaire
 *
 * @ORM\Table(name="ligne_questionnaire")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\LigneQuestionnaireRepository")
 * @GRID\Source(columns="id,question", operatorsVisible=false, filterable=false)
 */
 
class LigneQuestionnaire
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
     * @ORM\Column(type="integer")
     */
    private $ordre;

    /**
     * @var string
     *
     * @ORM\Column(name="question", type="text")
     * @GRID\Column(title="Question")
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(name="type_champ", type="string", length=25)
     */
    private $typeChamp;

    /**
     * @var array
     *
     * @ORM\Column(name="valeur_possible", type="json_array")
     */
    private $valeurPossible;


    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="ValeurLigneQuestionnaire", mappedBy="ligne", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $valeurs;

    /**
     * @var bool
     *
     * @ORM\Column(name="requis", type="boolean")
     */
    private $requis = true;


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
     */
    private $statut = true;


     /**
     * @var bool
     *
     * @ORM\Column(name="multiple", type="boolean")
     */
    private $multiple = false;

    /**
     * @var string
     *
     * @ORM\Column(name="lib_aide", type="text")
     */
    private $libAide;

    /**
     * @var array
     *
     * @ORM\Column(name="option_champ", type="json_array")
     */
    private $optionChamp = [];

    /**
     * @var array
     *
     * @ORM\Column(name="valeur_defaut", type="json_array")
     */
    private $valeurDefaut = [];



    /**
     * @ORM\ManyToOne(targetEntity="Questionnaire", inversedBy="lignes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $questionnaire;



    /**
     * @ORM\ManyToOne(targetEntity="LigneQuestionnaire", inversedBy="enfants")
     */
    private $parent;


     /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="LigneQuestionnaire", mappedBy="parent", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $enfants;


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
     * Set question
     *
     * @param string $question
     *
     * @return LigneQuestionnaire
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set typeChamp
     *
     * @param string $typeChamp
     *
     * @return LigneQuestionnaire
     */
    public function setTypeChamp($typeChamp)
    {
        $this->typeChamp = $typeChamp;

        return $this;
    }

    /**
     * Get typeChamp
     *
     * @return string
     */
    public function getTypeChamp()
    {
        return $this->typeChamp;
    }

    /**
     * Set valeurPossible
     *
     * @param array $valeurPossible
     *
     * @return LigneQuestionnaire
     */
    public function setValeurPossible($valeurPossible)
    {
        $this->valeurPossible = $valeurPossible;

        return $this;
    }

    /**
     * Get valeurPossible
     *
     * @return array
     */
    public function getValeurPossible()
    {
        return $this->valeurPossible;
    }

    /**
     * Set requis
     *
     * @param boolean $requis
     *
     * @return LigneQuestionnaire
     */
    public function setRequis($requis)
    {
        $this->requis = $requis;

        return $this;
    }

    /**
     * Get requis
     *
     * @return bool
     */
    public function getRequis()
    {
        return $this->requis;
    }

    /**
     * Set libAide
     *
     * @param string $libAide
     *
     * @return LigneQuestionnaire
     */
    public function setLibAide(string $libAide)
    {
        $this->libAide = (string)$libAide;

        return $this;
    }

    /**
     * Get libAide
     *
     * @return string
     */
    public function getLibAide()
    {
        return $this->libAide;
    }

    /**
     * Set optionChamp
     *
     * @param array $optionChamp
     *
     * @return LigneQuestionnaire
     */
    public function setOptionChamp($optionChamp)
    {
        $this->optionChamp = $optionChamp;

        return $this;
    }

    /**
     * Get optionChamp
     *
     * @return array
     */
    public function getOptionChamp()
    {
        return $this->optionChamp;
    }

    /**
     * Set valeurDefaut
     *
     * @param array $valeurDefaut
     *
     * @return LigneQuestionnaire
     */
    public function setValeurDefaut($valeurDefaut)
    {
        $this->valeurDefaut = $valeurDefaut;

        return $this;
    }

    /**
     * Get valeurDefaut
     *
     * @return array
     */
    public function getValeurDefaut()
    {
        return $this->valeurDefaut;
    }

    /**
     * Set multiple
     *
     * @param boolean $multiple
     *
     * @return LigneQuestionnaire
     */
    public function setMultiple($multiple)
    {
        $this->multiple = $multiple;

        return $this;
    }

    /**
     * Get multiple
     *
     * @return boolean
     */
    public function getMultiple()
    {
        return $this->multiple;
    }

    /**
     * Set questionnaire
     *
     * @param \PS\GestionBundle\Entity\Questionnaire $questionnaire
     *
     * @return LigneQuestionnaire
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
     * Set ordre
     *
     * @param integer $ordre
     *
     * @return LigneQuestionnaire
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Get ordre
     *
     * @return integer
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * Set statut
     *
     * @param boolean $statut
     *
     * @return LigneQuestionnaire
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
     * Constructor
     */
    public function __construct()
    {
        $this->valeurs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->valeurPossible = [];
        $this->setPourcentage(0);
    }

    /**
     * Set pourcentage
     *
     * @param string $pourcentage
     *
     * @return LigneQuestionnaire
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
     * Add valeur
     *
     * @param \PS\GestionBundle\Entity\ValeurLigneQuestionnaire $valeur
     *
     * @return LigneQuestionnaire
     */
    public function addValeur(\PS\GestionBundle\Entity\ValeurLigneQuestionnaire $valeur)
    {
        $this->valeurs[] = $valeur;
        $valeur->setLigne($this);
        return $this;
    }

    /**
     * Remove valeur
     *
     * @param \PS\GestionBundle\Entity\ValeurLigneQuestionnaire $valeur
     */
    public function removeValeur(\PS\GestionBundle\Entity\ValeurLigneQuestionnaire $valeur)
    {
        $this->valeurs->removeElement($valeur);
    }

    /**
     * Get valeurs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getValeurs()
    {
        return $this->valeurs;
    }

    /**
     * Set parent
     *
     * @param \PS\GestionBundle\Entity\LigneQuestionnaire $parent
     *
     * @return LigneQuestionnaire
     */
    public function setParent(\PS\GestionBundle\Entity\LigneQuestionnaire $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \PS\GestionBundle\Entity\LigneQuestionnaire
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add enfant
     *
     * @param \PS\GestionBundle\Entity\LigneQuestionnaire $enfant
     *
     * @return LigneQuestionnaire
     */
    public function addEnfant(\PS\GestionBundle\Entity\LigneQuestionnaire $enfant)
    {
        $this->enfants[] = $enfant;

        return $this;
    }

    /**
     * Remove enfant
     *
     * @param \PS\GestionBundle\Entity\LigneQuestionnaire $enfant
     */
    public function removeEnfant(\PS\GestionBundle\Entity\LigneQuestionnaire $enfant)
    {
        $this->enfants->removeElement($enfant);
    }

    /**
     * Get enfants
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEnfants()
    {
        return $this->enfants;
    }
}
