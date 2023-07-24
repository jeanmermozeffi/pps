<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Pack
 *
 * @ORM\Table(name="pack")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\PackRepository")
 * @GRID\Source(columns="id,libelle, alias, prix, full_duree, duree, typeDuree")
 * @GRID\Column(id="full_duree", type="join", title="Durée", columns={"duree", "typeDuree"}, operatorsVisible=false)
 */
class Pack
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(visible=false)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     * @Assert\NotBlank(message="Veuillez renseigner le libellé")
     * @GRID\Column(title="Libellé")
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="prix", type="decimal", precision=6, scale=2)
     * @Assert\NotBlank(message="Veuillez renseigner le prix")
     * @GRID\Column(title="Prix")
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", length=50, unique=true)
     * @Assert\NotBlank(message="Veuillez renseigner l'alias")
     * @GRID\Column(title="Alias")
     */
    private $alias;


    /**
     * @var string
     *
     * @ORM\Column(name="duree", type="smallint")
     * @Assert\NotBlank(message="Veuillez renseigner la durée")
     * @GRID\Column(visible=false)
     */
    private $duree;



    /**
     * @var string
     *
     * @ORM\Column(name="type_duree", type="string", length=5)
     * @Assert\NotBlank(message="Veuillez renseigner le type de durée")
     * @Assert\Choice(choices={"day", "month", "year"}, message="Choix inconnu")
     * @GRID\Column(visible=false)
     */
    private $typeDuree;

    /**
     * @ORM\Column(type="smallint", name="ordre")
     */
    private $ordre;


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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Pack
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
     * Set description
     *
     * @param string $description
     *
     * @return Pack
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set prix
     *
     * @param string $prix
     *
     * @return Pack
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return string
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set alias
     *
     * @param string $alias
     *
     * @return Pack
     */
    public function setAlias($alias)
    {
        $this->alias = mb_strtoupper($alias);

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
     * Set duree
     *
     * @param integer $duree
     *
     * @return Pack
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return integer
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Set typeDuree
     *
     * @param string $typeDuree
     *
     * @return Pack
     */
    public function setTypeDuree($typeDuree)
    {
        $this->typeDuree = $typeDuree;

        return $this;
    }

    /**
     * Get typeDuree
     *
     * @return string
     */
    public function getTypeDuree()
    {
        return $this->typeDuree;
    }

    /**
     * Set ordre
     *
     * @param integer $ordre
     *
     * @return Pack
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Get ordre
     *
     * @return integer
     */
    public function getOrdre()
    {
        return $this->ordre;
    }


    public function getFullDuree()
    {
        return $this->getDuree().' '.$this->getTypeDuree();
    }
}
