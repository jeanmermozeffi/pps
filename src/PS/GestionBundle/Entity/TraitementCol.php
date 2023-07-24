<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TraitementCol
 *
 * @ORM\Table(name="traitement_col")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\TraitementColRepository")
 */
class TraitementCol
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
     * @ORM\Column(name="typeTraitementCol", type="string", length=255, nullable=true)
     */
    private $typeTraitementCol;

    /**
     * @var string
     *
     * @ORM\Column(name="raisonReportCryotherapie", type="string", length=255, nullable=true)
     */
    private $raisonReportCryotherapie;

    /**
     * @ORM\OneToOne(targetEntity="\PS\GestionBundle\Entity\InfoPatientCancerCol", mappedBy="traitementCol")
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
     * Set typeTraitementCol
     *
     * @param string $typeTraitementCol
     * @return TraitementCol
     */
    public function setTypeTraitementCol($typeTraitementCol)
    {
        $this->typeTraitementCol = $typeTraitementCol;
    
        return $this;
    }

    /**
     * Get typeTraitementCol
     *
     * @return string 
     */
    public function getTypeTraitementCol()
    {
        return $this->typeTraitementCol;
    }

    /**
     * Set raisonReportCryotherapie
     *
     * @param string $raisonReportCryotherapie
     * @return TraitementCol
     */
    public function setRaisonReportCryotherapie($raisonReportCryotherapie)
    {
        $this->raisonReportCryotherapie = $raisonReportCryotherapie;
    
        return $this;
    }

    /**
     * Get raisonReportCryotherapie
     *
     * @return string 
     */
    public function getRaisonReportCryotherapie()
    {
        return $this->raisonReportCryotherapie;
    }

    /**
     * Set infoPatientCancerCol
     *
     * @param \PS\GestionBundle\Entity\InfoPatientCancerCol $infoPatientCancerCol
     * @return TraitementCol
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
