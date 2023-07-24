<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use APY\DataGridBundle\Grid\Mapping as GRID;
use PS\GestionBundle\Entity\PassCorporate;

/**
 * Pass
 *
 * @ORM\Table(name="pass")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\PassRepository")
 * @UniqueEntity("identifiant", message="Identifiant déjà existant dans la base de données")
 * @GRID\Source(columns="id, identifiant, pin, actif, dateCreation", filterable=false, sortable=false)
 */
class Pass
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(title="ID", primary=true)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="identifiant", type="string", length=6, unique=true)
     * @GRID\Column(title="Identifiant",  operators={"rlike"}, defaultOperator="rlike", filterable=true)
     */
    private $identifiant;

    /**
     * @var string
     *
     * @ORM\Column(name="pin", type="string", length=6)
     * @GRID\Column(title="Pin", operators={"rlike"}, defaultOperator="rlike", filterable=true)
     */
    private $pin;

    /**
     * @var bool
     *
     * @ORM\Column(name="actif", type="boolean")
     * @GRID\Column(title="Actif", safe=false)
     */
    private $actif;

    /**
     * @var bool
     *
     * @ORM\Column(name="date_creation", type="datetime")
     * @GRID\Column(title="Date Creation", operatorsVisible=false, filterable=true)
     */
    private $dateCreation;

    /**
     * @ORM\OneToMany(targetEntity="PS\GestionBundle\Entity\PassCorporate", mappedBy="pass", cascade={"persist"})
     */
    private $passCorporate;


    private $corporate;


    /**
     * @ORM\OneToMany(targetEntity="PS\GestionBundle\Entity\PassHopital", mappedBy="pass")
     */
    private $passHopital;


    private $hopital;


    public function __construct()
    {
        $this->dateCreation = new \DateTime();
        $this->passCorporate =  new \Doctrine\Common\Collections\ArrayCollection();
        $this->passHopital=  new \Doctrine\Common\Collections\ArrayCollection();
    }


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
     * Set identifiant
     *
     * @param string $identifiant
     * @return Pass
     */
    public function setIdentifiant($identifiant)
    {
        $this->identifiant = $identifiant;

        return $this;
    }

    /**
     * Get identifiant
     *
     * @return string 
     */
    public function getIdentifiant()
    {
        return $this->identifiant;
    }

    /**
     * Set pin
     *
     * @param string $pin
     * @return Pass
     */
    public function setPin($pin)
    {
        $this->pin = $pin;

        return $this;
    }

    /**
     * Get pin
     *
     * @return string 
     */
    public function getPin()
    {
        return $this->pin;
    }

    /**
     * Set actif
     *
     * @param boolean $actif
     * @return Pass
     */
    public function setActif($actif)
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * Get actif
     *
     * @return boolean 
     */
    public function getActif()
    {
        return $this->actif;
    }


    /**
     * Set actif
     *
     * @param boolean $actif
     * @return Pass
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get actif
     *
     * @return boolean 
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }


        // Important
    /**
     * @param $corporate
     * @return mixed
     */
    public function setCorporate($corporate)
    {
        if (!$corporate) {
            return $this;
        }

        $passCorporate = new PassCorporate();

        $passCorporate->setPass($this);
        $passCorporate->setCorporate($corporate);

        $this->addPassCorporate($passCorporate);

    }

    public function getCorporate()
    {

        $corporate = [];
        foreach ($this->passCorporate as $_pass) {
            $corporate[] = $_pass->getCorporate();
        }

        return current($corporate);
    }

    /**
     * Add PassCorporate
     *
     * @param \PS\GestionBundle\Entity\PassCorporate $passCorporate
     *
     * @return Patient
     */
    public function addPassCorporate(\PS\GestionBundle\Entity\PassCorporate $passCorporate)
    {
        $this->passCorporate[] = $passCorporate;

        return $this;
    }

    /**
     * Remove PassCorporate
     *
     * @param \PS\GestionBundle\Entity\PassCorporate $passCorporate
     */
    public function removePassCorporate(\PS\GestionBundle\Entity\PassCorporate $passCorporate)
    {
        $this->passCorporate->removeElement($passCorporate);
    }

    /**
     * Get PassCorporates
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPassCorporate()
    {
        return $this->passCorporate;
    }


    /**
     * @param $hopital
     * @return mixed
     */
    public function setHopital($hopital)
    {
        if (!$hopital) {
            return $this;
        }

        $passHopital = new PassHopital();

        $passHopital->setPass($this);
        $passHopital->setCorporate($hopital);

        $this->addPassHopital($passHopital);

    }

    public function getHopital()
    {

        $hopital = [];
        foreach ($this->passHopital as $_pass) {
            $hopital[] = $_pass->getCorporate();
        }

        return current($hopital);
    }

    /**
     * Add PassHopital
     *
     * @param \PS\GestionBundle\Entity\PassHopital $passHopital
     *
     * @return Patient
     */
    public function addPassHopital(\PS\GestionBundle\Entity\PassHopital $passHopital)
    {
        $this->passHopital[] = $passHopital;

        return $this;
    }

    /**
     * Remove PassHopital
     *
     * @param \PS\GestionBundle\Entity\PassHopital $passHopital
     */
    public function removePassHopital(\PS\GestionBundle\Entity\PassHopital $passHopital)
    {
        $this->passHopital->removeElement($passHopital);
    }

    /**
     * Get PassHopitals
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPassHopital()
    {
        return $this->passHopital;
    }
}
