<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
/**
 * Etat
 *
 * @ORM\Table(name="etat")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\EtatRepository")
 * @GRID\Source(columns="id, libEtat, typeEtat.libTypeEtat", sortable=false, filterable=false)
 */
class Etat
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
     * @ORM\Column(name="lib_etat", type="string", length=100)
     * @GRID\Column(title="Libellé")
     */
    private $libEtat;


    /**
     * @ORM\ManyToOne(targetEntity="TypeEtat")
     * @ORM\JoinColumn(nullable=false)
     * @GRID\Column(field="typeEtat.libTypeEtat", title="Type")
     */
    private $typeEtat;


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
     * Set libEtat
     *
     * @param string $libEtat
     *
     * @return Etat
     */
    public function setLibEtat($libEtat)
    {
        $this->libEtat = $libEtat;

        return $this;
    }

    /**
     * Get libEtat
     *
     * @return string
     */
    public function getLibEtat()
    {
        return $this->libEtat;
    }

    /**
     * Set typeEtat
     *
     * @param \PS\ParametreBundle\Entity\TypeEtat $typeEtat
     *
     * @return Etat
     */
    public function setTypeEtat(\PS\ParametreBundle\Entity\TypeEtat $typeEtat)
    {
        $this->typeEtat = $typeEtat;

        return $this;
    }

    /**
     * Get typeEtat
     *
     * @return \PS\ParametreBundle\Entity\TypeEtat
     */
    public function getTypeEtat()
    {
        return $this->typeEtat;
    }
}
