<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;


/**
 * @ExclusionPolicy("all")
 * 
 * @ORM\Entity
 * @GRID\Source(columns="id, nom", sortable=false, filterable=false)
 */
class Region
{
    /**
     * @var int
     * @Expose
     * 
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @GRID\Column(title="Id", primary=true)
     * @Groups({"region"})
     */
    private $id;

    /**
     * 
     * @Expose
     * @var string
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank()
     * @GRID\Column(title="region.form.nom", filterable=true, operatorsVisible=false)
     * @Groups({"region"})
     */
    private $nom;

    /**
     * @Exclude
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Pays", cascade={"persist"})
     * @GRID\Column(visible=false)
     * @Groups({"region"})
     */
    private $pays;

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
     * Set nom
     *
     * @param string $nom
     * @return Region
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set pays
     *
     * @param \PS\ParametreBundle\Entity\Pays $pays
     *
     * @return Region
     */
    public function setPays(\PS\ParametreBundle\Entity\Pays $pays = null)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return \PS\ParametreBundle\Entity\Pays
     */
    public function getPays()
    {
        return $this->pays;
    }
}
