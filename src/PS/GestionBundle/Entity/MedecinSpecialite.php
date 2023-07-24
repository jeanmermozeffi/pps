<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MedecinSpecialite
 *
 * @ORM\Table(name="medecin_specialite")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\MedecinSpecialiteRepository")
 */
class MedecinSpecialite
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
     * @ORM\ManyToOne(targetEntity="Medecin", inversedBy="ligneSpecialites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $medecin;


     /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Specialite")
     * @ORM\JoinColumn(nullable=false)
     */
    private $specialite;


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
     * Set medecin
     *
     * @param \PS\GestionBundle\Entity\Medecin $medecin
     *
     * @return MedecinSpecialite
     */
    public function setMedecin(\PS\GestionBundle\Entity\Medecin $medecin)
    {
        $this->medecin = $medecin;

        return $this;
    }

    /**
     * Get medecin
     *
     * @return \PS\GestionBundle\Entity\Medecin
     */
    public function getMedecin()
    {
        return $this->medecin;
    }

    /**
     * Set specialite
     *
     * @param \PS\ParametreBundle\Entity\Specialite $specialite
     *
     * @return MedecinSpecialite
     */
    public function setSpecialite(\PS\ParametreBundle\Entity\Specialite $specialite)
    {
        $this->specialite = $specialite;

        return $this;
    }

    /**
     * Get specialite
     *
     * @return \PS\GestionBundle\Specialite
     */
    public function getSpecialite()
    {
        return $this->specialite;
    }
}
