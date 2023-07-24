<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FicheGlycemie
 *
 * @ORM\Table(name="fiche_glycemie")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\FicheGlycemieRepository")
 */
class FicheGlycemie
{
      const LIBELLES = ['Glycémie CAP(GC)', 'Glycémie Véneuse (GV)'];
      const VALUES = ['<0,80 g/l', '0,80 - 0,92 g/l', '>0,92 g/l'];
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
     * @Assert\NotBlank(message="Veuillez renseigner l'examen")
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="valeur", columnDefinition="TINYINT")
     */
    private $valeur;


    /**
     * @var string
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private $etat;


    /**
     * @ORM\ManyToOne(targetEntity="Fiche", inversedBy="glycemies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fiche;



    public function __construct()
    {
        $this->setEtat(false);
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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return FicheGlycemie
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    

  
    /**
     * Set fiche
     *
     * @param \PS\GestionBundle\Entity\Fiche $fiche
     *
     * @return FicheGlycemie
     */
    public function setFiche(\PS\GestionBundle\Entity\Fiche $fiche)
    {
        $this->fiche = $fiche;

        return $this;
    }

    /**
     * Get fiche
     *
     * @return \PS\GestionBundle\Entity\Fiche
     */
    public function getFiche()
    {
        return $this->fiche;
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
     * Set valeur
     *
     * @param string $valeur
     *
     * @return FicheGlycemie
     */
    public function setValeur($valeur)
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
     * Set etat
     *
     * @param boolean $etat
     *
     * @return FicheGlycemie
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return boolean
     */
    public function getEtat()
    {
        return $this->etat;
    }
}
