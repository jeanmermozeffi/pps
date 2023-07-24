<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RealisationIva
 *
 * @ORM\Table(name="realisation_iva")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\RealisationIvaRepository")
 */
class RealisationIva
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
     * @ORM\Column(name="statutRealisation", type="boolean")
     */
    private $statutRealisation;

    /**
     * @var bool
     *
     * @ORM\Column(name="resultatIVA", type="boolean", nullable=true)
     */
    private $resultatIVA;

    /**
     * @var string
     *
     * @ORM\Column(name="raisonAnnulation", type="string", length=255, nullable=true)
     */
    private $raisonAnnulation;

    /**
     * @ORM\OneToOne(targetEntity="\PS\GestionBundle\Entity\InfoPatientCancerCol", mappedBy="realisationIva")
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
     * Set statutRealisation
     *
     * @param boolean $statutRealisation
     * @return RealisationIva
     */
    public function setStatutRealisation($statutRealisation)
    {
        $this->statutRealisation = $statutRealisation;
    
        return $this;
    }

    /**
     * Get statutRealisation
     *
     * @return boolean 
     */
    public function getStatutRealisation()
    {
        return $this->statutRealisation;
    }

    /**
     * Set resultatIVA
     *
     * @param boolean $resultatIVA
     * @return RealisationIva
     */
    public function setResultatIVA($resultatIVA)
    {
        $this->resultatIVA = $resultatIVA;
    
        return $this;
    }

    /**
     * Get resultatIVA
     *
     * @return boolean 
     */
    public function getResultatIVA()
    {
        return $this->resultatIVA;
    }

    /**
     * Set raisonAnnulation
     *
     * @param string $raisonAnnulation
     * @return RealisationIva
     */
    public function setRaisonAnnulation($raisonAnnulation)
    {
        $this->raisonAnnulation = $raisonAnnulation;
    
        return $this;
    }

    /**
     * Get raisonAnnulation
     *
     * @return string 
     */
    public function getRaisonAnnulation()
    {
        return $this->raisonAnnulation;
    }

    /**
     * Set infoPatientCancerCol
     *
     * @param \PS\GestionBundle\Entity\InfoPatientCancerCol $infoPatientCancerCol
     * @return RealisationIva
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
