<?php

declare(strict_types=0);

namespace PS\GestionBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;
use PS\GestionBundle\Validator\Constraints as PSAssert;
use PS\ParametreBundle\Entity\Affection;
use PS\ParametreBundle\Entity\Specialite;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * FicheAffection
 *
 * @ORM\Table(name="fiche_affection")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\FicheAffectionRepository")
 */
class FicheAffection
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
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="compteRendu", type="text")
     */
    private $compteRendu;

    /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Affection")
     * @ORM\JoinColumn(nullable=false)
     */
    private $affection;



     /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Hopital")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hopital;


     /**
     * @ORM\ManyToOne(targetEntity="Medecin")
     * @ORM\JoinColumn(nullable=false)
     */
    private $medecin;



     /**
     * @ORM\ManyToOne(targetEntity="Patient")
     * @ORM\JoinColumn(nullable=false)
     */
    private $patient;



    /**
     * @Exclude
     * @ORM\OneToMany(targetEntity="FicheParamClinique", mappedBy="fiche", cascade={"persist"})
     */
    private $constantes;




    /**
     * @Exclude
     * @ORM\OneToMany(targetEntity="FicheTraitement", mappedBy="fiche", cascade={"persist"})
     */
    private $traitements;



    /**
     * @Exclude
     * @ORM\OneToMany(targetEntity="FicheExamen", mappedBy="fiche", cascade={"persist"})
     * @Assert\Valid()
     */
    private $examens;




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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return FicheAffection
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set compteRendu
     *
     * @param string $compteRendu
     *
     * @return FicheAffection
     */
    public function setCompteRendu(string $compteRendu)
    {
        $this->compteRendu = (string)$compteRendu;

        return $this;
    }

    /**
     * Get compteRendu
     *
     * @return string
     */
    public function getCompteRendu()
    {
        return $this->compteRendu;
    }


    /**
     * Set affection
     *
     * @param \PS\ParametreBundle\Entity\Affection $affection
     *
     * @return FicheAffection
     */
    public function setAffection(\PS\ParametreBundle\Entity\Affection $affection)
    {
        $this->affection = $affection;

        return $this;
    }

    /**
     * Get affection
     *
     * @return \PS\ParametreBundle\Entity\Affection
     */
    public function getAffection()
    {
        return $this->affection;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->constantes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->traitements = new \Doctrine\Common\Collections\ArrayCollection();
        $this->examens = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set hopital
     *
     * @param \PS\ParametreBundle\Entity\Hopital $hopital
     *
     * @return FicheAffection
     */
    public function setHopital(\PS\ParametreBundle\Entity\Hopital $hopital)
    {
        $this->hopital = $hopital;

        return $this;
    }

    /**
     * Get hopital
     *
     * @return \PS\ParametreBundle\Entity\Hopital
     */
    public function getHopital()
    {
        return $this->hopital;
    }

    /**
     * Set medecin
     *
     * @param \PS\GestionBundle\Entity\Medecin $medecin
     *
     * @return FicheAffection
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

    /**
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\Patient $patient
     *
     * @return FicheAffection
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
     * Add constante
     *
     * @param \PS\GestionBundle\Entity\FicheParamClinique $constante
     *
     * @return FicheAffection
     */
    public function addConstante(\PS\GestionBundle\Entity\FicheParamClinique $constante)
    {
        $this->constantes[] = $constante;
        $constante->setFiche($this);
        return $this;
    }

    /**
     * Remove constante
     *
     * @param \PS\GestionBundle\Entity\FicheParamClinique $constante
     */
    public function removeConstante(\PS\GestionBundle\Entity\FicheParamClinique $constante)
    {
        $this->constantes->removeElement($constante);
    }

    /**
     * Get constantes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConstantes()
    {
        return $this->constantes;
    }

    /**
     * Add traitement
     *
     * @param \PS\GestionBundle\Entity\FicheTraitement $traitement
     *
     * @return FicheAffection
     */
    public function addTraitement(\PS\GestionBundle\Entity\FicheTraitement $traitement)
    {
        $this->traitements[] = $traitement;
        $traitement->setFiche($this);
        return $this;
    }

    /**
     * Remove traitement
     *
     * @param \PS\GestionBundle\Entity\FicheTraitement $traitement
     */
    public function removeTraitement(\PS\GestionBundle\Entity\FicheTraitement $traitement)
    {
        $this->traitements->removeElement($traitement);
    }

    /**
     * Get traitements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTraitements()
    {
        return $this->traitements;
    }

    /**
     * Add examen
     *
     * @param \PS\GestionBundle\Entity\FicheExamen $examen
     *
     * @return FicheAffection
     */
    public function addExamen(\PS\GestionBundle\Entity\FicheExamen $examen)
    {
        $this->examens[] = $examen;
        $examen->setFiche($this);
        return $this;
    }

    /**
     * Remove examen
     *
     * @param \PS\GestionBundle\Entity\FicheExamen $examen
     */
    public function removeExamen(\PS\GestionBundle\Entity\FicheExamen $examen)
    {
        $this->examens->removeElement($examen);
    }

    /**
     * Get examens
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExamens()
    {
        return $this->examens;
    }
}
