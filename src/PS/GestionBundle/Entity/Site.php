<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use APY\DataGridBundle\Grid\Mapping as GRID;
use JMS\Serializer\Annotation\Groups;

/**
 * Site
 *
 * @ORM\Table(name="site")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\SiteRepository")
 * @GRID\Source(columns="id, libelle, url, pays.nom, statut", filterable=false, sortable=false)
 */
class Site
{
    /**
     * @var int
     * @Groups({"site"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(title="ID")
     */
    private $id;

    /**
     * @var string
     * @Groups({"site"})
     * @ORM\Column(name="url_site", type="string", length=255)
     * @GRID\Column(title="site.form.url",  operators={"rlike"}, defaultOperator="rlike", filterable=true)
     */
    private $url;

    /**
     * @var string
     * @Groups({"site"})
     * @ORM\Column(name="lib_site", type="string", length=100)
     * @GRID\Column(title="site.form.libelle",  operators={"rlike"}, defaultOperator="rlike", filterable=true)
     */
    private $libelle;

    /**
     * @var boolean
     * @Groups({"site"})
     * @ORM\Column(name="statut_site", type="boolean")
     * @GRID\Column(title="site.form.statut", operators={"rlike"}, defaultOperator="rlike", filter="select"
            , selectFrom="values"
            , safe=false
            , align="center"
            , values={"0": "Inactif",  "1": "Actif"})
     */
    private $statut;

    /**
     * @ORM\ManyToMany(targetEntity="PS\ParametreBundle\Entity\Pays", cascade={"persist"})
     * @GRID\Column(field="pays.nom", visible=false, title="site.form.pays", type="array")
     * @Groups({"site", "pays"})
     */
    private $pays;


     /**
     * @ORM\Column(type="string", name="logo_site")
     * @Assert\NotNull(message="site.form.message", groups={"FileRequired"})
     * @Assert\File(mimeTypes={ "image/jpeg", "image/png" }, maxSize="2M")
     * @Groups({"site"})
     */
    private $logo;


    /**
     * @ORM\OneToMany(targetEntity="PS\GestionBundle\Entity\OptionSite", mappedBy="site")
     * @Assert\Valid
     */
    private $options;





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
     * Set urlSite
     *
     * @param string $urlSite
     *
     * @return Site
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get urlSite
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Site
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set statut
     *
     * @param boolean $statut
     *
     * @return Site
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return boolean
     */
    public function getStatut()
    {
        return $this->statut;
    }

    

    /**
     * Set logo
     *
     * @param string $logo
     *
     * @return Site
     */
    public function setLogo($logo)
    {
        if ($logo) {
            $this->logo = $logo;
        }
        

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pays = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add pay
     *
     * @param \PS\ParametreBundle\Entity\Pays $pay
     *
     * @return Site
     */
    public function addPay(\PS\ParametreBundle\Entity\Pays $pay)
    {
        $this->pays[] = $pay;

        return $this;
    }

    /**
     * Remove pay
     *
     * @param \PS\ParametreBundle\Entity\Pays $pay
     */
    public function removePay(\PS\ParametreBundle\Entity\Pays $pay)
    {
        $this->pays->removeElement($pay);
    }

    /**
     * Get pays
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Add option
     *
     * @param \Ps\GestionBundle\Entity\OptionSite $option
     *
     * @return Site
     */
    public function addOption(\PS\GestionBundle\Entity\OptionSite $option)
    {
        $this->options[] = $option;
        $option->setSite($this);
        return $this;
    }

    /**
     * Remove option
     *
     * @param \Ps\GestionBundle\Entity\OptionSite $option
     */
    public function removeOption(\PS\GestionBundle\Entity\OptionSite $option)
    {
        $this->options->removeElement($option);
    }

    /**
     * Get options
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOptions()
    {
        return $this->options;
    }
}
