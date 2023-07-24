<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FicheTraitement
 *
 * @ORM\Table(name="fiche_traitement")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\FicheTraitementRepository")
 */
class FicheTraitement
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
     *  @Assert\NotBlank(message="Veuillez renseigner le traitement")
     */
    private $libelle;


    /**
     * @ORM\ManyToOne(targetEntity="FicheAffection", inversedBy="traitements")
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
     * @return FicheTraitement
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
     * Set fiche
     *
     * @param \PS\GestionBundle\Entity\FicheAffection $fiche
     *
     * @return FicheTraitement
     */
    public function setFiche(\PS\GestionBundle\Entity\FicheAffection $fiche)
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
