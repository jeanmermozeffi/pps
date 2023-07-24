<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DiagnosticQuestionnaire
 *
 * @ORM\Table(name="diagnostic_questionnaire")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\DiagnosticQuestionnaireRepository")
 */
class DiagnosticQuestionnaire
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
     * @var string
     *
     * @ORM\Column(name="libelle", type="text")
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="stat", type="string", options={"default":""})
     */
    private $stat;

    /**
     * @var int
     *
     * @ORM\Column(name="pourcentage_min", type="smallint")
     */
    private $pourcentageMin;

    /**
     * @var int
     *
     * @ORM\Column(name="pourcentage_max", type="smallint")
     */
    private $pourcentageMax;


    /**
     * @var bool
     *
     * @ORM\Column(name="urgence", type="boolean")
     */
    private $urgence = false;




    /**
     * @ORM\ManyToOne(targetEntity="Questionnaire", inversedBy="diagnostics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $questionnaire;


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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return DiagnosticQuestionnaire
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
     * Set pourcentageMin
     *
     * @param integer $pourcentageMin
     *
     * @return DiagnosticQuestionnaire
     */
    public function setPourcentageMin($pourcentageMin)
    {
        $this->pourcentageMin = $pourcentageMin;

        return $this;
    }

    /**
     * Get pourcentageMin
     *
     * @return int
     */
    public function getPourcentageMin()
    {
        return $this->pourcentageMin;
    }

    /**
     * Set pourcentageMax
     *
     * @param integer $pourcentageMax
     *
     * @return DiagnosticQuestionnaire
     */
    public function setPourcentageMax($pourcentageMax)
    {
        $this->pourcentageMax = $pourcentageMax;

        return $this;
    }

    /**
     * Get pourcentageMax
     *
     * @return int
     */
    public function getPourcentageMax()
    {
        return $this->pourcentageMax;
    }

    /**
     * Set questionnaire
     *
     * @param \PS\GestionBundle\Entity\Questionnaire $questionnaire
     *
     * @return DiagnosticQuestionnaire
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
     * Set urgence
     *
     * @param boolean $urgence
     *
     * @return DiagnosticQuestionnaire
     */
    public function setUrgence($urgence)
    {
        $this->urgence = $urgence;

        return $this;
    }

    /**
     * Get urgence
     *
     * @return boolean
     */
    public function getUrgence()
    {
        return $this->urgence;
    }

    /**
     * Set stat
     *
     * @param string $stat
     *
     * @return DiagnosticQuestionnaire
     */
    public function setStat($stat)
    {
        $this->stat = $stat;

        return $this;
    }

    /**
     * Get stat
     *
     * @return string
     */
    public function getStat()
    {
        return $this->stat;
    }
}
