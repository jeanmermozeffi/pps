<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ListeExamen
 *
 * @ORM\Table(name="liste_examen")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\ListeExamenRepository")
 */
class ListeExamen
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;



     /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;


    /**
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;


    /**
     * 
     * @ORM\OneToMany(targetEntity="ExamenAffection", mappedBy="examen", cascade={"persist", "remove"})
     */
    private $affections;


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
     * @return ListeExamen
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
     * Set categorie
     *
     * @param \PS\ParametreBundle\Entity\Categorie $categorie
     *
     * @return ListeExamen
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->affections = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add affection
     *
     * @param \PS\ParametreBundle\Entity\ExamenAffection $affection
     *
     * @return ListeExamen
     */
    public function addAffection(\PS\ParametreBundle\Entity\ExamenAffection $affection)
    {
        $this->affections[] = $affection;
        $affection->setExamen($this);
        return $this;
    }

    /**
     * Remove affection
     *
     * @param \PS\ParametreBundle\Entity\ExamenAffection $affection
     */
    public function removeAffection(\PS\ParametreBundle\Entity\ExamenAffection $affection)
    {
        $this->affections->removeElement($affection);
    }

    /**
     * Get affections
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAffections()
    {
        return $this->affections;
    }
}
