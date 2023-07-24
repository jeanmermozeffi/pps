<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConsultationDonnee
 *
 * @ORM\Table(name="consultation_donnee")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\ConsultationDonneeRepository")
 */
class ConsultationDonnee
{
    const DATA = ['Problèmes posés', 'Au total'];
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
     * @var string
     *
     * @ORM\Column(name="valeur", type="text")
     */
    private $valeur;


     /**
     * @ORM\ManyToOne(targetEntity="Consultation", inversedBy="constantes")
     * @ORM\JoinColumn(nullable=false)
     */
     private $consultation;


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
     * @return ConsultationDonnee
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
     * Set valeur
     *
     * @param string $valeur
     *
     * @return ConsultationDonnee
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;

        return $this;
    }

    /**
     * Get valeur
     *
     * @return string
     */
    public function getValeur()
    {
        return $this->valeur;
    }


    /**
     * Set consultation.
     *
     * @param \PS\GestionBundle\Entity\Consultation $consultation
     *
     * @return ConsultationConstante
     */
    public function setConsultation(\PS\GestionBundle\Entity\Consultation $consultation)
    {
        $this->consultation = $consultation;

        return $this;
    }

    /**
     * Get consultation.
     *
     * @return \PS\GestionBundle\Entity\Consultation
     */
    public function getConsultation()
    {
        return $this->consultation;
    }
}

