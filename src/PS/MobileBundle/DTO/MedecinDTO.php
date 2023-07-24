<?php

namespace PS\MobileBundle\DTO;

use PS\GestionBundle\Entity\Consultation;
use PS\GestionBundle\Service\Util;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PS\MobileBundle\Validator\Constraints\UniqueValueEntity;
use PS\UtilisateurBundle\Entity\Utilisateur;
use PS\ParametreBundle\Entity\Hopital;
use PS\ParametreBundle\Entity\Specialite;

class MedecinDTO
{
    /**
     * Undocumented variable
     *
     * @var string
     * @Assert\NotBlank(message="Veuillez renseigner le pseudo")
     * @UniqueValueEntity(entityClass=Utilisateur::class, field="username", message="Ce pseudo est déjà utilisé")
     */
    private $username;

    /**
     * Undocumented variable
     *
     * @var string
     *  @Assert\NotBlank(message="Veuillez renseigner le mot de passe")
     * 
     */
    private $password;

    /**
     * Undocumented variable
     *
     * @var string
     *  @Assert\NotBlank(message="Veuillez renseigner le nom")
     */
    private $nom;


    /**
     * Undocumented variable
     *
     * @var string
     * @Assert\NotBlank(message="Veuillez renseigner le prénom")
     */
    private $prenom;

    /**
     * Undocumented variable
     *
     * @var string
     * @Assert\NotBlank(message="Veuillez renseigner le matricule")
     */
    private $matricule;


    /**
     * Undocumented variable
     *
     * @var string
     * @Assert\NotBlank(message="Veuillez renseigner une adresse mail")
     * @Assert\Email(message="Veuillez renseigner une adresse mail valide")
     *  @UniqueValueEntity(entityClass=Utilisateur::class, field="email", message="Cette adresse mail est déjà utilisée")
     */
    private $email;

     /**
     * Undocumented variable
     *
     * @var string
     * @Assert\NotBlank(message="Veuillez renseigner le contact")
     */
    private $contact;

     /**
     * Undocumented variable
     *
     * @var ArrayCollection
     * @Assert\Count(min=1, minMessage="Veuillez ajouter au moins une spécialité")
     */
    private $specialites;


     /**
     * Undocumented variable
     *
     * @var Hopital
     * @Assert\NotBlank(message="Veuillez renseigner l'hôpital")
     */
    private $hopital;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->specialites = new ArrayCollection();

    }

     /**
     * Add specialite
     *
     * @param \PS\ParametreBundle\Entity\Specialite $specialite
     *
     * @return Hopital
     */
    public function addSpecialite(\PS\ParametreBundle\Entity\Specialite $specialite)
    {
        if ($this->specialites->contains($specialite)) { 
            return; 
        }
        $this->specialites[] = $specialite;

        return $this;
    }

    /**
     * Remove specialite
     *
     * @param \PS\ParametreBundle\Entity\Specialite $specialite
     */
    public function removeSpecialite(Specialite $specialite)
    {
        $this->specialites->removeElement($specialite);
    }

    /**
     * Get specialites
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSpecialites()
    {
        return $this->specialites;
    }


    /**
     * Get undocumented variable
     *
     * @return  string
     */ 
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set undocumented variable
     *
     * @param  string  $contact  Undocumented variable
     *
     * @return  self
     */ 
    public function setContact(string $contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get undocumented variable
     *
     * @return  string
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set undocumented variable
     *
     * @param  string  $email  Undocumented variable
     *
     * @return  self
     */ 
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get undocumented variable
     *
     * @return  string
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set undocumented variable
     *
     * @param  string  $username  Undocumented variable
     *
     * @return  self
     */ 
    public function setUsername(string $username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get undocumented variable
     *
     * @return  string
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set undocumented variable
     *
     * @param  string  $password  Undocumented variable
     *
     * @return  self
     */ 
    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get undocumented variable
     *
     * @return  string
     */ 
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set undocumented variable
     *
     * @param  string  $nom  Undocumented variable
     *
     * @return  self
     */ 
    public function setNom(string $nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get undocumented variable
     *
     * @return  string
     */ 
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set undocumented variable
     *
     * @param  string  $prenom  Undocumented variable
     *
     * @return  self
     */ 
    public function setPrenom(string $prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }


    /**
     * Get undocumented variable
     *
     * @return  Hopital
     */ 
    public function getHopital()
    {
        return $this->hopital;
    }

    /**
     * Set undocumented variable
     *
     * @param  Hopital  $hopital  Undocumented variable
     *
     * @return  self
     */ 
    public function setHopital(Hopital $hopital)
    {
        $this->hopital = $hopital;

        return $this;
    }

    /**
     * Get undocumented variable
     *
     * @return  string
     */ 
    public function getMatricule()
    {
        return $this->matricule;
    }

    /**
     * Set undocumented variable
     *
     * @param  string  $matricule  Undocumented variable
     *
     * @return  self
     */ 
    public function setMatricule(string $matricule)
    {
        $this->matricule = $matricule;

        return $this;
    }
}