<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InfoConsultation
 *
 * @ORM\Table(name="info_consultation")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\InfoConsultationRepository")
 */
class InfoConsultation
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
     * @ORM\ManyToOne(targetEntity="Consultation", inversedBy="infos")
     */
    private $consultation;


     /**
     * @ORM\ManyToOne(targetEntity="Medecin")
     */
    private $medecin;

     /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Etat")
     */
    private $etat;


    /**
     * @ORM\Column(type="datetime")
     */
    private $dateInfo;


    /**
     * @ORM\Column(type="text")
     */
    private $detailsInfo;


    public function __construct()
    {
        $this->dateInfo = new \DateTime();
    }



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
     * Set dateInfo
     *
     * @param \DateTime $dateInfo
     *
     * @return InfoConsultation
     */
    public function setDateInfo($dateInfo)
    {
        $this->dateInfo = $dateInfo;

        return $this;
    }

    /**
     * Get dateInfo
     *
     * @return \DateTime
     */
    public function getDateInfo()
    {
        return $this->dateInfo;
    }

    /**
     * Set consultation
     *
     * @param \PS\GestionBundle\Entity\Consultation $consultation
     *
     * @return InfoConsultation
     */
    public function setConsultation(\PS\GestionBundle\Entity\Consultation $consultation = null)
    {
        $this->consultation = $consultation;

        return $this;
    }

    /**
     * Get consultation
     *
     * @return \PS\GestionBundle\Entity\Consultation
     */
    public function getConsultation()
    {
        return $this->consultation;
    }

    /**
     * Set medecin
     *
     * @param \PS\GestionBundle\Entity\Medecin $medecin
     *
     * @return InfoConsultation
     */
    public function setMedecin(\PS\GestionBundle\Entity\Medecin $medecin = null)
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

    /**
     * Set etat
     *
     * @param \PS\ParametreBundle\Entity\Etat $etat
     *
     * @return InfoConsultation
     */
    public function setEtat(\PS\ParametreBundle\Entity\Etat $etat = null)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return \PS\ParametreBundle\Entity\Etat
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set detailsInfo
     *
     * @param string $detailsInfo
     *
     * @return InfoConsultation
     */
    public function setDetailsInfo($detailsInfo)
    {
        $this->detailsInfo = $detailsInfo;

        return $this;
    }

    /**
     * Get detailsInfo
     *
     * @return string
     */
    public function getDetailsInfo()
    {
        return $this->detailsInfo;
    }
}
