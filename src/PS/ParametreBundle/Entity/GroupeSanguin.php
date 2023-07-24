<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use APY\DataGridBundle\Grid\Mapping as GRID;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;



/**
 * @ExclusionPolicy("all")
 * @ORM\Entity
 * @GRID\Source(columns="id,code,libelle", sortable=false, filterable=false)
 */
class GroupeSanguin
{
    /**
     * @Expose
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @GRID\Column(title="ID", primary=true)
     * @Groups({"groupe-sanguin"})
     */
    private $id;

    /**
     * @Expose
     * @ORM\Column(type="string",length=3)
     * @ORM\Column(type="integer")
     * @GRID\Column(title="Code")
     * @Groups({"groupe-sanguin"})
     */
    private $code;

    /**
     * @Exclude
     * @ORM\Column(type="string",length=20)
     * @Assert\NotBlank()
     * @GRID\Column(title="Libellé")
     */
    private $libelle;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return GroupeSanguin
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return GroupeSanguin
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
}
