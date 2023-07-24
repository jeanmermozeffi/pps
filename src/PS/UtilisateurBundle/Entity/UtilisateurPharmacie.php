<?php

namespace PS\UtilisateurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UtilisateurPreference
 *
 * @ORM\Table(name="utilisateur_pharmacie")
 * @ORM\Entity(repositoryClass="PS\UtilisateurBundle\Repository\UtilisateurPharmacieRepository")
 */
class UtilisateurPharmacie
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
     * @ORM\ManyToOne(targetEntity="Utilisateur", inversedBy="utilisateurPharmacie")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;


     /**
     * @ORM\ManyToOne(targetEntity="\PS\GestionBundle\Entity\Pharmacie")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pharmacie;



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
     * Set utilisateur
     *
     * @param \PS\UtilisateurBundle\Entity\Utilisateur $utilisateur
     *
     * @return Utilisateurpharmacie
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

    /**
     * Set pharmacie
     *
     * @param \PS\GestionBundle\Entity\Pharmacie $pharmacie
     *
     * @return Utilisateurpharmacie
     */
    public function setPharmacie(\PS\GestionBundle\Entity\Pharmacie $pharmacie)
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
}
