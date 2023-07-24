<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use APY\DataGridBundle\Grid\Mapping as GRID;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Constante
 * @ExclusionPolicy("all")
 * @ORM\Table(name="constante")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\ConstanteRepository")
 */
class Constante
{
    const TYPES = ["personnel", "médical"];
    /**
     * @Expose
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"constante"})
     */
    private $id;

    /**
     * @var string
     * @Expose
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     * @Assert\NotBlank(message="Veuillez renseigner le libellé")
     * @Groups({"constante"})
     */
    private $libelle;


    /**
     * @Expose
     * @var string
     *
     * @ORM\Column(type="string", length=10)
     * @Groups({"constante"})
     */
    private $unite;


    /**
     * @var string
     *
     * @ORM\Column(type="json_array")
     * @GRID\Column(type="array")
     * @Assert\Count(min=1, minMessage="Veuillez sélectionner au moins un type")
     */
    private $types;




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
     * @return Constante
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
     * Set unite
     *
     * @param string $unite
     *
     * @return Constante
     */
    public function setUnite($unite)
    {
        $this->unite = $unite;

        return $this;
    }

    /**
     * Get unite
     *
     * @return string
     */
    public function getUnite()
    {
        return $this->unite;
    }


    /**
     * Set types
     *
     * @param array $types
     *
     * @return ListeAntecedent
     */
    public function setTypes($types)
    {
        $this->types = $types;

        return $this;
    }

    /**
     * Get types
     *
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }
}
