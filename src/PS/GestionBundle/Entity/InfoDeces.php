<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InfoDeces
 *
 * @ORM\Table(name="info_deces")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\InfoDecesRepository")
 */
class InfoDeces
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
     * @var datetime_immutable
     *
     * @ORM\Column(name="date_deces", type="datetime")
     */
    private $dateDeces;

    /**
     * @ORM\OneToOne(targetEntity="Patient", mappedBy="deces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $patient;

    
    /**
     * @ORM\Column(type="text", name="details_deces")
     */
    private $detailsDeces;


    /**
     * @ORM\ManyToOne(targetEntity="Medecin")
     * @ORM\JoinColumn(nullable=false)
     */
    private $medecin;


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
     * Set dateDeces
     *
     * @param datetime_immutable $dateDeces
     *
     * @return InfoDeces
     */
    public function setDateDeces($dateDeces)
    {
        $this->dateDeces = $dateDeces;

        return $this;
    }

    /**
     * Get dateDeces
     *
     * @return datetime_immutable
     */
    public function getDateDeces()
    {
        return $this->dateDeces;
    }

    /**
     * Set detailsDeces
     *
     * @param string $detailsDeces
     *
     * @return InfoDeces
     */
    public function setDetailsDeces($detailsDeces)
    {
        $this->detailsDeces = $detailsDeces;

        return $this;
    }

    /**
     * Get detailsDeces
     *
     * @return string
     */
    public function getDetailsDeces()
    {
        return $this->detailsDeces;
    }

    /**
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\Patient $patient
     *
     * @return InfoDeces
     */
    public function setPatient(\PS\GestionBundle\Entity\Patient $patient)
    {
        $this->patient = $patient;

        return $this;
    }

    /**
     * Get patient
     *
     * @return \PS\GestionBundle\Entity\Patient
     */
    public function getPatient()
    {
        return $this->patient;
    }

   

    /**
     * Set medecin
     *
     * @param \PS\GestionBundle\Entity\Medecin $medecin
     *
     * @return InfoDeces
     */
    public function setMedecin(\PS\GestionBundle\Entity\Medecin $medecin)
    {
        $this->medecin = $medecin;

        return $this;
    }

    /**
     * Get medecin
     *
     * @return \PS\GestionBundle\Entity\Medecin
     */
    public function getMedecin()
    {
        return $this->medecin;
    }
}
