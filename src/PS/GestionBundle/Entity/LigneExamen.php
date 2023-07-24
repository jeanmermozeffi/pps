<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LigneExamen
 *
 * @ORM\Table(name="ligne_examen")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\LigneExamenRepository")
 */
class LigneExamen
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
     * @var bool
     *
     * @ORM\Column(name="etat", type="boolean")
     */
    private $etat;

    /**
     * @var string
     *
     * @ORM\Column(name="details", type="text")
     */
    private $details;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $diagnostic;



    /**
     * @ORM\ManyToOne(targetEntity="Examen", inversedBy="lignes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $examen;



     /**
     * @var string
     *
     * @ORM\Column(name="lib_examen", type="string")
     */
    private $libelle;



    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set etat.
     *
     * @param bool $etat
     *
     * @return LigneExamen
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat.
     *
     * @return bool
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set details.
     *
     * @param string $details
     *
     * @return LigneExamen
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details.
     *
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }


    /**
     * Set examen.
     *
     * @param \PS\GestionBundle\Entity\Examen $examen
     *
     * @return LigneExamen
     */
    public function setExamen(\PS\GestionBundle\Entity\Examen $examen)
    {
        $this->examen = $examen;

        return $this;
    }

    /**
     * Get examen.
     *
     * @return \PS\GestionBundle\Entity\Examen
     */
    public function getExamen()
    {
        return $this->examen;
    }

    /**
     * Set libelle.
     *
     * @param string $libelle
     *
     * @return LigneExamen
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle.
     *
     * @return \PS\ParametreBundle\Entity\Libelle
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set diagnostic.
     *
     * @param string $diagnostic
     *
     * @return LigneExamen
     */
    public function setDiagnostic($diagnostic)
    {
        $this->diagnostic = $diagnostic;

        return $this;
    }

    /**
     * Get diagnostic.
     *
     * @return string
     */
    public function getDiagnostic()
    {
        return $this->diagnostic;
    }
}
