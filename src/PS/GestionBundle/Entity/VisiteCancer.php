<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VisiteCancer
 *
 * @ORM\Table(name="visite_cancer")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\VisiteCancerRepository")
 */
class VisiteCancer
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
     * @var array
     *
     * @ORM\Column(name="typeVisite", type="json_array",nullable=true)
     */
    private $typeVisite;

    /**
     * @var bool
     *
     * @ORM\Column(name="statutPostTraitement", type="boolean",nullable=true)
     */
    private $statutPostTraitement;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateTraitementLeep", type="datetime", nullable=true)
     */
    private $dateTraitementLeep;


    /**
     * @ORM\OneToOne(targetEntity="\PS\GestionBundle\Entity\InfoPatientCancerCol", mappedBy="visiteCancer")
     */
    private $infoPatientCancerCol
;

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
     * Set typeVisite
     *
     * @param array $typeVisite
     * @return VisiteCancer
     */
    public function setTypeVisite($typeVisite)
    {
        $this->typeVisite = $typeVisite;
    
        return $this;
    }

    /**
     * Get typeVisite
     *
     * @return array 
     */
    public function getTypeVisite()
    {
        return $this->typeVisite;
    }

    /**
     * Set statutPostTraitement
     *
     * @param boolean $statutPostTraitement
     * @return VisiteCancer
     */
    public function setStatutPostTraitement($statutPostTraitement)
    {
        $this->statutPostTraitement = $statutPostTraitement;
    
        return $this;
    }

    /**
     * Get statutPostTraitement
     *
     * @return boolean 
     */
    public function getStatutPostTraitement()
    {
        return $this->statutPostTraitement;
    }

    /**
     * Set dateTraitementLeep
     *
     * @param \DateTime $dateTraitementLeep
     * @return VisiteCancer
     */
    public function setDateTraitementLeep($dateTraitementLeep)
    {
        $this->dateTraitementLeep = $dateTraitementLeep;
    
        return $this;
    }

    /**
     * Get dateTraitementLeep
     *
     * @return \DateTime 
     */
    public function getDateTraitementLeep()
    {
        return $this->dateTraitementLeep;
    }

    /**
     * Set visiteCancer
     *
     * @param \PS\GestionBundle\Entity\InfoPatientCancerCol $visiteCancer
     * @return VisiteCancer
     */
    public function setVisiteCancer(\PS\GestionBundle\Entity\InfoPatientCancerCol $visiteCancer = null)
    {
        $this->visiteCancer = $visiteCancer;
    
        return $this;
    }

    /**
     * Get visiteCancer
     *
     * @return \PS\GestionBundle\Entity\InfoPatientCancerCol 
     */
    public function getVisiteCancer()
    {
        return $this->visiteCancer;
    }

    /**
     * Set infoPatientCancerCol
     *
     * @param \PS\GestionBundle\Entity\InfoPatientCancerCol $infoPatientCancerCol
     * @return VisiteCancer
     */
    public function setInfoPatientCancerCol(\PS\GestionBundle\Entity\InfoPatientCancerCol $infoPatientCancerCol = null)
    {
        $this->infoPatientCancerCol = $infoPatientCancerCol;
    
        return $this;
    }

    /**
     * Get infoPatientCancerCol
     *
     * @return \PS\GestionBundle\Entity\InfoPatientCancerCol 
     */
    public function getInfoPatientCancerCol()
    {
        return $this->infoPatientCancerCol;
    }
}
