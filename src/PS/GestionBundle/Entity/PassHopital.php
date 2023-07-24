<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PassHopital
 *
 * @ORM\Table(name="pass_hopital")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\PassHopitalRepository")
 */
class PassHopital
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
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Hopital")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hopital;


    /**
     * @ORM\OneToOne(targetEntity="PS\ParametreBundle\Entity\Pass")
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
     * Set hopital
     *
     * @param \PS\ParametreBundle\Entity\Hopital $hopital
     *
     * @return PassHopital
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
     * Set pass
     *
     * @param \PS\ParametreBundle\Entity\Pass $pass
     *
     * @return PassHopital
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
