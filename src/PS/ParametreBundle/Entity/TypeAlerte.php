<?php

namespace PS\ParametreBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * TypeAlerte
 *
 * @ORM\Table(name="type_alerte")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\TypeAlerteRepository")
 */
class TypeAlerte
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(visible=false)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=100)
     * @Assert\NotBlank(message="Veuillez choisir un libellé")
     * @GRID\Column(title="Libellé")
     */
    private $libelle;

     /**
     * @var int
     *
     * @ORM\Column(name="frequence", type="smallint")
     * @Assert\NotBlank(message="Veuillez renseigner la fréquence")
     * @GRID\Column(title="Fréquence")
     */
    private $frequence;


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
     * @return TypeAlerte
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
     * Set frequence
     *
     * @param integer $frequence
     *
     * @return TypeAlerte
     */
    public function setFrequence($frequence)
    {
        $this->frequence = $frequence;

        return $this;
    }

    /**
     * Get frequence
     *
     * @return integer
     */
    public function getFrequence()
    {
        return $this->frequence;
    }
}
