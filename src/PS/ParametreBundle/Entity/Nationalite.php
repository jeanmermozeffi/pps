<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Nationalite
 * @ExclusionPolicy("all")
 *
 * @ORM\Table(name="nationalite")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\NationaliteRepository")
 * @GRID\Source(columns="id, libNationalite", sortable=false, filterable=false)
 */
class Nationalite
{
    /**
     * @var int
     * @Expose
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(title="ID", primary=true)
     * @Groups({"patient", "nationalite"})
     */
    private $id;

    /**
     * @Expose
     * @var string
     * @SerializedName("libelle")
     * @ORM\Column(name="lib_nationalite", type="string", length=200, unique=true)
     * @GRID\Column(title="patient.form.nationalite")
     * @Groups({"patient", "nationalite"})
     */
    private $libNationalite;


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
     * Set libNationalite
     *
     * @param string $libNationalite
     *
     * @return Nationalite
     */
    public function setLibNationalite($libNationalite)
    {
        $this->libNationalite = $libNationalite;

        return $this;
    }

    /**
     * Get libNationalite
     *
     * @return string
     */
    public function getLibNationalite()
    {
        return $this->libNationalite;
    }
}
