<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * Statut
 * @ExclusionPolicy("all")
 * @ORM\Table(name="statut")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\StatutRepository")
 */
class Statut
{
    /**
     * @var int
     * @Expose
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"statut"})
     */
    private $id;

    /**
     * @var string
     * @Expose
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     * @Groups({"statut"})
     */
    private $libelle;


    /**
     * @ORM\ManyToOne(targetEntity="TypeStatut")
     */
    private $type;


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
     * @return Statut
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
     * Set type
     *
     * @param \PS\ParametreBundle\Entity\TypeStatut $type
     *
     * @return Statut
     */
    public function setType(\PS\ParametreBundle\Entity\TypeStatut $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \PS\ParametreBundle\Entity\TypeStatut
     */
    public function getType()
    {
        return $this->type;
    }
}
