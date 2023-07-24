<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;

/**
 * @ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\ConsultationTraitementsRepository")
 */
class ConsultationTraitements
{
    /**
     * @Expose
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @Groups({"consultation-medicament"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Consultation", inversedBy="medicaments")
     * @Assert\NotBlank()
     */
    private $consultation;

    /**
     * @Expose
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank()
     * @Groups({"consultation-medicament"})
     */
    private $medicament;



    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Groups({"consultation", "ordonnance"})
     */
    private $substitution;

    /**
     * @Expose
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Groups({"consultation-medicament"})
     */
    private $posologie;

    /**
     * @Expose
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Groups({"consultation-medicament"})
     */
    private $details;


    /**
     * @Exclude
     * @ORM\OneToMany(targetEntity="MedicamentPharmacie", mappedBy="medicament")
     */
    private $pharmacies;

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
     * Set medicament
     *
     * @param string $medicament
     * @return ConsultationTraitements
     */
    public function setMedicament($medicament)
    {
        $this->medicament = $medicament;

        return $this;
    }

    /**
     * Get medicament
     *
     * @return string 
     */
    public function getMedicament()
    {
        return $this->medicament;
    }

    /**
     * Set posologie
     *
     * @param string $posologie
     * @return ConsultationTraitements
     */
    public function setPosologie($posologie)
    {
        $this->posologie = $posologie;

        return $this;
    }

    /**
     * Get posologie
     *
     * @return string 
     */
    public function getPosologie()
    {
        return $this->posologie;
    }

    /**
     * Set details
     *
     * @param string $details
     * @return ConsultationTraitements
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return string 
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set consultation
     *
     * @param \PS\GestionBundle\Entity\Consultation $consultation
     * @return ConsultationTraitements
     */
    public function setConsultation(\PS\GestionBundle\Entity\Consultation $consultation = null)
    {
        $this->consultation = $consultation;

        return $this;
    }

    /**
     * Get consultation
     *
     * @return \PS\GestionBundle\Entity\Consultation 
     */
    public function getConsultation()
    {
        return $this->consultation;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pharmacies = new \Doctrine\Common\Collections\ArrayCollection();
    }


    public function getlastOperation()
    {
        if (!$this->pharmacies->isEmpty()) {
            return $this->pharmacies->last();
        }

        return true;
    }

    /**
     * Add pharmacy
     *
     * @param \PS\GestionBundle\Entity\MedicamentPharmacie $pharmacy
     *
     * @return ConsultationTraitements
     */
    public function addPharmacy(\PS\GestionBundle\Entity\MedicamentPharmacie $pharmacy)
    {
        $this->pharmacies[] = $pharmacy;
        $pharmacy->setMedicament($this);
        return $this;
    }

    /**
     * Remove pharmacy
     *
     * @param \PS\GestionBundle\Entity\MedicamentPharmacie $pharmacy
     */
    public function removePharmacy(\PS\GestionBundle\Entity\MedicamentPharmacie $pharmacy)
    {
        $this->pharmacies->removeElement($pharmacy);
    }

    /**
     * Get pharmacies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPharmacies()
    {
        return $this->pharmacies;
    }



    /**
     * Set medicament
     *
     * @param string $medicament
     * @return ConsultationTraitements
     */
    public function setSubstitution($substitution)
    {
        $this->substitution = $substitution;

        return $this;
    }

    /**
     * Get medicament
     *
     * @return string 
     */
    public function getSubstitution()
    {
        return $this->substitution;
    }
}
