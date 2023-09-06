<?php
namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use PS\GestionBundle\Entity\Corporate;
use PS\ParametreBundle\Entity\Specialite;
use PS\UtilisateurBundle\Entity\Personne;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Constraints as Assert;




/**
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\CorporateSpecialiteRepository")
 */
class CorporateSpecialite
{
    /**
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @GRID\Column(title="ID", primary=true)
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Specialite", inversedBy="corporates")
     * @ORM\JoinColumn(name="specialite_id", referencedColumnName="id")
     */
    private $specialite;


    /**
     * @ORM\ManyToOne(targetEntity="PS\GestionBundle\Entity\Corporate", inversedBy="corporateSpecialites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $corporate;

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
     * Set specialite
     *
     * @param \PS\ParametreBundle\Entity\Specialite $specialite
     *
     * @return CorporateSpecialite
     */
    public function setSpecialite(\PS\ParametreBundle\Entity\Specialite $specialite)
    {
        $this->specialite = $specialite;

        return $this;
    }

    /**
     * Get specialite
     *
     * @return \PS\ParametreBundle\Entity\Specialite
     */
    public function getSpecialite()
    {
        return $this->specialite;
    }

    /**
     * Set corporate
     *
     * @param \PS\GestionBundle\Entity\Corporate $corporate
     *
     * @return CorporateSpecialite
     */
    public function setCorporate(\PS\GestionBundle\Entity\Corporate $corporate)
    {
        $this->corporate = $corporate;

        return $this;
    }

    /**
     * Get corporate
     *
     * @return \PS\GestionBundle\Entity\Corporate
     */
    public function getCorporate()
    {
        return $this->corporate;
    }
}
