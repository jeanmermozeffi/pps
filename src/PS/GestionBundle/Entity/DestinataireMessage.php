<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DestinataireMessage
 *
 * @ORM\Table(name="destinataire_message")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\DestinataireMessageRepository")
 */
class DestinataireMessage
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
     * @ORM\Column(name="statut", type="boolean")
     */
    private $statut;

    /**
     * @var string
     *
     * @ORM\Column(name="contact", type="string", length=255)
     */
    private $contact;

     /**
     * @ORM\ManyToOne(targetEntity="Message")
     * @ORM\JoinColumn(nullable=false)
     */
    private $message;


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
     * Set statut
     *
     * @param boolean $statut
     *
     * @return DestinataireMessage
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return bool
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set contact
     *
     * @param string $contact
     *
     * @return DestinataireMessage
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set message
     *
     * @param \PS\GestionBundle\Entity\Message $message
     *
     * @return DestinataireMessage
     */
    public function setMessage(\PS\GestionBundle\Entity\Message $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return \PS\GestionBundle\Entity\Message
     */
    public function getMessage()
    {
        return $this->message;
    }
}
