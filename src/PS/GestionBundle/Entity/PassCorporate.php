<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PS\GestionBundle\Entity\Corporate;

/**
 * PassCorporate
 *
 * @ORM\Table(name="pass_corporate")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\PassCorporateRepository")
 */
class PassCorporate
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
     * @ORM\ManyToOne(targetEntity="PS\GestionBundle\Entity\Corporate", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $corporate;


    /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Pass", inversedBy="passCorporate")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pass;


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
     * Set corporate
     *
     * @param \PS\GestionBundle\Entity\Corporate $corporate
     *
     * @return PassCorporate
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

    /**
     * Set pass
     *
     * @param \PS\ParametreBundle\Entity\Pass $pass
     *
     * @return PassCorporate
     */
    public function setPass(\PS\ParametreBundle\Entity\Pass $pass)
    {
        $this->pass = $pass;

        return $this;
    }

    /**
     * Get pass
     *
     * @return \PS\ParametreBundle\Entity\Pass
     */
    public function getPass()
    {
        return $this->pass;
    }
}
