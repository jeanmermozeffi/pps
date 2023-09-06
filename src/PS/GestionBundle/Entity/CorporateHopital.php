<?php
namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use PS\UtilisateurBundle\Entity\Personne;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Constraints as Assert;




/**
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\CorporateHopitalRepository")
 */
class CorporateHopital
{
    /**
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @GRID\Column(title="ID", primary=true)
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Hopital", inversedBy="corporateHopital", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $hopital;


    /**
     * @ORM\ManyToOne(targetEntity="PS\GestionBundle\Entity\Corporate", inversedBy="corporateHopital", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $corporate;

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
     * Set hopital
     *
     * @param \PS\ParametreBundle\Entity\Hopital $hopital
     *
     * @return CorporateHopital
     */
    public function setHopital(\PS\ParametreBundle\Entity\Hopital $hopital)
    {
        $this->hopital = $hopital;

        return $this;
    }

    /**
     * Get hopital
     *
     * @return \PS\ParametreBundle\Entity\Hopital
     */
    public function getHopital()
    {
        return $this->hopital;
    }

    /**
     * Set corporate
     *
     * @param \PS\GestionBundle\Entity\Corporate $corporate
     *
     * @return CorporateHopital
     */
    public function setCorporate(\PS\GestionBundle\Entity\Corporate $corporate)
    {
        $this->corporate = $corporate;

        return $this;
    }

    /**
     * Get corporate
     *
     * @return \PS\GestionBundle\Entity\Corporate
     */
    public function getCorporate()
    {
        return $this->corporate;
    }
}
