<?php
namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use APY\DataGridBundle\Grid\Mapping as GRID;
use JMS\Serializer\Annotation\Groups;




/**
 * @ORM\Entity
 */
class Livraison
{
    /**
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @Groups({"livraison"})
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Commande", inversedBy="livraisons")
     * @Groups({"livraison"})
     */    
    private $commande;

    /**
     * @ORM\Column(type="date")
     * @Groups({"livraison"})
     */
    private $dateLivraison;


    /**
     * @ORM\Column(type="integer")
     * @Groups({"livraison"})
     */
    private $qteLivraison;


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
     * Set dateLivraison
     *
     * @param \DateTime $dateLivraison
     *
     * @return Livraison
     */
    public function setDateLivraison($dateLivraison)
    {
        $this->dateLivraison = $dateLivraison;

        return $this;
    }

    /**
     * Get dateLivraison
     *
     * @return \DateTime
     */
    public function getDateLivraison()
    {
        return $this->dateLivraison;
    }

    /**
     * Set qteLivraison
     *
     * @param integer $qteLivraison
     *
     * @return Livraison
     */
    public function setQteLivraison($qteLivraison)
    {
        $this->qteLivraison = $qteLivraison;

        return $this;
    }

    /**
     * Get qteLivraison
     *
     * @return integer
     */
    public function getQteLivraison()
    {
        return $this->qteLivraison;
    }

    

    /**
     * Set commande
     *
     * @param \PS\GestionBundle\Entity\Commande $commande
     *
     * @return Livraison
     */
    public function setCommande(\PS\GestionBundle\Entity\Commande $commande = null)
    {
        $this->commande = $commande;

        return $this;
    }

    /**
     * Get commande
     *
     * @return \PS\GestionBundle\Entity\Commande
     */
    public function getCommande()
    {
        return $this->commande;
    }
}
