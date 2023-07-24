<?php

namespace PS\SpecialiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DonneeChamp
 *
 * @ORM\Table(name="donnee_champ")
 * @ORM\Entity(repositoryClass="PS\SpecialiteBundle\Repository\DonneeChampRepository")
 */
class DonneeChamp
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
     * @ORM\Column(name="valeur_champ", type="text")
     */
    private $valeurChamp;

     /**
     * @ORM\ManyToOne(targetEntity="Champ")
     * @ORM\JoinColumn(nullable=false)
     */
    private $champ;


    /**
     * @ORM\ManyToOne(targetEntity="Fiche")
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
     * Set valeurChamp
     *
     * @param string $valeurChamp
     *
     * @return DonneeChamp
     */
    public function setValeurChamp($valeurChamp)
    {
        $this->valeurChamp = $valeurChamp;

        return $this;
    }

    /**
     * Get valeurChamp
     *
     * @return string
     */
    public function getValeurChamp()
    {
        return $this->valeurChamp;
    }

    /**
     * Set champ
     *
     * @param \PS\SpecialiteBundle\Entity\Champ $champ
     *
     * @return DonneeChamp
     */
    public function setChamp(\PS\SpecialiteBundle\Entity\Champ $champ)
    {
        $this->champ = $champ;

        return $this;
    }

    /**
     * Get champ
     *
     * @return \PS\SpecialiteBundle\Entity\Champ
     */
    public function getChamp()
    {
        return $this->champ;
    }

    
    /**
     * Set fiche
     *
     * @param \PS\SpecialiteBundle\Entity\Fiche $fiche
     *
     * @return DonneeChamp
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
}
