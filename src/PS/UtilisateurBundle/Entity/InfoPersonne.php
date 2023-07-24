<?php

namespace PS\UtilisateurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InfoPersonne
 *
 * @ORM\Table(name="info_personne")
 * @ORM\Entity(repositoryClass="PS\UtilisateurBundle\Repository\PersonneRepository")
 */
class InfoPersonne
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
     * @ORM\OneToOne(targetEntity="Personne", inversedBy="info")
     */
    private $personne;


    /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Pays", cascade={"persist"})
     */
    private $pays;


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
     * Set personne
     *
     * @param \PS\UtilisateurBundle\Entity\Personne $personne
     *
     * @return InfoPersonne
     */
    public function setPersonne(\PS\UtilisateurBundle\Entity\Personne $personne = null)
    {
        $this->personne = $personne;

        return $this;
    }

    /**
     * Get personne
     *
     * @return \PS\UtilisateurBundle\Entity\Personne
     */
    public function getPersonne()
    {
        return $this->personne;
    }

    /**
     * Set pays
     *
     * @param \PS\ParametreBundle\Entity\Pays $pays
     *
     * @return InfoPersonne
     */
    public function setPays(\PS\ParametreBundle\Entity\Pays $pays = null)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return \PS\ParametreBundle\Entity\Pays
     */
    public function getPays()
    {
        return $this->pays;
    }
}
