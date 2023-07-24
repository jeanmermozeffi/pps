<?php
namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use APY\DataGridBundle\Grid\Mapping as GRID;
use JMS\Serializer\Annotation\Groups;



/**
 * @ORM\Entity
 * @GRID\Source(columns="id,corporate.raisonSociale,dateCommande,qteCommande", sortable=false, filterable=true)
 */
class Commande
{
    /**
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @GRID\Column(title="ID", primary=true, filterable=false, sortable=true)
     * @Groups({"commande"})
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Corporate", inversedBy="commandes")
     * @Assert\NotBlank(groups={"create"})
     * @GRID\Column(field="corporate.raisonSociale", title="Corporate", operatorsVisible=false, filter="select")
     * @Groups({"commande"})
     */    
    private $corporate;

    /**
     * @ORM\Column(type="datetime")
     * @GRID\Column(title="Date", operatorsVisible=false, filterable=false)
     * @Groups({"commande"})
     */
    private $dateCommande;


    /**
     * @ORM\Column(type="integer")
     * @Assert\Choice(choices={-1, 1, 0}, groups={"statut"})
     * @GRID\Column(
            title="Statut"
            , type="array"
            , operatorsVisible=false
            , sortable=false
            , filter="select"
            , selectFrom="values"
            , values={"-1":"En attente", "1": "Traité", "0":"Annulé"}
        )
     * @Groups({"commande"})
     */
    private $statutCommande;


    /**
     * @ORM\Column(type="integer")
     * @GRID\Column(title="Quantité", filterable=false)
     * @Assert\Type(type="numeric", groups={"create"})
     * @Groups({"commande"})
     */
    private $qteCommande;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(groups={"create"})
     * @Groups({"commande"})
     */
    private $detailsCommande;


     /**
     * @ORM\OneToMany(targetEntity="Livraison", mappedBy="commande")
     * @Groups({"livraison"})
     */    
    private $livraisons;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->livraisons = new \Doctrine\Common\Collections\ArrayCollection();
        if (is_null($this->statutCommande)) {
            $this->statutCommande = -1;
        }

        if (is_null($this->dateCommande)) {
            $this->dateCommande = new \DateTime();
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
     * Set dateCommande
     *
     * @param \DateTime $dateCommande
     *
     * @return Commande
     */
    public function setDateCommande($dateCommande)
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    /**
     * Get dateCommande
     *
     * @return \DateTime
     */
    public function getDateCommande()
    {
        return $this->dateCommande;
    }

    /**
     * Set statutCommande
     *
     * @param integer $statutCommande
     *
     * @return Commande
     */
    public function setStatutCommande($statutCommande)
    {
        $this->statutCommande = $statutCommande;

        return $this;
    }

    /**
     * Get statutCommande
     *
     * @return integer
     */
    public function getStatutCommande()
    {
        return $this->statutCommande;
    }

    /**
     * Set qteCommande
     *
     * @param integer $qteCommande
     *
     * @return Commande
     */
    public function setQteCommande($qteCommande)
    {
        $this->qteCommande = $qteCommande;

        return $this;
    }

    /**
     * Get qteCommande
     *
     * @return integer
     */
    public function getQteCommande()
    {
        return $this->qteCommande;
    }

    /**
     * Set corporate
     *
     * @param \PS\GestionBundle\Entity\Corporate $corporate
     *
     * @return Commande
     */
    public function setCorporate(\PS\GestionBundle\Entity\Corporate $corporate = null)
    {
        $this->corporate = $corporate;

        return $this;
    }

    /**
     * Get corporate
     *
     * @return \PS\GestionBundle\Entity\Corporate
     */
    public function getCorporate()
    {
        return $this->corporate;
    }

  

    /**
     * Set detailsCommande
     *
     * @param string $detailsCommande
     *
     * @return Commande
     */
    public function setDetailsCommande($detailsCommande)
    {
        $this->detailsCommande = $detailsCommande;

        return $this;
    }

    /**
     * Get detailsCommande
     *
     * @return string
     */
    public function getDetailsCommande()
    {
        return $this->detailsCommande;
    }

    /**
     * Add livraison
     *
     * @param \PS\GestionBundle\Entity\Livraison $livraison
     *
     * @return Commande
     */
    public function addLivraison(\PS\GestionBundle\Entity\Livraison $livraison)
    {
        $this->livraisons[] = $livraison;

        $livraison->setCommande($this);

        return $this;
    }

    /**
     * Remove livraison
     *
     * @param \PS\GestionBundle\Entity\Livraison $livraison
     */
    public function removeLivraison(\PS\GestionBundle\Entity\Livraison $livraison)
    {
        $this->livraisons->removeElement($livraison);
    }

    /**
     * Get livraisons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLivraisons()
    {
        return $this->livraisons;
    }
}
