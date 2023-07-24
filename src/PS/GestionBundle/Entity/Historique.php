<?php
namespace PS\GestionBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\ORM\Mapping as ORM;
use PS\ParametreBundle\Entity\Affection;
use PS\ParametreBundle\Entity\Specialite;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="\PS\GestionBundle\Repository\HistoriqueRepository")
 */
class Historique
{
    const PROFILE_ID_ERROR = 'PROFILE_ID_ERROR';
    const CREATE_USER = 'CREATE_USER';
    const EDIT_USER = 'EDIT_USER';
    const DELETE_USER = 'DELETE_USER';
    const PROFILE_VIEW = 'PROFILE_VIEW';

   /**
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

     /**
     * @ORM\ManyToOne(targetEntity="\PS\UtilisateurBundle\Entity\Utilisateur")
     */
    private $utilisateur;

    /**
     * @ORM\Column(type="datetime", name="date_historique"))
     */
    private $date;

    /**
     * @ORM\Column(type="text", name="lib_historique")
     */
    private $libelle;


     /**
     * @ORM\Column(type="string", name="alias_historique", nullable=true)
     */
    private $alias;

     /**
     * @ORM\Column(type="string", length=6, nullable=true)
     */
    private $identifiant;

    /**
     * @ORM\Column(name="session_id", type="string")
    */
    private $session;




    /**
     * @ORM\ManyToOne(targetEntity="\PS\UtilisateurBundle\Entity\Personne", inversedBy="lignesHistorique")
     */
    private $personne;


    public function __construct()
    {
        $this->date = new \DateTime();
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
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Historique
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set libelle.
     *
     * @param string $libelle
     *
     * @return Historique
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle.
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set alias.
     *
     * @param string $alias
     *
     * @return Historique
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias.
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set identifiant.
     *
     * @param string|null $identifiant
     *
     * @return Historique
     */
    public function setIdentifiant($identifiant = null)
    {
        $this->identifiant = $identifiant;

        return $this;
    }

    /**
     * Get identifiant.
     *
     * @return string|null
     */
    public function getIdentifiant()
    {
        return $this->identifiant;
    }

    /**
     * Set utilisateur.
     *
     * @param \PS\UtilisateurBundle\Entity\Utilisateur|null $utilisateur
     *
     * @return Historique
     */
    public function setUtilisateur(\PS\UtilisateurBundle\Entity\Utilisateur $utilisateur = null)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur.
     *
     * @return \PS\UtilisateurBundle\Entity\Utilisateur|null
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * Set personne.
     *
     * @param \PS\UtilisateurBundle\Entity\Personne|null $personne
     *
     * @return Historique
     */
    public function setPersonne(\PS\UtilisateurBundle\Entity\Personne $personne = null)
    {
        $this->personne = $personne;

        return $this;
    }

    /**
     * Get personne.
     *
     * @return \PS\UtilisateurBundle\Entity\Personne|null
     */
    public function getPersonne()
    {
        return $this->personne;
    }

    /**
     * Set session.
     *
     * @param string $session
     *
     * @return Historique
     */
    public function setSession($session)
    {
        $this->session = $session;

        return $this;
    }

    /**
     * Get session.
     *
     * @return string
     */
    public function getSession()
    {
        return $this->session;
    }
}
