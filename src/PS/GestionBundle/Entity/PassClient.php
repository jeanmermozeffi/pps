<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PS\GestionBundle\Validator\Constraints as PSAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * PassClient
 *
 * @ORM\Table(name="pass_client")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\PassClientRepository")
 * @UniqueEntity("identifiant", message="Identifiant déjà existant dans la base de données")
 * @GRID\Source(columns="id,identifiant, pin, contact", filterable=false, sortable=false)
 * @PSAssert\Pass
 */
class PassClient
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *  @GRID\Column(visible=false)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="contact", type="string", length=25)
     * @GRID\Column(title="Contact")
     */
    private $contact;

    /**
     * @var string
     *
     * @ORM\Column(name="identifiant", type="string", length=10, unique=true)
     * @GRID\Column(title="Identifiant")
     */
    private $identifiant;

    /**
     * @var string
     *
     * @ORM\Column(name="pin", type="string", length=10)
     * @GRID\Column(title="PIN")
     */
    private $pin;


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
     * Set contact.
     *
     * @param string $contact
     *
     * @return PassClient
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact.
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set identifiant.
     *
     * @param string $identifiant
     *
     * @return PassClient
     */
    public function setIdentifiant($identifiant)
    {
        $this->identifiant = $identifiant;

        return $this;
    }

    /**
     * Get identifiant.
     *
     * @return string
     */
    public function getIdentifiant()
    {
        return $this->identifiant;
    }

    /**
     * Set pin.
     *
     * @param string $pin
     *
     * @return PassClient
     */
    public function setPin($pin)
    {
        $this->pin = $pin;

        return $this;
    }

    /**
     * Get pin.
     *
     * @return string
     */
    public function getPin()
    {
        return $this->pin;
    }
}
