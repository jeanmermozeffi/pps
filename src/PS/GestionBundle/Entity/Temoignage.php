<?php
namespace PS\GestionBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @GRID\Source(columns="id, dateTemoignage, auteurTemoignage", sortable=false, filterable=true)
 */
class Temoignage
{
    /**
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @GRID\Column(title="ID", primary=true, filterable=false, sortable=true)
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $contenuTemoignage;

    /**
     * @ORM\Column(type="date")
     * @ORM\OrderBy({"date" = "ASC"})
     * @GRID\Column(title="Date", operatorsVisible=false, filterable=false)
     * @Assert\NotBlank()
     */
    private $dateTemoignage;

    /**
     * @ORM\Column(type="string", length=100)
     * @GRID\Column(title="Auteur", operatorsVisible=false, filterable=false)
     * @Assert\NotBlank()
     */
    private $auteurTemoignage;

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
     * Set contenuTemoignage
     *
     * @param string $contenuTemoignage
     *
     * @return Temoignage
     */
    public function setContenuTemoignage($contenuTemoignage)
    {
        $this->contenuTemoignage = $contenuTemoignage;

        return $this;
    }

    /**
     * Get contenuTemoignage
     *
     * @return string
     */
    public function getContenuTemoignage()
    {
        return $this->contenuTemoignage;
    }

    /**
     * Set dateTemoignage
     *
     * @param \DateTime $dateTemoignage
     *
     * @return Temoignage
     */
    public function setDateTemoignage($dateTemoignage)
    {
        $this->dateTemoignage = $dateTemoignage;

        return $this;
    }

    /**
     * Get dateTemoignage
     *
     * @return \DateTime
     */
    public function getDateTemoignage()
    {
        return $this->dateTemoignage;
    }

    /**
     * Set auteurTemoignage
     *
     * @param string $auteurTemoignage
     *
     * @return Temoignage
     */
    public function setAuteurTemoignage($auteurTemoignage)
    {
        $this->auteurTemoignage = $auteurTemoignage;

        return $this;
    }

    /**
     * Get auteurTemoignage
     *
     * @return string
     */
    public function getAuteurTemoignage()
    {
        return $this->auteurTemoignage;
    }
}
