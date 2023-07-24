<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypePaiement
 *
 * @ORM\Table(name="type_paiement")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\TypePaiementRepository")
 */
class TypePaiement
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
     * @ORM\Column(name="libTypePaiement", type="string", length=100, unique=true)
     */
    private $libTypePaiement;


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
     * Set libTypePaiement
     *
     * @param string $libTypePaiement
     *
     * @return TypePaiement
     */
    public function setLibTypePaiement($libTypePaiement)
    {
        $this->libTypePaiement = $libTypePaiement;

        return $this;
    }

    /**
     * Get libTypePaiement
     *
     * @return string
     */
    public function getLibTypePaiement()
    {
        return $this->libTypePaiement;
    }
}

