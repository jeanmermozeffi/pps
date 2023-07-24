<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
/**
 * GardePharmacie
 *
 * @ORM\Table(name="garde_pharmacie")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\GardePharmacieRepository")
 */
class GardePharmacie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(visible=false)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut", type="date")
     * @GRID\Column(title="Du", operatorsVisible=false)
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="date")
     * @GRID\Column(title="Au",  operatorsVisible=false)
     */
    private $dateFin;


    /**
     * @ORM\OneToOne(targetEntity="Pharmacie")
     * @ORM\JoinColumn(nullable=false)
     * @GRID\Column(field="pharmacie.libPharmacie", title="Pharmacie", filter="select", selectFrom="source", operatorsVisible=false)
     * @GRID\Column(field="pharmacie.info.localisationPharmacie", title="Situation.", filterable=false)
     * @GRID\Column(field="pharmacie.info.contacts", title="Contacts", filterable=false)
     */
    private $pharmacie;


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
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return GardePharmacie
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return GardePharmacie
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set pharmacie
     *
     * @param \PS\GestionBundle\Entity\Pharmacie $pharmacie
     *
     * @return GardePharmacie
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
}
