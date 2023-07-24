<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PS\ParametreBundle\Entity\Analyse;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\LigneAnalyseRepository")
 */
class LigneAnalyse
{
    /**
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $libelle;

	/**
     * @ORM\ManyToOne(targetEntity="Consultation", inversedBy="analyses")
     * @Assert\NotBlank()
     */    
    private $consultation;

    /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Analyse")
     * @Assert\NotBlank()
     */
    private $analyse;


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
     * Set consultation
     *
     * @param \PS\GestionBundle\Entity\Consultation $consultation
     * @return LigneAnalyse
     */
    public function setConsultation(Consultation $consultation = null)
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
     * Set analyse
     *
     * @param \PS\ParametreBundle\Entity\Analyse $analyse
     * @return LigneAnalyse
     */
    public function setAnalyse(Analyse $analyse = null)
    {
        $this->analyse = $analyse;

        return $this;
    }

    /**
     * Get analyse
     *
     * @return \PS\ParametreBundle\Entity\Analyse 
     */
    public function getAnalyse()
    {
        return $this->analyse;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return LigneAnalyse
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
}
