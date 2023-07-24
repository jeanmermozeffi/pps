<?php
namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * @ORM\Entity
 * @GRID\Source(columns="id,nom,details", sortable=false)
 */
class Affection
{
    /**
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @GRID\Column(title="Numéro", primary=true, size=10, align="center")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank()
     * @GRID\Column(title="affection.form.nom")
     */    
    private $nom;

    /**
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank()
     * @GRID\Column(title="affection.form.details", filterable=false)
     */    
    private $details;


    /**
    * @ORM\Column(name="depistage", type="boolean")
    */
    private $depistage = 0;

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
     * @return Affection
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
     * @return Affection
     */
    public function setDetails(string $details)
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

    /**
     * Set depistage
     *
     * @param boolean $depistage
     *
     * @return Affection
     */
    public function setDepistage($depistage)
    {
        $this->depistage = $depistage;

        return $this;
    }

    /**
     * Get depistage
     *
     * @return boolean
     */
    public function getDepistage()
    {
        return boolval($this->depistage);
    }
}
