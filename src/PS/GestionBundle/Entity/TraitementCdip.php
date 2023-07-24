<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TraitementCdip
 *
 * @ORM\Table(name="traitement_cdip")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\TraitementCdipRepository")
 */
class TraitementCdip
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
     * @ORM\Column(name="cdipOffert", type="boolean", nullable=true)
     */
    private $cdipOffert;

    /**
     * @var bool
     *
     * @ORM\Column(name="cdipAccepter", type="boolean", nullable=true)
     */
    private $cdipAccepter;

    /**
     * @var string
     *
     * @ORM\Column(name="resultatCdip", type="string", length=1, nullable=true)
     */
    private $resultatCdip;

    /**
     * @var string
     *
     * @ORM\Column(name="raisonCdipOffert", type="text", nullable=true)
     */
    private $raisonCdipOffert;

    /**
     * @var string
     *
     * @ORM\Column(name="raisonCdipAccepter", type="text", nullable=true)
     */
    private $raisonCdipAccepter;

    /**
     * @ORM\OneToOne(targetEntity="\PS\GestionBundle\Entity\InfoPatientCancerCol", mappedBy="traitementCdip")
     */
    private $infoPatientCancerCol;

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
     * Set cdipOffert
     *
     * @param boolean $cdipOffert
     * @return TraitementCdip
     */
    public function setCdipOffert($cdipOffert)
    {
        $this->cdipOffert = $cdipOffert;
    
        return $this;
    }

    /**
     * Get cdipOffert
     *
     * @return boolean 
     */
    public function getCdipOffert()
    {
        return $this->cdipOffert;
    }

    /**
     * Set cdipAccepter
     *
     * @param boolean $cdipAccepter
     * @return TraitementCdip
     */
    public function setCdipAccepter($cdipAccepter)
    {
        $this->cdipAccepter = $cdipAccepter;
    
        return $this;
    }

    /**
     * Get cdipAccepter
     *
     * @return boolean 
     */
    public function getCdipAccepter()
    {
        return $this->cdipAccepter;
    }

    /**
     * Set resultatCdip
     *
     * @param string $resultatCdip
     * @return TraitementCdip
     */
    public function setResultatCdip($resultatCdip)
    {
        $this->resultatCdip = $resultatCdip;
    
        return $this;
    }

    /**
     * Get resultatCdip
     *
     * @return string 
     */
    public function getResultatCdip()
    {
        return $this->resultatCdip;
    }

    /**
     * Set raisonCdipOffert
     *
     * @param string $raisonCdipOffert
     * @return TraitementCdip
     */
    public function setRaisonCdipOffert($raisonCdipOffert)
    {
        $this->raisonCdipOffert = $raisonCdipOffert;
    
        return $this;
    }

    /**
     * Get raisonCdipOffert
     *
     * @return string 
     */
    public function getRaisonCdipOffert()
    {
        return $this->raisonCdipOffert;
    }

    /**
     * Set raisonCdipAccepter
     *
     * @param string $raisonCdipAccepter
     * @return TraitementCdip
     */
    public function setRaisonCdipAccepter($raisonCdipAccepter)
    {
        $this->raisonCdipAccepter = $raisonCdipAccepter;
    
        return $this;
    }

    /**
     * Get raisonCdipAccepter
     *
     * @return string 
     */
    public function getRaisonCdipAccepter()
    {
        return $this->raisonCdipAccepter;
    }

    /**
     * Set infoPatientCancerCol
     *
     * @param \PS\GestionBundle\Entity\InfoPatientCancerCol $infoPatientCancerCol
     * @return TraitementCdip
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
