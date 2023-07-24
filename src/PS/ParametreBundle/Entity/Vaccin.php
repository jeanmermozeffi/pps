<?php
/**
 * Created by PhpStorm.
 * User: Mikhail
 * Date: 27/06/2017
 * Time: 00:55
 */

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use APY\DataGridBundle\Grid\Mapping as GRID;


/**
 * @ORM\Entity
 * @GRID\Source(columns="id,nom,details", sortable=false)
 */

class Vaccin {
    /**
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @GRID\Column(title="ID", primary=true, filterable=false)
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank()
     * @GRID\Column(title="Nom", operatorsVisible=false)
     */
    private $nom;

    /**
     * @ORM\Column(type="string",length=255)
     * @GRID\Column(title="Détails", operatorsVisible=false, operators={"rlike"}, defaultOperator="rlike", filter="select")
     */
    private $details;


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
     * @return Vaccin
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
     * Set details
     *
     * @param string $details
     * @return Vaccin
     */
    public function setDetails($details)
    {
        $this->details = $details;
    
        return $this;
    }

    /**
     * Get details
     *
     * @return string 
     */
    public function getDetails()
    {
        return $this->details;
    }
}