<?php

namespace PS\UtilisateurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\Group as BaseGroup;

// use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * Groupe
 *
 * @ORM\Table(name="groupe")
 * @ORM\Entity
 */
class Groupe extends BaseGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\generatedValue(strategy="AUTO")
     */
    protected $id;

    /** 
     * @ORM\ManyToMany(targetEntity="Utilisateur", mappedBy="groups") 
     * 
     */
    protected $utilisateurs;

    public function __construct($name = '', $roles = array())
    {
        $this->name = $name;
        $this->roles = $roles;
    }

    public function __toString()
    {
        return $this->getName();
    }

    function getUtilisateurs()
    {
        return $this->utilisateurs;
    }

    /**
     * Add utilisateurs
     *
     * @param \PS\UtilisateurBundle\Entity\Utilisateur $utilisateurs
     * @return Groupe
     */
    public function addUtilisateur(\PS\UtilisateurBundle\Entity\Utilisateur $utilisateurs)
    {
        $this->utilisateurs[] = $utilisateurs;

        return $this;
    }

    /**
     * Remove utilisateurs
     *
     * @param \PS\UtilisateurBundle\Entity\Utilisateur $utilisateurs
     */
    public function removeUtilisateur(\PS\UtilisateurBundle\Entity\Utilisateur $utilisateurs)
    {
        $this->utilisateurs->removeElement($utilisateurs);
    }
}
