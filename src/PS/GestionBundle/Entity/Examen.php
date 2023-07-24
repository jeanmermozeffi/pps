<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Examen
 *
 * @ORM\Table(name="examen")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\ExamenRepository")
 */
class Examen
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_examen", type="datetime")
     */
    private $date;


     /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_resultat", type="datetime", nullable=true)
     */
    private $dateResultat;


    /**
     * @ORM\ManyToOne(targetEntity="Patient")
     * @ORM\JoinColumn(nullable=false)
     */
    private $patient;



     /**
     * @ORM\ManyToOne(targetEntity="Consultation")
     */
    private $consultation;



    /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Hopital")
     */
    private $hopital;



    /**
     * @ORM\OneToMany(targetEntity="LigneExamen", mappedBy="examen", cascade={"persist"})
     */
    private $lignes;






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
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Examen
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->lignes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set patient.
     *
     * @param \PS\GestionBundle\Entity\Patient $patient
     *
     * @return Examen
     */
    public function setPatient(\PS\GestionBundle\Entity\Patient $patient)
    {
        $this->patient = $patient;

        return $this;
    }

    /**
     * Get patient.
     *
     * @return \PS\GestionBundle\Entity\Patient
     */
    public function getPatient()
    {
        return $this->patient;
    }

    /**
     * Set consultation.
     *
     * @param \PS\GestionBundle\Entity\Consultation|null $consultation
     *
     * @return Examen
     */
    public function setConsultation(\PS\GestionBundle\Entity\Consultation $consultation = null)
    {
        $this->consultation = $consultation;

        return $this;
    }

    /**
     * Get consultation.
     *
     * @return \PS\GestionBundle\Entity\Consultation|null
     */
    public function getConsultation()
    {
        return $this->consultation;
    }

    /**
     * Set hopital.
     *
     * @param \PS\ParametreBundle\Entity\Hopital|null $hopital
     *
     * @return Examen
     */
    public function setHopital(\PS\ParametreBundle\Entity\Hopital $hopital = null)
    {
        $this->hopital = $hopital;

        return $this;
    }

    /**
     * Get hopital.
     *
     * @return \PS\ParametreBundle\Entity\Hopital|null
     */
    public function getHopital()
    {
        return $this->hopital;
    }

    /**
     * Add ligne.
     *
     * @param \PS\GestionBundle\Entity\LigneExamen $ligne
     *
     * @return Examen
     */
    public function addLigne(\PS\GestionBundle\Entity\LigneExamen $ligne)
    {
        $this->lignes[] = $ligne;
        $ligne->setExamen($this);
        return $this;
    }

    /**
     * Remove ligne.
     *
     * @param \PS\GestionBundle\Entity\LigneExamen $ligne
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeLigne(\PS\GestionBundle\Entity\LigneExamen $ligne)
    {
        return $this->lignes->removeElement($ligne);
    }

    /**
     * Get lignes.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLignes()
    {
        return $this->lignes;
    }

    /**
     * Set dateResultat.
     *
     * @param \DateTime|null $dateResultat
     *
     * @return Examen
     */
    public function setDateResultat($dateResultat = null)
    {
        $this->dateResultat = $dateResultat;

        return $this;
    }

    /**
     * Get dateResultat.
     *
     * @return \DateTime|null
     */
    public function getDateResultat()
    {
        return $this->dateResultat;
    }
}
