<?php

namespace PS\GestionBundle\Entity;

use PS\GestionBundle\Repository\PaiementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 *  @ORM\Table(name="paiement")
 * @ORM\Entity(repositoryClass=PaiementRepository::class)
 */
class Paiement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $montant;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $prenoms;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $device;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $moyenPayement;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $contact;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $emailPaiement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $referencePayement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $typeDemande;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datePayement;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statutPayement;

    public function __construct()
    {
        $this->datePayement = new \DateTime();
        $this->referencePayement = 'REF-' . time();
        $this->device = 'XOF';
        $this->statutPayement = false;
        $this->description = '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenoms(): ?string
    {
        return $this->prenoms;
    }

    public function setPrenoms(string $prenoms): self
    {
        $this->prenoms = $prenoms;

        return $this;
    }

    public function getDevice(): ?string
    {
        return $this->device;
    }

    public function setDevice(string $device): self
    {
        $this->device = $device;

        return $this;
    }

    public function getMoyenPayement(): ?string
    {
        return $this->moyenPayement;
    }

    public function setMoyenPayement(string $moyenPayement): self
    {
        $this->moyenPayement = $moyenPayement;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getEmailPaiement(): ?string
    {
        return $this->emailPaiement;
    }

    public function setEmailPaiement(string $emailPaiementt): self
    {
        $this->emailPaiement = $emailPaiementt;

        return $this;
    }

    public function getReferencePayement(): ?string
    {
        return $this->referencePayement;
    }

    public function setReferencePayement(string $referencePayement): self
    {
        $this->referencePayement = $referencePayement;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTypeDemande(): ?bool
    {
        return $this->typeDemande;
    }

    public function setTypeDemande(bool $typeDemande): self
    {
        $this->typeDemande = $typeDemande;

        return $this;
    }

    public function getDatePayement(): ?\DateTimeInterface
    {
        return $this->datePayement;
    }

    public function setDatePayement(\DateTimeInterface $datePayement): self
    {
        $this->datePayement = $datePayement;

        return $this;
    }

    public function getStatutPayement(): ?bool
    {
        return $this->statutPayement;
    }

    public function setStatutPayement(bool $statutPayement): self
    {
        $this->statutPayement = $statutPayement;

        return $this;
    }

}
