<?php
namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @GRID\Source(columns="id,nom", sortable=false, filterable=false)
 */
class Medicament
{
    /**
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @GRID\Column(title="ID", primary=true)
     * @Groups({"ordonnance"})
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank()
     * @GRID\Column(title="medicament.form.nom")
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
     * @return Medicament
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