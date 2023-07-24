<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 * LienParente
 *
 * @ORM\Table(name="lien_parente")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\LienParenteRepository")
 */
class LienParente
{
    /**
     * @Expose
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"patient-associe", "lien-parente"})
     */
    private $id;

    /**
     * @Expose
     * @var string
     * @SerializedName("lien")
     * @ORM\Column(name="lib_lien_parente", type="string", length=30, unique=true)
     * @Groups({"patient-associe", "lien-parente"})
     */
    private $libLienParente;


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
     * Set libLienParente
     *
     * @param string $libLienParente
     *
     * @return LienParente
     */
    public function setLibLienParente($libLienParente)
    {
        $this->libLienParente = $libLienParente;

        return $this;
    }

    /**
     * Get libLienParente
     *
     * @return string
     */
    public function getLibLienParente()
    {
        return $this->libLienParente;
    }
}
