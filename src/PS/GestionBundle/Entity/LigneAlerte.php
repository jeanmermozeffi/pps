<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * LigneAlerte
 * @ExclusionPolicy("all")
 * @ORM\Table(name="ligne_alerte")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\LigneAlerteRepository")
 */
class LigneAlerte
{


    /**
     * @var int
     * @Expose
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"ligne-alerte"})
     */
    private $id;


    
    /**
     * @var string
     * @Expose
     * @ORM\Column(name="libelle", type="string", length=100)
     * @Assert\NotBlank(message="Veuillez renseigner le libellé")
     * @Groups({"ligne-alerte"})
     */
    private $libelle;

    /**
     * @var \DateTime
     * @Expose
     * @ORM\Column(name="date_debut", type="datetime")
     * @Groups({"ligne-alerte"})
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="datetime", nullable=true)
     */
    private $dateFin;

    /**
     * @Expose
     * @var int
     *
     * @ORM\Column(name="frequence", type="integer")
     * @Groups({"ligne-alerte"})
     */
    private $frequence;


    
     /**
      * @Expose
     * @ORM\ManyToOne(targetEntity="Alerte", inversedBy="lignes")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"ligne-alerte"})
     */
    private $alerte;


    /**
     * @var string
     * @Expose
     * @ORM\Column(type="text")
     * @Groups({"ligne-alerte"})
     */
    private $commentaire;


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
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return LigneAlerte
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return LigneAlerte
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set frequence
     *
     * @param integer $frequence
     *
     * @return LigneAlerte
     */
    public function setFrequence($frequence)
    {
        $this->frequence = $frequence;

        return $this;
    }

    /**
     * Get frequence
     *
     * @return int
     */
    public function getFrequence()
    {
        return $this->frequence;
    }

    /**
     * Set alerte
     *
     * @param \PS\GestionBundle\Entity\Alerte $alerte
     *
     * @return LigneAlerte
     */
    public function setAlerte(\PS\GestionBundle\Entity\Alerte $alerte)
    {
        $this->alerte = $alerte;

        return $this;
    }

    /**
     * Get alerte
     *
     * @return \PS\GestionBundle\Entity\Alerte
     */
    public function getAlerte()
    {
        return $this->alerte;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return LigneAlerte
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
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return Alerte
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }
}
