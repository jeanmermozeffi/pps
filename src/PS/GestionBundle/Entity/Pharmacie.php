<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use APY\DataGridBundle\Grid\Mapping as GRID;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Expose;


/**
 * Pharmacie
 * @ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\PharmacieRepository")
 * @GRID\Source(columns="id, libPharmacie, info.nomResponsable, info.contacts, info.localisationPharmacie")
 */
class Pharmacie
{
    /**
     * @Expose
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(title="Id", filterable=false, primary=true)
     * @Groups({"pharmacie"})
     */
    private $id;

    /**
     * @Expose
     * @ORM\Column(type="string")
     * @GRID\Column(title="pharmacie.form.libellePharmacie")
     * @SerializedName("nom")
     * @Groups({"pharmacie"})
     */
    private $libPharmacie;

    /**
     * @ORM\OneToMany(targetEntity="AssurancePharmacie", mappedBy="pharmacie", cascade={"persist"})
     */
    private $assurances;

    /**
     * @Expose
     * @ORM\OneToOne(targetEntity="InfoPharmacie", mappedBy="pharmacie", cascade={"persist"}, fetch="EAGER")
     * @GRID\Column(field="info.nomResponsable", title="pharmacie.form.info.nomResponsable")
     * @GRID\Column(field="info.commune.libCommune", title="pharmacie.form.info.libCommune")
     * @GRID\Column(field="info.localisationPharmacie", title="pharmacie.form.info.localisationPharmacie")
     * @GRID\Column(field="info.contacts", title="pharmacie.form.info.contacts")
     * @GRID\Column(field="info.ville.libelle", title="pharmacie.form.info.ville")
     * @Groups({"pharmacie", "info-pharmacie"})
     */
    private $info;


    /**
     * @ORM\ManyToMany(targetEntity="PS\ParametreBundle\Entity\TypePaiement", cascade={"persist"})
     * @ORM\JoinTable(name="type_paiement_pharmacie")
     */
    private $typePaiments;


    /**
     * @ORM\OneToMany(targetEntity="DisponibiliteMedicament", mappedBy="pharmacie")
     */
    private $disponibilites;



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
     * Set libPharmacie
     *
     * @param string $libPharmacie
     *
     * @return Pharmacie
     */
    public function setLibPharmacie($libPharmacie)
    {
        $this->libPharmacie = $libPharmacie;

        return $this;
    }

    /**
     * Get libPharmacie
     *
     * @return string
     */
    public function getLibPharmacie()
    {
        return $this->libPharmacie;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->assurances = new \Doctrine\Common\Collections\ArrayCollection();
        $this->disponibilites = new \Doctrine\Common\Collections\ArrayCollection();
    }

   

    /**
     * Add assurance
     *
     * @param \PS\GestionBundle\Entity\AssurancePharmacie $assurance
     *
     * @return Pharmacie
     */
    public function addAssurance(\PS\GestionBundle\Entity\AssurancePharmacie $assurance)
    {
        $this->assurances[] = $assurance;
        $assurance->setPharmacie($this);
        return $this;
    }

    /**
     * Remove assurance
     *
     * @param \PS\GestionBundle\Entity\AssurancePharmacie $assurance
     */
    public function removeAssurance(\PS\GestionBundle\Entity\AssurancePharmacie $assurance)
    {
        $this->assurances->removeElement($assurance);
    }

    /**
     * Get assurances
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAssurances()
    {
        return $this->assurances;
    }

    /**
     * Set info
     *
     * @param \PS\GestionBundle\Entity\InfoPharmacie $info
     *
     * @return Pharmacie
     */
    public function setInfo(\PS\GestionBundle\Entity\InfoPharmacie $info = null)
    {
        $this->info = $info;
        $info->setPharmacie($this);
        return $this;
    }

    /**
     * Get info
     *
     * @return \PS\GestionBundle\Entity\InfoPharmacie
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Add typePaiment
     *
     * @param \PS\ParametreBundle\Entity\TypePaiement $typePaiment
     *
     * @return Pharmacie
     */
    public function addTypePaiment(\PS\ParametreBundle\Entity\TypePaiement $typePaiment)
    {
        $this->typePaiments[] = $typePaiment;
        $typePaiment->setPharmacie($this);
        return $this;
    }

    /**
     * Remove typePaiment
     *
     * @param \PS\ParametreBundle\Entity\TypePaiement $typePaiment
     */
    public function removeTypePaiment(\PS\ParametreBundle\Entity\TypePaiement $typePaiment)
    {
        $this->typePaiments->removeElement($typePaiment);
    }

    /**
     * Get typePaiments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTypePaiments()
    {
        return $this->typePaiments;
    }


    /**
     * Add disponibilite
     *
     * @param \PS\GestionBundle\Entity\DisponibiliteMedicament $disponibilite
     *
     * @return Pharmacie
     */
    public function addDisponibilite(\PS\GestionBundle\Entity\DisponibiliteMedicament $disponibilite)
    {
        $this->disponibilites[] = $disponibilite;
        $disponibilite->setPharmacie($this);
        return $this;
    }

    /**
     * Remove disponibilite
     *
     * @param \PS\GestionBundle\Entity\DisponibiliteMedicament $disponibilite
     */
    public function removeDisponibilite(\PS\GestionBundle\Entity\DisponibiliteMedicament $disponibilite)
    {
        $this->disponibilites->removeElement($disponibilite);
    }

    /**
     * Get disponibilites
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDisponibilites()
    {
        return $this->disponibilites;
    }
}
