<?php

namespace PS\SpecialiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Etape
 *
 * @ORM\Table(name="etape")
 * @ORM\Entity(repositoryClass="PS\SpecialiteBundle\Repository\EtapeRepository")
 * @GRID\Source(columns="id,libEtape,etapeParente.libEtape,specialite.nom", filterable=false, sortable=false)
 */
class Etape
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(title="ID")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="lib_etape", type="string", length=100)
     * @GRID\Column(title="Etape")
     */
    private $libEtape;


    /**
     * @ORM\ManyToOne(targetEntity="Etape")
     * @GRID\Column(title="Etape parente", field="etapeParente.libEtape")
     */
    private $etapeParente;

     /**
     * @ORM\OneToMany(targetEntity="Etape", mappedBy="etapeParente", fetch="EAGER")
     */
    private $etapeEnfants;


    /**
     * @ORM\Column(name="roles_etape", type="json_array")
     */
    private $rolesEtape;




    /**
     * @ORM\OneToMany(targetEntity="ReferenceEtape", mappedBy="etape")
     */
    private $references;



    public function __construct()
    {
        if (!$this->rolesEtape) {
            $this->rolesEtape = [];
        }
        
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
     * Set libEtape
     *
     * @param string $libEtape
     *
     * @return Etape
     */
    public function setLibEtape($libEtape)
    {
        $this->libEtape = $libEtape;

        return $this;
    }

    /**
     * Get libEtape
     *
     * @return string
     */
    public function getLibEtape()
    {
        return $this->libEtape;
    }



    /**
     * Set etapeParente
     *
     * @param \PS\SpecialiteBundle\Entity\Etape $etapeParente
     *
     * @return Etape
     */
    public function setEtapeParente(\PS\SpecialiteBundle\Entity\Etape $etapeParente = null)
    {
        $this->etapeParente = $etapeParente;

        return $this;
    }

    /**
     * Get etapeParente
     *
     * @return \PS\SpecialiteBundle\Entity\Etape
     */
    public function getEtapeParente()
    {
        return $this->etapeParente;
    }

    /**
     * Set rolesEtape
     *
     * @param json $rolesEtape
     *
     * @return Etape
     */
    public function setRolesEtape($rolesEtape)
    {
        $this->rolesEtape = $rolesEtape;

        return $this;
    }

    /**
     * Get rolesEtape
     *
     * @return json
     */
    public function getRolesEtape()
    {
        return $this->rolesEtape;
    }

    /**
     * Add etapeEnfant
     *
     * @param \PS\SpecialiteBundle\Entity\Etape $etapeEnfant
     *
     * @return Etape
     */
    public function addEtapeEnfant(\PS\SpecialiteBundle\Entity\Etape $etapeEnfant)
    {
        $this->etapeEnfants[] = $etapeEnfant;

        return $this;
    }

    /**
     * Remove etapeEnfant
     *
     * @param \PS\SpecialiteBundle\Entity\Etape $etapeEnfant
     */
    public function removeEtapeEnfant(\PS\SpecialiteBundle\Entity\Etape $etapeEnfant)
    {
        $this->etapeEnfants->removeElement($etapeEnfant);
    }

    /**
     * Get etapeEnfants
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEtapeEnfants()
    {
        return $this->etapeEnfants;
    }

  
    

    /**
     * Add reference
     *
     * @param \PS\SpecialiteBundle\Entity\ReferenceEtape $reference
     *
     * @return Etape
     */
    public function addReference(\PS\SpecialiteBundle\Entity\ReferenceEtape $reference)
    {
        $this->references[] = $reference;
        $reference->setEtape($this);
        return $this;
    }

    /**
     * Remove reference
     *
     * @param \PS\SpecialiteBundle\Entity\ReferenceEtape $reference
     */
    public function removeReference(\PS\SpecialiteBundle\Entity\ReferenceEtape $reference)
    {
        $this->references->removeElement($reference);
    }

    /**
     * Get references
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReferences()
    {
        return $this->references;
    }
}
