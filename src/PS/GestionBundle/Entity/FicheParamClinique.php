<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FicheParamClinique
 *
 * @ORM\Table(name="fiche_param_clinique")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\FicheParamCliniqueRepository")
 */
class FicheParamClinique
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
     * @ORM\Column(name="valeur", type="string", length=20)
     */
    private $valeur;


    /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Constante")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Veuillez sélectionner une constante")
     */
    private $constante;




     /**
     * @ORM\ManyToOne(targetEntity="FicheAffection", inversedBy="constantes")
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
     * @return FicheParamClinique
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
     * Set constante
     *
     * @param \PS\ParametreBundle\Entity\Constante $constante
     *
     * @return FicheParamClinique
     */
    public function setConstante(\PS\ParametreBundle\Entity\Constante $constante)
    {
        $this->constante = $constante;

        return $this;
    }

    /**
     * Get constante
     *
     * @return \PS\ParametreBundle\Entity\Constante
     */
    public function getConstante()
    {
        return $this->constante;
    }

    /**
     * Set fiche
     *
     * @param \PS\GestionBundle\Entity\FicheAffection $fiche
     *
     * @return FicheParamClinique
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
