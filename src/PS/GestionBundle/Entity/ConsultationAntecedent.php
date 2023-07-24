<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConsultationAntecedent
 *
 * @ORM\Table(name="consultation_antecedent")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\ConsultationAntecedentRepository")
 */
class ConsultationAntecedent
{
    const ANTECEDENTS = ['Diabète', 'HTA'];
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
     * @ORM\Column(type="text")
     */
    private $antecedent;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=50)
     */
    private $type;



    /**
     * @ORM\ManyToOne(targetEntity="Consultation", inversedBy="antecedents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $consultation;




    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lien;



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
     * Set antecedent
     *
     * @param string $antecedent
     *
     * @return $this
     */
    public function setAntecedent(string $antecedent)
    {
        $this->antecedent = $antecedent;

        return $this;
    }

    /**
     * Get antecedent
     *
     * @return string
     */
    public function getAntecedent()
    {
        return $this->antecedent;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return ConsultationAntecedent
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
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


    /**
     * Set lien
     *
     * @param string $lien
     *
     * @return PatientAntecedent
     */
    public function setLien($lien)
    {
        $this->lien = $lien;

        return $this;
    }

    /**
     * Get lien
     *
     * @return string
     */
    public function getLien()
    {
        return $this->lien;
    }
}
