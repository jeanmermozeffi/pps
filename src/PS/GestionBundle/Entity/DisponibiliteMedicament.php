<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * DisponibiliteMedicament
 *
 * @ORM\Table(name="disponibilite_medicament")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\DisponibiliteMedicamentRepository")
 */
class DisponibiliteMedicament
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
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Medicament")
     * @ORM\JoinColumn(nullable=false)
     * @GRID\Column(title="Médicament", field="medicament.nom", operatorsVisible=false, filter="select"
    , selectFrom="source")
     */
    private $medicament;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=2, options={"default": 0})
     * @GRID\Column(title="Prix", align="left")
     */
    private $prixMedicament;

    /**
     * @ORM\ManyToOne(targetEntity="Pharmacie", inversedBy="disponibilites")
     * @ORM\JoinColumn(nullable=false)
     * @GRID\Column(field="pharmacie.libPharmacie", title="Pharmacie", filter="select", selectFrom="source", operatorsVisible=false)
     */
    private $pharmacie;

    /**
     * @ORM\Column(type="boolean", options={"default":1})
     * @GRID\Column(title="Disponible", operatorsVisible=false
    , sortable=false
    , filter="select"
    , selectFrom="values"
    , values={true: "Oui", false:"Non"}
    ))
     */
    private $disponible;


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
     * Set medicament
     *
     * @param \PS\ParametreBundle\Entity\Medicament $medicament
     *
     * @return DisponibiliteMedicament
     */
    public function setMedicament(\PS\ParametreBundle\Entity\Medicament $medicament)
    {
        $this->medicament = $medicament;

        return $this;
    }

    /**
     * Get medicament
     *
     * @return \PS\ParametreBundle\Entity\Medicament
     */
    public function getMedicament()
    {
        return $this->medicament;
    }

    /**
     * Set pharmacie
     *
     * @param \PS\GestionBundle\Entity\Pharmacie $pharmacie
     *
     * @return DisponibiliteMedicament
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
     * Set prixMedicament
     *
     * @param string $prixMedicament
     *
     * @return DisponibiliteMedicament
     */
    public function setPrixMedicament($prixMedicament)
    {
        $this->prixMedicament = $prixMedicament;

        return $this;
    }

    /**
     * Get prixMedicament
     *
     * @return string
     */
    public function getPrixMedicament()
    {
        return $this->prixMedicament;
    }

    /**
     * Set disponible
     *
     * @param boolean $disponible
     *
     * @return DisponibiliteMedicament
     */
    public function setDisponible($disponible)
    {
        $this->disponible = $disponible;

        return $this;
    }

    /**
     * Get disponible
     *
     * @return boolean
     */
    public function getDisponible()
    {
        return $this->disponible;
    }
}
