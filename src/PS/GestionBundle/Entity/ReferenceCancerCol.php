<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReferenceCancerCol
 *
 * @ORM\Table(name="reference_cancer_col")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\ReferenceCancerColRepository")
 */
class ReferenceCancerCol
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
     * @ORM\Column(name="libReference", type="string", length=200, nullable=true)
     */
    private $libReference;

    /**
     * @var array
     *
     * @ORM\Column(name="typeReference", type="json_array", nullable=true)
     */
    private $typeReference;

    /**
     * @var string
     *
     * @ORM\Column(name="problemeGynegologique", type="string", length=255, nullable=true)
     */
    private $problemeGynegologique;

    /**
     * @ORM\OneToOne(targetEntity="\PS\GestionBundle\Entity\InfoPatientCancerCol", mappedBy="referenceCancerCol")
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
     * Set libReference
     *
     * @param string $libReference
     * @return ReferenceCancerCol
     */
    public function setLibReference($libReference)
    {
        $this->libReference = $libReference;
    
        return $this;
    }

    /**
     * Get libReference
     *
     * @return string 
     */
    public function getLibReference()
    {
        return $this->libReference;
    }

    /**
     * Set typeReference
     *
     * @param array $typeReference
     * @return ReferenceCancerCol
     */
    public function setTypeReference($typeReference)
    {
        $this->typeReference = $typeReference;
    
        return $this;
    }

    /**
     * Get typeReference
     *
     * @return array 
     */
    public function getTypeReference()
    {
        return $this->typeReference;
    }

    /**
     * Set problemeGynegologique
     *
     * @param string $problemeGynegologique
     * @return ReferenceCancerCol
     */
    public function setProblemeGynegologique($problemeGynegologique)
    {
        $this->problemeGynegologique = $problemeGynegologique;
    
        return $this;
    }

    /**
     * Get problemeGynegologique
     *
     * @return string 
     */
    public function getProblemeGynegologique()
    {
        return $this->problemeGynegologique;
    }

    /**
     * Set infoPatientCancerCol
     *
     * @param \PS\GestionBundle\Entity\InfoPatientCancerCol $infoPatientCancerCol
     * @return ReferenceCancerCol
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
