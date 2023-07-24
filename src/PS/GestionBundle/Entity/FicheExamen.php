<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FicheExamen
 *
 * @ORM\Table(name="fiche_examen")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\FicheExamenRepository")
 */
class FicheExamen
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
     * @ORM\Column(name="valeur", type="text")
     */
    private $valeur;


     /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\ListeExamen")
     * @ORM\JoinColumn(nullable=false)
     */
    private $examen;

      /**
     * @ORM\ManyToOne(targetEntity="FicheAffection", inversedBy="examens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fiche;


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
     * Set valeur
     *
     * @param string $valeur
     *
     * @return FicheExamen
     */
    public function setValeur(string $valeur)
    {
        $this->valeur = $valeur;

        return $this;
    }

    /**
     * Get valeur
     *
     * @return string
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * Set examen
     *
     * @param \PS\ParametreBundle\Entity\ListeExamen $examen
     *
     * @return FicheExamen
     */
    public function setExamen(\PS\ParametreBundle\Entity\ListeExamen $examen)
    {
        $this->examen = $examen;

        return $this;
    }

    /**
     * Get examen
     *
     * @return \PS\ParametreBundle\Entity\ListeExamen
     */
    public function getExamen()
    {
        return $this->examen;
    }

    /**
     * Set fiche
     *
     * @param \PS\GestionBundle\Entity\FicheAffection $fiche
     *
     * @return FicheExamen
     */
    public function setFiche(\PS\GestionBundle\Entity\FicheAffection $fiche)
    {
        $this->fiche = $fiche;

        return $this;
    }

    /**
     * Get fiche
     *
     * @return \PS\GestionBundle\Entity\FicheAffection
     */
    public function getFiche()
    {
        return $this->fiche;
    }
}
