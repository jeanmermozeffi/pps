<?php
namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\MedicamentPharmacieRepository")
 */
class MedicamentPharmacie
{
    /**
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="ConsultationTraitements", inversedBy="pharmacies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $medicament;


    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Groups({"consultation", "ordonnance"})
     */
    private $substitution;


    /**
     * @ORM\Column(type="string", options={"default":""})
     */
    private $commentaire;

     /**
     * @ORM\ManyToOne(targetEntity="OperationPharmacie", inversedBy="medicamentPharmacie", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $operation;

    /**
     * @ORM\Column(type="smallint")
     */
    private $statut;

    /**
     * @ORM\Column(name="prix_medicament", type="decimal", options={"default": 0}, precision=10, scale=2)
     */
    private $prixMedicament;

     /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Assurance")
     */
    private $assurance;
    




    //private $medicamentSubstitution;


    public function __construct()
    {
        if (is_null($this->statut)) {
            $this->statut = 0;
        }
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
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return MedicamentPharmacie
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getStatut()
    {
        return $this->statut;
    }

    
    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return MedicamentPharmacie
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set medicament
     *
     * @param \PS\GestionBundle\Entity\ConsultationTraitements $medicament
     *
     * @return MedicamentPharmacie
     */
    public function setMedicament(\PS\GestionBundle\Entity\ConsultationTraitements $medicament)
    {
        $this->medicament = $medicament;

        return $this;
    }

    /**
     * Get medicament
     *
     * @return \PS\GestionBundle\Entity\ConsultationTraitements
     */
    public function getMedicament()
    {
        return $this->medicament;
    }


    /**
     * Set operation
     *
     * @param \PS\GestionBundle\Entity\OperationPharmacie $operation
     *
     * @return MedicamentPharmacie
     */
    public function setOperation(\PS\GestionBundle\Entity\OperationPharmacie $operation)
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * Get operation
     *
     * @return \PS\GestionBundle\Entity\OperationPharmacie
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * Set prixMedicament
     *
     * @param string $prixMedicament
     *
     * @return MedicamentPharmacie
     */
    public function setPrixMedicament($prixMedicament)
    {
        $this->prixMedicament = $prixMedicament;

        return $this;
    }

    /**
     * Get prixMedicament
     *
     * @return string
     */
    public function getPrixMedicament()
    {
        return $this->prixMedicament;
    }

    /**
     * Set assurance
     *
     * @param \PS\ParametreBundle\Entity\Assurance $assurance
     *
     * @return MedicamentPharmacie
     */
    public function setAssurance(\PS\ParametreBundle\Entity\Assurance $assurance = null)
    {
        $this->assurance = $assurance;

        return $this;
    }

    /**
     * Get assurance
     *
     * @return \PS\ParametreBundle\Entity\Assurance
     */
    public function getAssurance()
    {
        return $this->assurance;
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
