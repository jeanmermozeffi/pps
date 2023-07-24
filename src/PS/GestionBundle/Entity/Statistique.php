<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * Statistique
 *
 * @ORM\Table(name="statistique")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\StatistiqueRepository")
 * @GRID\Source(columns="id,libStatistique,champ.libChamp,valeurStatistique", filterable=false, sortable=false)
 */
class Statistique
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
     * @ORM\Column(name="lib_statistique", type="string", length=100)
     * @GRID\Column(title="Libellé")
     */
    private $libStatistique;


    /**
     * @ORM\Column(name="valeur_statistique", type="string", nullable=true)
     * @GRID\Column(title="Valeur prise en compte")
     */
    private $valeurStatistique;


    /**
     * @ORM\ManyToOne(targetEntity="PS\SpecialiteBundle\Entity\Champ")
     * @GRID\Column(field="champ.libChamp", title="Champ concerné")
     */
    private $champ;


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
     * Set libStatistique
     *
     * @param string $libStatistique
     *
     * @return Statistique
     */
    public function setLibStatistique($libStatistique)
    {
        $this->libStatistique = $libStatistique;

        return $this;
    }

    /**
     * Get libStatistique
     *
     * @return string
     */
    public function getLibStatistique()
    {
        return $this->libStatistique;
    }

    /**
     * Set valeurStatistique
     *
     * @param string $valeurStatistique
     *
     * @return Statistique
     */
    public function setValeurStatistique($valeurStatistique)
    {
        $this->valeurStatistique = $valeurStatistique;

        return $this;
    }

    /**
     * Get valeurStatistique
     *
     * @return string
     */
    public function getValeurStatistique()
    {
        return $this->valeurStatistique;
    }

    /**
     * Set champ
     *
     * @param \PS\SpecialiteBundle\Entity\Champ $champ
     *
     * @return Statistique
     */
    public function setChamp(\PS\SpecialiteBundle\Entity\Champ $champ = null)
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
}
