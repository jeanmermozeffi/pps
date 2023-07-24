<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PS\ParametreBundle\Entity\Analyse;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Expose;

/**
 * @ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\ConsultationAnalysesRepository")
 */
class ConsultationAnalyses
{
    /**
     * @Expose
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @Groups({"consultation-analyse"})
     */
    private $id;

    /**
     * @Expose
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Groups({"consultation-analyse"})
     */
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity="Consultation", inversedBy="analyses")
     * @Assert\NotBlank()
     */
    private $consultation;

    /**
     * @Expose
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank()
     * @Groups({"consultation-analyse"})
     */
    private $analyse;



    private $resultat;




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
     * Set libelle
     *
     * @param string $libelle
     * @return ConsultationAnalyses
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
     * Set analyse
     *
     * @param string $analyse
     * @return ConsultationAnalyses
     */
    public function setAnalyse($analyse)
    {
        $this->analyse = $analyse;

        return $this;
    }

    /**
     * Get analyse
     *
     * @return string 
     */
    public function getAnalyse()
    {
        return $this->analyse;
    }

    /**
     * Set consultation
     *
     * @param \PS\GestionBundle\Entity\Consultation $consultation
     * @return ConsultationAnalyses
     */
    public function setConsultation(\PS\GestionBundle\Entity\Consultation $consultation = null)
    {
        $this->consultation = $consultation;

        return $this;
    }

    /**
     * Get consultation
     *
     * @return \PS\GestionBundle\Entity\Consultation 
     */
    public function getConsultation()
    {
        return $this->consultation;
    }

    /**
     * Set resultat
     *
     * @param \PS\ParametreBundle\Entity\Fichier $resultat
     *
     * @return ConsultationAnalyses
     */
    public function setResultat(\PS\ParametreBundle\Entity\Fichier $resultat = null)
    {
        if (!is_null($resultat->getFile())) {
            $this->resultat = $resultat;
        }

        return $this;
    }

    /**
     * Get resultat
     *
     * @return \PS\ParametreBundle\Entity\Fichier
     */
    public function getResultat()
    {
        return $this->resultat;
    }
}
