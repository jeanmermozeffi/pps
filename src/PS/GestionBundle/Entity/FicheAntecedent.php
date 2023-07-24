<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FicheAntecedent
 *
 * @ORM\Table(name="fiche_antecedent")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\FicheAntecedentRepository")
 */
class FicheAntecedent
{
    const LIBELLES = ['Famille', 'Diabète', 'IMC>25kg/m2', 'Macrosomie'];
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
     * @Assert\NotBlank(message="Veuillez renseigner le type d'antécédent")
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="valeur", type="boolean")
     */
    private $valeur;




    /**
     * @ORM\ManyToOne(targetEntity="Fiche", inversedBy="complications")
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
     * @return FicheAntecedent
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
     * @return FicheAntecedent
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
     * @return FicheAntecedent
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
