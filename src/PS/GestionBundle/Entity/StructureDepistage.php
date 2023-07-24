<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StructureDepistage
 *
 * @ORM\Table(name="structure_depistage")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\StructureDepistageRepository")
 */
class StructureDepistage
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
     * @ORM\Column(name="nomStructure", type="string", length=150)
     */
    private $nomStructure;

    /**
     * @var string
     *
     * @ORM\Column(name="nomPrestataire", type="string", length=150)
     */
    private $nomPrestataire;

    /**
     * @ORM\OneToOne(targetEntity="\PS\GestionBundle\Entity\InfoPatientCancerCol", mappedBy="structureDepistage")
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
     * Set nomStructure
     *
     * @param string $nomStructure
     * @return StructureDepistage
     */
    public function setNomStructure($nomStructure)
    {
        $this->nomStructure = $nomStructure;
    
        return $this;
    }

    /**
     * Get nomStructure
     *
     * @return string 
     */
    public function getNomStructure()
    {
        return $this->nomStructure;
    }

    /**
     * Set nomPrestataire
     *
     * @param string $nomPrestataire
     * @return StructureDepistage
     */
    public function setNomPrestataire($nomPrestataire)
    {
        $this->nomPrestataire = $nomPrestataire;
    
        return $this;
    }

    /**
     * Get nomPrestataire
     *
     * @return string 
     */
    public function getNomPrestataire()
    {
        return $this->nomPrestataire;
    }

    /**
     * Set infoPatientCancerCol
     *
     * @param \PS\GestionBundle\Entity\InfoPatientCancerCol $infoPatientCancerCol
     * @return StructureDepistage
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
