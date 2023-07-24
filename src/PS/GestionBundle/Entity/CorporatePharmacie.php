<?php
namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use APY\DataGridBundle\Grid\Mapping as GRID;
use JMS\Serializer\Annotation\Groups;
use PS\UtilisateurBundle\Entity\Personne;


/**
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\CorporatePharmacieRepository")
 */
class CorporatePharmacie
{
    /**
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @GRID\Column(title="ID", primary=true)
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="Pharmacie")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pharmacie;


    /**
     * @ORM\ManyToOne(targetEntity="Corporate", inversedBy="corporatePharmacies")
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
     * Set pharmacie
     *
     * @param \PS\GestionBundle\Entity\Pharmacie $pharmacie
     *
     * @return CorporatePharmacie
     */
    public function setPharmacie(\PS\GestionBundle\Entity\Pharmacie $pharmacie)
    {
        $this->pharmacie = $pharmacie;

        return $this;
    }

    /**
     * Get pharmacie
     *
     * @return \PS\GestionBundle\Entity\Pharmacie
     */
    public function getPharmacie()
    {
        return $this->pharmacie;
    }

    /**
     * Set corporate
     *
     * @param \PS\GestionBundle\Entity\Corporate $corporate
     *
     * @return CorporatePharmacie
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
