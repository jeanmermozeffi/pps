<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistoriqueUrgence
 *
 * @ORM\Table(name="historique_urgence")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\HistoriqueUrgenceRepository")
 */
class HistoriqueUrgence
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="text")
     */
    private $libelle;


     /**
     * @ORM\ManyToOne(targetEntity="Urgence", inversedBy="historiques")
     * @ORM\JoinColumn(nullable=false)
     */
    private $urgence;



     /**
     * @ORM\ManyToOne(targetEntity="PS\UtilisateurBundle\Entity\Utilisateur")
     * @ORM\JoinColumn(nullable=false)
    */
    private $utilisateur;


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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return HistoriqueUrgence
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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return HistoriqueUrgence
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
     * Set urgence
     *
     * @param \PS\GestionBundle\Entity\Urgence $urgence
     *
     * @return HistoriqueUrgence
     */
    public function setUrgence(\PS\GestionBundle\Entity\Urgence $urgence)
    {
        $this->urgence = $urgence;

        return $this;
    }

    /**
     * Get urgence
     *
     * @return \PS\GestionBundle\Entity\Urgence
     */
    public function getUrgence()
    {
        return $this->urgence;
    }

    /**
     * Set utilisateur
     *
     * @param \PS\UtilisateurBundle\Entity\Utilisateur $utilisateur
     *
     * @return HistoriqueUrgence
     */
    public function setUtilisateur(\PS\UtilisateurBundle\Entity\Utilisateur $utilisateur)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return \PS\UtilisateurBundle\Entity\Utilisateur
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }
}
