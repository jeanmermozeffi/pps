<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commune
 *
 * @ORM\Table(name="commune")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\CommuneRepository")
 */
class Commune
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
     * @var string
     *
     * @ORM\Column(name="lib_commune", type="string", length=255)
     */
    private $libCommune;


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
     * Set libCommune
     *
     * @param string $libCommune
     *
     * @return Commune
     */
    public function setLibCommune($libCommune)
    {
        $this->libCommune = $libCommune;

        return $this;
    }

    /**
     * Get libCommune
     *
     * @return string
     */
    public function getLibCommune()
    {
        return $this->libCommune;
    }
}

