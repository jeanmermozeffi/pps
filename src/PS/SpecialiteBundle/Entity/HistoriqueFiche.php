<?php

namespace PS\SpecialiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistoriqueFiche
 *
 * @ORM\Table(name="historique_fiche")
 * @ORM\Entity(repositoryClass="PS\SpecialiteBundle\Repository\HistoriqueFicheRepository")
 */
class HistoriqueFiche
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
     * @ORM\Column(name="date_historique_fiche", type="datetime")
     */
    private $dateHistoriqueFiche;


    /**
     * @ORM\ManyToOne(targetEntity="Fiche")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fiche;

     /**
     * @ORM\ManyToOne(targetEntity="Etape")
     * @ORM\JoinColumn(nullable=true)
     */
    private $etape;

    /**
     * @ORM\ManyToOne(targetEntity="PS\UtilisateurBundle\Entity\Utilisateur")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;

    /**
     * @ORM\Column(name="lib_historique_fiche", type="text")
     */
    private $libHistoriqueFiche;


    public function __construct()
    {
        $this->dateHistoriqueFiche = new \DateTime();
    }

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
     * Set dateHistoriqueFiche
     *
     * @param datetime_immutable $dateHistoriqueFiche
     *
     * @return HistoriqueFiche
     */
    public function setDateHistoriqueFiche($dateHistoriqueFiche)
    {
        $this->dateHistoriqueFiche = $dateHistoriqueFiche;

        return $this;
    }

    /**
     * Get dateHistoriqueFiche
     *
     * @return datetime_immutable
     */
    public function getDateHistoriqueFiche()
    {
        return $this->dateHistoriqueFiche;
    }

    /**
     * Set fiche
     *
     * @param \PS\SpecialiteBundle\Entity\Fiche $fiche
     *
     * @return HistoriqueFiche
     */
    public function setFiche(\PS\SpecialiteBundle\Entity\Fiche $fiche)
    {
        $this->fiche = $fiche;

        return $this;
    }

    /**
     * Get fiche
     *
     * @return \PS\SpecialiteBundle\Entity\Fiche
     */
    public function getFiche()
    {
        return $this->fiche;
    }

    /**
     * Set libHistoriqueFiche
     *
     * @param string $libHistoriqueFiche
     *
     * @return HistoriqueFiche
     */
    public function setLibHistoriqueFiche($libHistoriqueFiche)
    {
        $this->libHistoriqueFiche = $libHistoriqueFiche;

        return $this;
    }

    /**
     * Get libHistoriqueFiche
     *
     * @return string
     */
    public function getLibHistoriqueFiche()
    {
        return $this->libHistoriqueFiche;
    }

    /**
     * Set etape
     *
     * @param \PS\SpecialiteBundle\Entity\Etape $etape
     *
     * @return HistoriqueFiche
     */
    public function setEtape(\PS\SpecialiteBundle\Entity\Etape $etape)
    {
        $this->etape = $etape;

        return $this;
    }

    /**
     * Get etape
     *
     * @return \PS\SpecialiteBundle\Entity\Etape
     */
    public function getEtape()
    {
        return $this->etape;
    }

    /**
     * Set utilisateur
     *
     * @param \PS\UtilisateurBundle\Entity\Utilisateur $utilisateur
     *
     * @return HistoriqueFiche
     */
    public function setUtilisateur(\PS\UtilisateurBundle\Entity\Utilisateur $utilisateur)
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
}
