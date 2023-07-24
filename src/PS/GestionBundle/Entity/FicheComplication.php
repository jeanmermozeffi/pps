<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FicheComplication
 *
 * @ORM\Table(name="fiche_complication")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\FicheComplicationRepository")
 */
class FicheComplication
{
     const LIBELLES = ['1er trimestre', '2ème et 3è trimestre', 'Période néonatale'];
     const HAS_MORE = true;
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
     * @Assert\NotBlank(message="Veuillez renseigner la libellé")
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="details", type="text")
     */
    private $details;



     /**
     * @ORM\ManyToOne(targetEntity="Fiche", inversedBy="complications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fiche;



    /**
     * @var string
     *
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private $etat;


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
     * @return FicheComplication
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
     * Set details
     *
     * @param string $details
     *
     * @return FicheComplication
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set fiche
     *
     * @param \PS\GestionBundle\Entity\Fiche $fiche
     *
     * @return FicheComplication
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
     * Set etat
     *
     * @param boolean $etat
     *
     * @return FicheComplication
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
