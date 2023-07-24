<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FicheConstante
 *
 * @ORM\Table(name="fiche_constante")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\FicheConstanteRepository")
 */
class FicheConstante
{
     const LIBELLES = ['Poids(Kg)', 'Taille', 'TA', 'Température'];
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
     * @Assert\NotBlank(message="Veuillez renseigner l'examen")
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="valeur", type="string", length=255, nullable=true)
     */
    private $valeur;


     /**
     * @ORM\ManyToOne(targetEntity="Fiche", inversedBy="constantes")
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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return FicheConstante
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
     * Set valeur
     *
     * @param string $valeur
     *
     * @return FicheConstante
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
     * Set fiche
     *
     * @param \PS\GestionBundle\Entity\Fiche $fiche
     *
     * @return FicheConstante
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
}
