<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * ListeAntecedent
 *
 * @ORM\Table(name="liste_antecedent")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\ListeAntecedentRepository")
 */
class ListeAntecedent
{
    const GROUPES = ["médical", "chirurgical", "gyneco-obstétrical"];
    const TYPES = ["personnel", "familial"];
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
     * @ORM\Column(name="libelle", type="string", length=255)
     * @Assert\NotBlank(message="Veuillez renseigner le libellé")
     */
    private $libelle;

  

     /**
     * @var string
     *
     * @ORM\Column(type="json_array")
     * @GRID\Column(type="array")
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
     * @return ListeAntecedent
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
