<?php
namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Traitement
{
    /**
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */    
    private $libTraitement;


     /**
     * @ORM\ManyToMany(targetEntity="PS\ParametreBundle\Entity\Specialite")
     * @ORM\JoinTable(name="traitement_specialite")
     */
    private $specialites;
    


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
     * Set libTraitement
     *
     * @param string $libTraitement
     *
     * @return Traitement
     */
    public function setLibTraitement($libTraitement)
    {
        $this->libTraitement = $libTraitement;

        return $this;
    }

    /**
     * Get libTraitement
     *
     * @return string
     */
    public function getLibTraitement()
    {
        return $this->libTraitement;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->specialites = new \Doctrine\Common\Collections\ArrayCollection();
    }

   

    /**
     * Add specialite
     *
     * @param \PS\ParametreBundle\Entity\Specialite $specialite
     *
     * @return Traitement
     */
    public function addSpecialite(\PS\ParametreBundle\Entity\Specialite $specialite)
    {
        $this->specialites[] = $specialite;

        return $this;
    }

    /**
     * Remove specialite
     *
     * @param \PS\ParametreBundle\Entity\Specialite $specialite
     */
    public function removeSpecialite(\PS\ParametreBundle\Entity\Specialite $specialite)
    {
        $this->specialites->removeElement($specialite);
    }

    /**
     * Get specialites
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSpecialites()
    {
        return $this->specialites;
    }
}
