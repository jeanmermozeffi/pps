<?php
namespace PS\ParametreBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;

/**
 * @ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\ReligionRepository")
 * @GRID\Source(columns="id, nom", sortable=false, filterable=false)
 */
class Religion
{
    /**
     * @Expose
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @GRID\Column(title="ID", primary=true)
     * @Groups({"patient", "religion"})
     */
    private $id;

    /**
     * @Expose
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank()
     * @GRID\Column(title="patient.form.religion")
     * @Groups({"patient", "religion"})
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
     * Set nom
     *
     * @param string $nom
     * @return Religion
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
