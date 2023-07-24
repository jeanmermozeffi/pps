<?php

namespace PS\SpecialiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChampStatistique
 *
 * @ORM\Table(name="champ_statistique")
 * @ORM\Entity(repositoryClass="PS\SpecialiteBundle\Repository\ChampStatistiqueRepository")
 */
class ChampStatistique
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
     * @var int
     *
     * @ORM\Column(name="priorite_champ", type="integer")
     */
    private $prioriteChamp;


    /**
     * @ORM\ManyToOne(targetEntity="Champ")
     */
    private $champ;


    /**
     * @ORM\ManyToOne(targetEntity="PS\GestionBundle\Entity\Statistique")
     */
    private $statistique;

     /**
     * @var int
     *
     * @ORM\Column(name="valeur_champ_statistique", type="integer", nullable=true)
     */
    private $valeurChampStatistique;


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
     * Set prioriteChamp
     *
     * @param integer $prioriteChamp
     *
     * @return ChampStatistique
     */
    public function setPrioriteChamp($prioriteChamp)
    {
        $this->prioriteChamp = $prioriteChamp;

        return $this;
    }

    /**
     * Get prioriteChamp
     *
     * @return int
     */
    public function getPrioriteChamp()
    {
        return $this->prioriteChamp;
    }
}

