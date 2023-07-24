<?php
namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use APY\DataGridBundle\Grid\Mapping as GRID;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;



/**
 * @ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\VilleRepository")
 * @GRID\Source(columns="id, nom, region.nom", sortable=false, filterable=false)
 */
class Ville
{
    /**
     * @Expose
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @GRID\Column(title="ID", primary=true)
     * @Groups({"ville"})
     */
    private $id;
    
    /**
     * @Expose
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank()
     * @GRID\Column(title="vlle.form.nom")
     * @Groups({"ville"})
     */    
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity="Region")
     * @GRID\Column(field="region.nom", title="regon.form.nom", filterable=true, filter="select", operatorsVisible=false)
     * @Groups({"region"})
     */    
    private $region;


    /**
     * @ORM\ManyToOne(targetEntity="Pays")
     * @Assert\NotBlank()
     * @GRID\Column(field="pays.nom", title="pays.form.nom", filterable=true, filter="select", operatorsVisible=false)
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
     * @return Ville
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
     * Set region
     *
     * @param \PS\ParametreBundle\Entity\Region $region
     * @return Ville
     */
    public function setRegion(\PS\ParametreBundle\Entity\Region $region = null)
    {
        $this->region = $region;
    
        return $this;
    }

    /**
     * Get region
     *
     * @return \PS\ParametreBundle\Entity\Region
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set pays
     *
     * @param \PS\ParametreBundle\Entity\Pays $pays
     *
     * @return Ville
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
