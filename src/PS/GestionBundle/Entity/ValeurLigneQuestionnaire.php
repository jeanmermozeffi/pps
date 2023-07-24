<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ValeurLigneQuestionnaire
 *
 * @ORM\Table(name="valeur_ligne_questionnaire")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\ValeurLigneQuestionnaireRepository")
 */
class ValeurLigneQuestionnaire
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
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

    /**
     * @var int
     *
     * @ORM\Column(name="pourcentage", type="smallint")
     */
    private $pourcentage;


    /**
     * @ORM\ManyToOne(targetEntity="LigneQuestionnaire", inversedBy="valeurs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ligne;



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
     * @return ValeurLigneQuestionnaire
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
     * Set pourcentage
     *
     * @param integer $pourcentage
     *
     * @return ValeurLigneQuestionnaire
     */
    public function setPourcentage($pourcentage)
    {
        $this->pourcentage = $pourcentage;

        return $this;
    }

    /**
     * Get pourcentage
     *
     * @return int
     */
    public function getPourcentage()
    {
        return $this->pourcentage;
    }

    /**
     * Set ligne
     *
     * @param \PS\GestionBundle\Entity\LigneQuestionnaire $ligne
     *
     * @return ValeurLigneQuestionnaire
     */
    public function setLigne(\PS\GestionBundle\Entity\LigneQuestionnaire $ligne)
    {
        $this->ligne = $ligne;

        return $this;
    }

    /**
     * Get ligne
     *
     * @return \PS\GestionBundle\Entity\LigneQuestionnaire
     */
    public function getLigne()
    {
        return $this->ligne;
    }
}
