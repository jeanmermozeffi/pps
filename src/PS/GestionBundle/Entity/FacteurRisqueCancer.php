<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * FacteurRisqueCancer
 *
 * @ORM\Table(name="facteur_risque_cancer")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\FacteurRisqueCancerRepository")
 */
class FacteurRisqueCancer
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
     * @var bool
     *
     * @ORM\Column(name="traitementHormoMamno", type="boolean")
     * @Assert\NotBlank(message="Veuillez répondre par oui ou non")
     */
    private $traitementHormoMamno;

    /**
     * @var bool
     *
     * @ORM\Column(name="cancerSeinMamno", type="boolean")
     *  @Assert\NotBlank(message="Veuillez répondre par oui ou non")
     */
    private $cancerSeinMamno;

    /**
     * @ORM\OneToOne(targetEntity="\PS\GestionBundle\Entity\FicheDepistageCancer", mappedBy="facteurRisquecancer")
     */
    private $ficheDepistageCancer;


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
     * Set traitementHormoMamno
     *
     * @param boolean $traitementHormoMamno
     * @return FacteurRisqueCancer
     */
    public function setTraitementHormoMamno($traitementHormoMamno)
    {
        $this->traitementHormoMamno = $traitementHormoMamno;
    
        return $this;
    }

    /**
     * Get traitementHormoMamno
     *
     * @return boolean 
     */
    public function getTraitementHormoMamno()
    {
        return $this->traitementHormoMamno;
    }

    /**
     * Set cancerSeinMamno
     *
     * @param boolean $cancerSeinMamno
     * @return FacteurRisqueCancer
     */
    public function setCancerSeinMamno($cancerSeinMamno)
    {
        $this->cancerSeinMamno = $cancerSeinMamno;
    
        return $this;
    }

    /**
     * Get cancerSeinMamno
     *
     * @return boolean 
     */
    public function getCancerSeinMamno()
    {
        return $this->cancerSeinMamno;
    }

    /**
     * Set ficheDepistageCancer
     *
     * @param \PS\GestionBundle\Entity\FicheDepistageCancer $ficheDepistageCancer
     * @return FacteurRisqueCancer
     */
    public function setFicheDepistageCancer(\PS\GestionBundle\Entity\FicheDepistageCancer $ficheDepistageCancer = null)
    {
        $this->ficheDepistageCancer = $ficheDepistageCancer;
    
        return $this;
    }

    /**
     * Get ficheDepistageCancer
     *
     * @return \PS\GestionBundle\Entity\FicheDepistageCancer 
     */
    public function getFicheDepistageCancer()
    {
        return $this->ficheDepistageCancer;
    }
}
