<?php
namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\MedicamentPharmacieRepository")
 */
class OperationPharmacie
{
    /**
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="PS\UtilisateurBundle\Entity\Utilisateur")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;


     /**
     * @ORM\ManyToOne(targetEntity="Consultation")
     * @ORM\JoinColumn(nullable=false)
     */
    private $consultation;


     /**
     * @ORM\ManyToOne(targetEntity="Pharmacie")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pharmacie;

    /**
     * @ORM\Column(type="datetime", name="date_operation")
     * @Assert\NotBlank()
     */
    private $dateOperation;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, name="montant_verse", options={"default": 0})
     */
    private $montantVerse;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, name="montant_rendu", options={"default": 0})
    */
    private $montantRendu;


   
      /**
     * @ORM\OneToMany(targetEntity="MedicamentPharmacie", mappedBy="operation", cascade={"persist", "remove"})
    */
    private $medicamentPharmacies;



    public function __construct()
    {
        $this->dateOperation = new \DateTime();
        $this->montantVerse = 0;
        $this->montantRendu = 0;
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
     * Set dateOperation
     *
     * @param \DateTime $dateOperation
     *
     * @return OperationPharmacie
     */
    public function setDateOperation($dateOperation)
    {
        $this->dateOperation = $dateOperation;

        return $this;
    }

    /**
     * Get dateOperation
     *
     * @return \DateTime
     */
    public function getDateOperation()
    {
        return $this->dateOperation;
    }

    /**
     * Set user
     *
     * @param \PS\UtilisateurBundle\Entity\Utilisateur $user
     *
     * @return OperationPharmacie
     */
    public function setUser(\PS\UtilisateurBundle\Entity\Utilisateur $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \PS\UtilisateurBundle\Entity\Utilisateur
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set consultation
     *
     * @param \PS\GestionBundle\Entity\Consultation $consultation
     *
     * @return OperationPharmacie
     */
    public function setConsultation(\PS\GestionBundle\Entity\Consultation $consultation)
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
     * Set pharmacie
     *
     * @param \PS\GestionBundle\Entity\Pharmacie $pharmacie
     *
     * @return OperationPharmacie
     */
    public function setPharmacie(\PS\GestionBundle\Entity\Pharmacie $pharmacie)
    {
        $this->pharmacie = $pharmacie;

        return $this;
    }

    /**
     * Get pharmacie
     *
     * @return \PS\GestionBundle\Entity\Pharmacie
     */
    public function getPharmacie()
    {
        return $this->pharmacie;
    }

    /**
     * Set montantVerse
     *
     * @param string $montantVerse
     *
     * @return OperationPharmacie
     */
    public function setMontantVerse($montantVerse)
    {
        $this->montantVerse = $montantVerse;

        return $this;
    }

    /**
     * Get montantVerse
     *
     * @return string
     */
    public function getMontantVerse()
    {
        return $this->montantVerse;
    }

    /**
     * Set montantRendu
     *
     * @param string $montantRendu
     *
     * @return OperationPharmacie
     */
    public function setMontantRendu($montantRendu)
    {
        $this->montantRendu = $montantRendu;

        return $this;
    }

    /**
     * Get montantRendu
     *
     * @return string
     */
    public function getMontantRendu()
    {
        return $this->montantRendu;
    }

    

    /**
     * Add medicamentPharmacy
     *
     * @param \PS\GestionBundle\Entity\MedicamentPharmacie $medicamentPharmacy
     *
     * @return OperationPharmacie
     */
    public function addMedicamentPharmacy(\PS\GestionBundle\Entity\MedicamentPharmacie $medicamentPharmacy)
    {
        $this->medicamentPharmacies[] = $medicamentPharmacy;
        $medicamentPharmacy->setOperation($this);
        return $this;
    }

    /**
     * Remove medicamentPharmacy
     *
     * @param \PS\GestionBundle\Entity\MedicamentPharmacie $medicamentPharmacy
     */
    public function removeMedicamentPharmacy(\PS\GestionBundle\Entity\MedicamentPharmacie $medicamentPharmacy)
    {
        $this->medicamentPharmacies->removeElement($medicamentPharmacy);
    }

    /**
     * Get medicamentPharmacies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMedicamentPharmacies()
    {
        return $this->medicamentPharmacies;
    }

   
}
