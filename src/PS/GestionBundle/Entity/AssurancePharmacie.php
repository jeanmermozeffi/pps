<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AssurancePharmacie
 *
 * @ORM\Table(name="assurance_pharmacie")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\AssurancePharmacieRepository")
 */
class AssurancePharmacie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Pharmacie", inversedBy="assurances")
     */
    private $pharmacie;

    /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Assurance")
     */
    private $assurance;


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
     * Set pharmacie
     *
     * @param \PS\GestionBundle\Entity\Pharmacie $pharmacie
     *
     * @return AssurancePharmacie
     */
    public function setPharmacie(\PS\GestionBundle\Entity\Pharmacie $pharmacie = null)
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
     * Set assurance
     *
     * @param \PS\ParametreBundle\Entity\Assurance $assurance
     *
     * @return AssurancePharmacie
     */
    public function setAssurance(\PS\ParametreBundle\Entity\Assurance $assurance = null)
    {
        $this->assurance = $assurance;

        return $this;
    }

    /**
     * Get assurance
     *
     * @return \PS\ParametreBundle\Entity\Assurance
     */
    public function getAssurance()
    {
        return $this->assurance;
    }
}
