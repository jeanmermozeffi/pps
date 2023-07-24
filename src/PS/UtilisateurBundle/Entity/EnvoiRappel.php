<?php


namespace PS\UtilisateurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity
 */
class EnvoiRappel
{
     /**
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="PS\GestionBundle\Entity\Patient")
     * @ORM\JoinColumn(nullable=false)
     */  
    private $patient;

    /**
     * @ORM\Column(type="date")
     */
    private $dateEnvoi;

    /**
     * @ORM\Column(type="smallint", nullable=false)
     * @Assert\Choice(choices = {"1", "2"})
     */
    private $typeRappel;


    public function __construct()
    {
        $this->dateEnvoi = new \DateTime();
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
     * Set dateEnvoi
     *
     * @param \DateTime $dateEnvoi
     * @return EnvoiRappel
     */
    public function setDateEnvoi($dateEnvoi)
    {
        $this->dateEnvoi = $dateEnvoi;
    
        return $this;
    }

    /**
     * Get dateEnvoi
     *
     * @return \DateTime 
     */
    public function getDateEnvoi()
    {
        return $this->dateEnvoi;
    }

    /**
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\Patient $patient
     * @return EnvoiRappel
     */
    public function setPatient(\PS\GestionBundle\Entity\Patient $patient = null)
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
     * Set typeRappel
     *
     * @param integer $typeRappel
     * @return EnvoiRappel
     */
    public function setTypeRappel($typeRappel)
    {
        if (!in_array($typeRappel, [1, 2])) {
            throw new \LogicException('Type de rappel invalide');
        }
        $this->typeRappel = $typeRappel;
    
        return $this;
    }

    /**
     * Get typeRappel
     *
     * @return integer 
     */
    public function getTypeRappel()
    {
        return $this->typeRappel;
    }
}
