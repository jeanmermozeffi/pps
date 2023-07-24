<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use APY\DataGridBundle\Grid\Mapping as GRID;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\AssuranceRepository")
 * @GRID\Source(columns="id,identifiant,nom", sortable=false, filterable=false)
 */
class Assurance
{
    /**
     * @Expose
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @GRID\Column(title="ID", primary=true)
     * @Groups({"assurance"})
     */
    private $id;

    /**
     * 
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank()
     * @GRID\Column(title="assurance.form.identifiant", defaultOperator="rlike", operators={"rlike"}, filterable=true, size=20, align="center")
     * @Groups({"assurance"})
     */
    private $identifiant;

    /**
     * @Expose
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank()
     * @GRID\Column(title="assurance.form.nom", defaultOperator="rlike", operators={"rlike"}, filterable=true)
     * @Groups({"assurance"})
     */
    private $nom;


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
     * Set identifiant
     *
     * @param string $identifiant
     * @return Assurance
     */
    public function setIdentifiant($identifiant)
    {
        $this->identifiant = $identifiant;

        return $this;
    }

    /**
     * Get identifiant
     *
     * @return string 
     */
    public function getIdentifiant()
    {
        return $this->identifiant;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Assurance
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
}
