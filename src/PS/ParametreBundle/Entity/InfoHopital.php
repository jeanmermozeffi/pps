<?php

namespace PS\ParametreBundle\Entity;

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
 * @ORM\Table(name="info_hopital")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\InfoHopitalRepository")
 */
class InfoHopital
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"info-hopital"})
     */
    private $id;

    /**
     * @Expose
     * @var string
     * @Assert\NotNull(message="Veuillez renseigner les contacts de l'hôpital")
     * @ORM\Column(name="contacts", type="string", length=100)
     * @Groups({"info-hopital"})
     */
    private $contacts;


    /**
     * @Expose
     * @var string
     * @SerializedName("email")
     * @ORM\Column(name="email_hopital", type="string", length=255, options={"default": ""})
     * @Groups({"info-hopital"})
     */
    private $emailHopital;


    /**
     * @Expose
     * @var string
     * @Assert\NotNull(message="Veuillez renseigner la localisation")
     * @ORM\Column(name="localisation_hopital", type="string", length=255)
     * @Groups({"info-hopital"})
     * @SerializedName("localisation")
     */
    private $localisationHopital;


    /**
     * @ORM\OneToOne(targetEntity="PS\ParametreBundle\Entity\Hopital", inversedBy="info", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"info-hopital"})
     */
    private $hopital;


    /**
     * @var string
     * @Assert\NotNull(message="Veuillez renseigner le nom du responsable")
     * @ORM\Column(name="nom_responsable", type="string", length=255)
     * @Groups({"info-hopital"})
     */
    private $nomResponsable;

    /**
     * @ORM\Column(type="string", name="logo_hopital",nullable=true)
     * @Assert\File(mimeTypes={ "image/jpeg", "image/png" }, maxSize="2M")
     * @Groups({"info-hopital"})
     */
    private $logoHopital;

    /**
     * @Expose
     * @ORM\Column(type="boolean", name="point_vente", options={"default": 0})
     * @Groups({"info-hopital"})
     */
    private $pointVente;


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
     * Set contacts
     *
     * @param string $contacts
     *
     * @return InfoHopital
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
     * Set emailHopital
     *
     * @param string $emailHopital
     *
     * @return InfoHopital
     */
    public function setEmailHopital($emailHopital)
    {
        $this->emailHopital = $emailHopital;

        return $this;
    }

    /**
     * Get emailHopital
     *
     * @return string
     */
    public function getEmailHopital()
    {
        return $this->emailHopital;
    }

    /**
     * Set localisationHopital
     *
     * @param string $localisationHopital
     *
     * @return InfoHopital
     */
    public function setLocalisationHopital($localisationHopital)
    {
        $this->localisationHopital = $localisationHopital;

        return $this;
    }

    /**
     * Get localisationHopital
     *
     * @return string
     */
    public function getLocalisationHopital()
    {
        return $this->localisationHopital;
    }

    /**
     * Set hopital
     *
     * @param \PS\ParametreBundle\Entity\Hopital $hopital
     *
     * @return InfoHopital
     */
    public function setHopital(\PS\ParametreBundle\Entity\Hopital $hopital)
    {
        $this->hopital = $hopital;

        return $this;
    }

    /**
     * Get hopital
     *
     * @return \PS\ParametreBundle\Entity\Hopital
     */
    public function getHopital()
    {
        return $this->hopital;
    }

    /**
     * Set nomResponsable
     *
     * @param string $nomResponsable
     *
     * @return InfoHopital
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
     * Set logoHopital
     *
     * @param string $logoHopital
     *
     * @return InfoHopital
     */
    public function setLogoHopital($logoHopital)
    {
        $this->logoHopital = $logoHopital;

        return $this;
    }

    /**
     * Get logoHopital
     *
     * @return string
     */
    public function getLogoHopital()
    {
        return $this->logoHopital;
    }

    /**
     * Set pointVente
     *
     * @param boolean $pointVente
     *
     * @return InfoHopital
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
