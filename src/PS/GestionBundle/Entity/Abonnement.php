<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Abonnement
 *
 * @ORM\Table(name="abonnement")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\AbonnementRepository")
 */
class Abonnement
{

    const TYPE_FREE = 'free'; //Par défaut
    const TYPE_PREMIUM_BASIC = 'premium_basic'; //Carte
    const TYPE_PREMIUM_FULL = 'premium_full'; //Carte + Bracelet
    const TYPE_RENEWAL = 'renewal'; //Abonnement annuel


    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *  @GRID\Column(visible=false)
     */
    private $id;

    /**
     * @var datetime
     *
     * @ORM\Column(name="date_debut_abonnement", type="datetime")
     *  @GRID\Column(title="Date Début")
     */
    private $dateDebut;




     /**
     * @var datetime
     *
     * @ORM\Column(name="date_fin_abonnement", type="datetime")
     * @GRID\Column(title="Date Fin")
     */
    private $dateFin;



    /**
     * @ORM\ManyToOne(targetEntity="PS\GestionBundle\Entity\Patient", inversedBy="abonnements")
     * @ORM\JoinColumn(nullable=false)
     * 
    */
    private $patient;


    /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Pack")
     * @GRID\Column(field="pack.alias", title="Pack")
    */
    private $pack;


     /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Pass", inversedBy="abonnements")
     * @GRID\Column(field="pass.identifiant", title="ID")
     * @GRID\Column(field="pass.pin", title="PIN")
    */
    private $pass;



    public function __construct()
    {
        $this->setDateDebut(new \DateTime());
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
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Abonnement
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
     * @return Abonnement
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
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\Patient $patient
     *
     * @return Abonnement
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
     * Set pack
     *
     * @param \PS\ParametreBundle\Entity\Pack $pack
     *
     * @return Abonnement
     */
    public function setPack(\PS\ParametreBundle\Entity\Pack $pack = null)
    {
        $this->pack = $pack;

        return $this;
    }

    /**
     * Get pack
     *
     * @return \PS\ParametreBundle\Entity\Pack
     */
    public function getPack()
    {
        return $this->pack;
    }

    /**
     * Set pass
     *
     * @param \PS\ParametreBundle\Entity\Pass $pass
     *
     * @return Abonnement
     */
    public function setPass(\PS\ParametreBundle\Entity\Pass $pass = null)
    {
        $this->pass = $pass;

        return $this;
    }

    /**
     * Get pass
     *
     * @return \PS\ParametreBundle\Entity\Pass
     */
    public function getPass()
    {
        return $this->pass;
    }
}
