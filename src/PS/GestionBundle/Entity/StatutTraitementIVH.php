<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StatutTraitementIVH
 *
 * @ORM\Table(name="statut_traitement_ivh")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\StatutTraitementIVHRepository")
 */
class StatutTraitementIVH
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
     * @var string
     *
     * @ORM\Column(name="statutIVH", type="string", length=100)
     */
    private $statutIVH;

    /**
     * @var bool
     *
     * @ORM\Column(name="etatARV", type="boolean", nullable=true)
     */
    private $etatARV;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="tempsTraitementArv", type="datetime", nullable=true)
     */
    private $tempsTraitementArv;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateTestArv", type="datetime", nullable=true)
     */
    private $dateTestArv;

    /**
     * @var string
     *
     * @ORM\Column(name="raisonNonArv", type="string", length=255, nullable=true)
     */
    private $raisonNonArv;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateTestNegative", type="datetime", nullable=true)
     */
    private $dateTestNegative;

    /**
     * @var bool
     *
     * @ORM\Column(name="provenanceStatut", type="boolean", nullable=true)
     */
    private $provenanceStatut;

    /**
     * @ORM\OneToOne(targetEntity="\PS\GestionBundle\Entity\InfoPatientCancerCol", mappedBy="statutTraitementIVH")
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
     * Set statutIVH
     *
     * @param string $statutIVH
     * @return StatutTraitementIVH
     */
    public function setStatutIVH($statutIVH)
    {
        $this->statutIVH = $statutIVH;
    
        return $this;
    }

    /**
     * Get statutIVH
     *
     * @return string 
     */
    public function getStatutIVH()
    {
        return $this->statutIVH;
    }

    /**
     * Set etatARV
     *
     * @param boolean $etatARV
     * @return StatutTraitementIVH
     */
    public function setEtatARV($etatARV)
    {
        $this->etatARV = $etatARV;
    
        return $this;
    }

    /**
     * Get etatARV
     *
     * @return boolean 
     */
    public function getEtatARV()
    {
        return $this->etatARV;
    }

    /**
     * Set tempsTraitementArv
     *
     * @param \DateTime $tempsTraitementArv
     * @return StatutTraitementIVH
     */
    public function setTempsTraitementArv($tempsTraitementArv)
    {
        $this->tempsTraitementArv = $tempsTraitementArv;
    
        return $this;
    }

    /**
     * Get tempsTraitementArv
     *
     * @return \DateTime 
     */
    public function getTempsTraitementArv()
    {
        return $this->tempsTraitementArv;
    }

    /**
     * Set dateTestArv
     *
     * @param \DateTime $dateTestArv
     * @return StatutTraitementIVH
     */
    public function setDateTestArv($dateTestArv)
    {
        $this->dateTestArv = $dateTestArv;
    
        return $this;
    }

    /**
     * Get dateTestArv
     *
     * @return \DateTime 
     */
    public function getDateTestArv()
    {
        return $this->dateTestArv;
    }

    /**
     * Set raisonNonArv
     *
     * @param string $raisonNonArv
     * @return StatutTraitementIVH
     */
    public function setRaisonNonArv($raisonNonArv)
    {
        $this->raisonNonArv = $raisonNonArv;
    
        return $this;
    }

    /**
     * Get raisonNonArv
     *
     * @return string 
     */
    public function getRaisonNonArv()
    {
        return $this->raisonNonArv;
    }

    /**
     * Set dateTestNegative
     *
     * @param \DateTime $dateTestNegative
     * @return StatutTraitementIVH
     */
    public function setDateTestNegative($dateTestNegative)
    {
        $this->dateTestNegative = $dateTestNegative;
    
        return $this;
    }

    /**
     * Get dateTestNegative
     *
     * @return \DateTime 
     */
    public function getDateTestNegative()
    {
        return $this->dateTestNegative;
    }

    /**
     * Set provenanceStatut
     *
     * @param boolean $provenanceStatut
     * @return StatutTraitementIVH
     */
    public function setProvenanceStatut($provenanceStatut)
    {
        $this->provenanceStatut = $provenanceStatut;
    
        return $this;
    }

    /**
     * Get provenanceStatut
     *
     * @return boolean 
     */
    public function getProvenanceStatut()
    {
        return $this->provenanceStatut;
    }

    /**
     * Set infoPatientCancerCol
     *
     * @param \PS\GestionBundle\Entity\InfoPatientCancerCol $infoPatientCancerCol
     * @return StatutTraitementIVH
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
