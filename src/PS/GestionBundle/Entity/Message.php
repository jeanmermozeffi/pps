<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\MessageRepository")
 */
class Message
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
     * @ORM\Column(name="contenu", type="string", length=160)
     */
    private $contenu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;


     /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_envoi", type="datetime", nullable=true)
     */
    private $dateEnvoi;



    /**
     * @var bool
     *
     * @ORM\Column(name="statut", type="boolean")
     */
    private $statut;


    /**
     * @ORM\OneToMany(targetEntity="DestinataireMessage",mappedBy="message", cascade={"persist"})
     * @Assert\Valid
     */
    private $destinataires;

   



    /**
     * @ORM\ManyToOne(targetEntity="PS\UtilisateurBundle\Entity\Utilisateur", cascade={"persist"})
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
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Message
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
     * @return Message
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
     * Set dateEnvoi
     *
     * @param \DateTime $dateEnvoi
     *
     * @return Message
     */
    public function setDateEnvoi($dateEnvoi)
    {
        $this->dateEnvoi = $dateEnvoi;

        return $this;
    }

    /**
     * Get dateEnvoi
     *
     * @return \DateTime
     */
    public function getDateEnvoi()
    {
        return $this->dateEnvoi;
    }

    /**
     * Set statut
     *
     * @param boolean $statut
     *
     * @return Message
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
     * Set utilisateur
     *
     * @param \PS\UtilisateurBundle\Entity\Utilisateur $utilisateur
     *
     * @return Message
     */
    public function setUtilisateur(\PS\UtilisateurBundle\Entity\Utilisateur $utilisateur = null)
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
     * Constructor
     */
    public function __construct()
    {
        $this->destinataires = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add destinataire
     *
     * @param \PS\GestionBundle\Entity\DestinataireMessage $destinataire
     *
     * @return Message
     */
    public function addDestinataire(\PS\GestionBundle\Entity\DestinataireMessage $destinataire)
    {
        $this->destinataires[] = $destinataire;
        $destinataire->setMessage($this);
        return $this;
    }

    /**
     * Remove destinataire
     *
     * @param \PS\GestionBundle\Entity\DestinataireMessage $destinataire
     */
    public function removeDestinataire(\PS\GestionBundle\Entity\DestinataireMessage $destinataire)
    {
        $this->destinataires->removeElement($destinataire);
    }

    /**
     * Get destinataires
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDestinataires()
    {
        return $this->destinataires;
    }
}
