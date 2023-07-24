<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ConsultationConstante
 *
 * @ORM\Table(name="consultation_constante")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\ConsultationConstanteRepository")
 */
class ConsultationConstante
{
     const DEFAULT_EMPTY_VALUES = [-99999];
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
     * @ORM\Column(name="valeur", type="string", length=20)
     */
    private $valeur;


    /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Constante")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Veuillez sélectionner une constante")
     */
    private $constante;


     /**
     * @ORM\ManyToOne(targetEntity="Consultation", inversedBy="constantes")
     */
    private $consultation;



     /**
     * @ORM\ManyToOne(targetEntity="Patient", inversedBy="constantes")
     */
    private $patient;




    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set valeur.
     *
     * @param string $valeur
     *
     * @return ConsultationConstante
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;

        return $this;
    }

    /**
     * Get valeur.
     *
     * @return string
     */
    public function getValeur()
    {
        return in_array($this->valeur, self::DEFAULT_EMPTY_VALUES) ? '': $this->valeur;
    }

    /**
     * Set constante.
     *
     * @param \PS\ParametreBundle\Entity\Constante $constante
     *
     * @return ConsultationConstante
     */
    public function setConstante(\PS\ParametreBundle\Entity\Constante $constante)
    {
        $this->constante = $constante;

        return $this;
    }

    /**
     * Get constante.
     *
     * @return \PS\ParametreBundle\Entity\Constante
     */
    public function getConstante()
    {
        return $this->constante;
    }

    /**
     * Set consultation.
     *
     * @param \PS\GestionBundle\Entity\Consultation $consultation
     *
     * @return ConsultationConstante
     */
    public function setConsultation(\PS\GestionBundle\Entity\Consultation $consultation = null)
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



     /**
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\Patient $patient
     * @return Consultation
     */
    public function setPatient(Patient $patient = null)
    {
        $this->patient = $patient;

        return $this;
    }

    /**
     * Get patient
     *
     * @return \PS\GestionBundle\Entity\Patient
     */
    public function getPatient()
    {
        return $this->patient;
    }
}
