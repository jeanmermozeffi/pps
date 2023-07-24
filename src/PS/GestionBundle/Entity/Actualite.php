<?php

namespace PS\GestionBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\ExclusionPolicy;


/**
 * Actualite
 * @ExclusionPolicy("all")
 * @ORM\Table(name="actualite")
 * @GRID\Source(columns="id,titre,categorie.libelle,date,auteur", operatorsVisible=false)
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\ActualiteRepository")
 */
class Actualite
{
    /**
     * @var int
     * @Expose
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(visible=false)
     * @Groups({"actualite", "detail-actualite"})
     */
    private $id;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="titre", type="string", length=255)
     * @GRID\Column(title="Titre")
     * @Assert\NotBlank(message="Veuillez renseigner un titre")
     * @Groups({"actualite", "detail-actualite"})
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", length=255)
     */
    private $alias;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="contenu", type="text")
     * @Assert\NotBlank(message="Veuillez renseigner un contenu")
     * @Groups({"detail-actualite"})
     */
    private $contenu;

    /**
     * @var \DateTime
     * @Expose
     * @ORM\Column(name="date", type="datetime")
     * @GRID\Column(title="Date de publication")
     *  @Groups({"detail-actualite", "actualite"})
     */
    private $date;

     /**
     * @var string
     * @Expose
     * @ORM\Column(type="string")
     * @GRID\Column(title="Auteur")
     * @Groups({"actualite", "detail-actualite"})
     */
    private $auteur;

    /**
     * @Expose
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Categorie")
     * @ORM\JoinColumn(nullable=false)
     * @GRID\Column(title="Catégorie", field="categorie.libelle")
     * @Assert\NotBlank(message="Veuillez sélectionner une catégorie")
     * @Groups({"actualite", "detail-actualite"})
     */
    private $categorie;


    public function __construct()
    {
        $this->setDate(new DateTime());
        $this->setAlias('');
        $this->setAuteur('');
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
     * Set titre
     *
     * @param string $titre
     *
     * @return Actualite
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set alias
     *
     * @param string $alias
     *
     * @return Actualite
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Actualite
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Actualite
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set auteur
     *
     * @param string $auteur
     *
     * @return Actualite
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return string
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set categorie
     *
     * @param \PS\ParametreBundle\Entity\Categorie $categorie
     *
     * @return Actualite
     */
    public function setCategorie(\PS\ParametreBundle\Entity\Categorie $categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \PS\ParametreBundle\Entity\Categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }
}
