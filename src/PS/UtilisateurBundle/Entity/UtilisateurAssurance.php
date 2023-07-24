<?php

namespace PS\UtilisateurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * UtilisateurAssurance
 *
 * @ORM\Table(name="utilisateur_assurance")
 * @ORM\Entity(repositoryClass="PS\UtilisateurBundle\Repository\UtilisateurAssuranceRepository")
 * 
 */
class UtilisateurAssurance
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
     * @ORM\ManyToOne(targetEntity="Utilisateur", inversedBy="utilisateurAssurance")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;


    /**
     * @ORM\ManyToOne(targetEntity="\PS\ParametreBundle\Entity\Assurance", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $assurance;



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
     * @return UtilisateurAssurance
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
     * Set assurance
     *
     * @param \PS\ParametreBundle\Entity\Assurance $assurance
     *
     * @return UtilisateurAssurance
     */
    public function setAssurance(\PS\ParametreBundle\Entity\Assurance $assurance)
    {
        $this->assurance = $assurance;

        return $this;
    }

    /**
     * Get assurance
     *
     * @return \PS\ParametreBundle\Entity\Assurance
     */
    public function getAssurance()
    {
        return $this->assurance;
    }
}

