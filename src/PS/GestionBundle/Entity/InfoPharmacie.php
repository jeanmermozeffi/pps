<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use APY\DataGridBundle\Grid\Mapping as GRID;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;

/**
 * @ExclusionPolicy("all")
 * InfoPharmacie
 *
 * @ORM\Table(name="info_pharmacie")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\InfoPharmacieRepository")
 */
class InfoPharmacie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"info-pharmacie"})
     */
    private $id;

    /**
     * @Expose
     * @var string
     *
     * @ORM\Column(name="contacts", type="string", length=100)
     * @Groups({"info-pharmacie"})
     */
    private $contacts;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_responsable", type="string", length=255)
     * @Groups({"info-pharmacie"})
     */
    private $nomResponsable;

     /**
      * @Expose
     * @var string
     *
     * @ORM\Column(name="localisation_pharmacie", type="string", length=255)
     * @Groups({"info-pharmacie"})
     * @SerializedName("localisation")
     */
    private $localisationPharmacie;


    /**
     * @ORM\OneToOne(targetEntity="Pharmacie", inversedBy="info", cascade={"persist"})
     * @Groups({"info-pharmacie"})
     */
    private $pharmacie;


    /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Ville")
     * @Groups({"info-pharmacie"})
     */
    private $ville;


     /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Commune")
     * @Groups({"info-pharmacie"})
     */
    private $commune;

    /**
     * @Expose
     * @ORM\Column(type="boolean", name="point_vente", options={"default": 0})
     * @Groups({"info-pharmacie"})
     */
    private $pointVente;




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
     * Set contacts
     *
     * @param string $contacts
     *
     * @return InfoPharmacie
     */
    public function setContacts($contacts)
    {
        $this->contacts = $contacts;

        return $this;
    }

    /**
     * Get contacts
     *
     * @return string
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * Set nomResponsable
     *
     * @param string $nomResponsable
     *
     * @return InfoPharmacie
     */
    public function setNomResponsable($nomResponsable)
    {
        $this->nomResponsable = $nomResponsable;

        return $this;
    }

    /**
     * Get nomResponsable
     *
     * @return string
     */
    public function getNomResponsable()
    {
        return $this->nomResponsable;
    }

    /**
     * Set localisationPharmacie
     *
     * @param string $localisationPharmacie
     *
     * @return InfoPharmacie
     */
    public function setLocalisationPharmacie($localisationPharmacie)
    {
        $this->localisationPharmacie = $localisationPharmacie;

        return $this;
    }

    /**
     * Get localisationPharmacie
     *
     * @return string
     */
    public function getLocalisationPharmacie()
    {
        return $this->localisationPharmacie;
    }

    /**
     * Set pharmacie
     *
     * @param \PS\GestionBundle\Entity\Pharmacie $pharmacie
     *
     * @return InfoPharmacie
     */
    public function setPharmacie(\PS\GestionBundle\Entity\Pharmacie $pharmacie = null)
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
     * Set ville
     *
     * @param \PS\ParametreBundle\Entity\Ville $ville
     *
     * @return InfoPharmacie
     */
    public function setVille(\PS\ParametreBundle\Entity\Ville $ville = null)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return \PS\ParametreBundle\Entity\Ville
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set commune
     *
     * @param \PS\ParametreBundle\Entity\Commune $commune
     *
     * @return InfoPharmacie
     */
    public function setCommune(\PS\ParametreBundle\Entity\Commune $commune = null)
    {
        $this->commune = $commune;

        return $this;
    }

    /**
     * Get commune
     *
     * @return \PS\ParametreBundle\Entity\Commune
     */
    public function getCommune()
    {
        return $this->commune;
    }

    /**
     * Set pointVente
     *
     * @param boolean $pointVente
     *
     * @return InfoPharmacie
     */
    public function setPointVente($pointVente)
    {
        $this->pointVente = $pointVente;

        return $this;
    }

    /**
     * Get pointVente
     *
     * @return boolean
     */
    public function getPointVente()
    {
        return $this->pointVente;
    }
}
