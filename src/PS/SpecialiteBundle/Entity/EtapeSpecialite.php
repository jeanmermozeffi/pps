<?php

namespace PS\SpecialiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EtapeSpecialite
 *
 * @ORM\Table(name="etape_specialite")
 * @ORM\Entity(repositoryClass="PS\SpecialiteBundle\Repository\EtapeSpecialiteRepository")
 */
class EtapeSpecialite
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
     * @var int
     *
     * @ORM\Column(name="ordre_etape", type="integer")
     */
    private $ordreEtape = 1;


    /**
     * @ORM\ManyToOne(targetEntity="Etape")
     * @ORM\JoinColumn(nullable=false)
     */
    private $etape;

     /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Specialite")
     * @ORM\JoinColumn(nullable=false)
     */
    private $specialite;

     /**
     * @ORM\ManyToOne(targetEntity="PS\SpecialiteBundle\Entity\Champ")
     */
    private $champ;

    /**
     * @var int
     *
     * @ORM\Column(name="valeur_champ_etape", type="text")
     */
    private $valeurChampEtape;


   


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
     * Set ordreEtape
     *
     * @param integer $ordreEtape
     *
     * @return EtapeSpecialite
     */
    public function setOrdreEtape($ordreEtape)
    {
        $this->ordreEtape = $ordreEtape;

        return $this;
    }

    /**
     * Get ordreEtape
     *
     * @return int
     */
    public function getOrdreEtape()
    {
        return $this->ordreEtape;
    }

    /**
     * Set etape
     *
     * @param \PS\SpecialiteBundle\Entity\Etape $etape
     *
     * @return EtapeSpecialite
     */
    public function setEtape(\PS\SpecialiteBundle\Entity\Etape $etape)
    {
        $this->etape = $etape;

        return $this;
    }

    /**
     * Get etape
     *
     * @return \PS\SpecialiteBundle\Entity\Etape
     */
    public function getEtape()
    {
        return $this->etape;
    }

    /**
     * Set specialite
     *
     * @param \PS\ParametreBundle\Entity\Specialite $specialite
     *
     * @return EtapeSpecialite
     */
    public function setSpecialite(\PS\ParametreBundle\Entity\Specialite $specialite)
    {
        $this->specialite = $specialite;

        return $this;
    }

    /**
     * Get specialite
     *
     * @return \PS\ParametreBundle\Entity\Specialite
     */
    public function getSpecialite()
    {
        return $this->specialite;
    }

    /**
     * Set valeurChampEtape
     *
     * @param string $valeurChampEtape
     *
     * @return EtapeSpecialite
     */
    public function setValeurChampEtape($valeurChampEtape)
    {
        $this->valeurChampEtape = $valeurChampEtape;

        return $this;
    }

    /**
     * Get valeurChampEtape
     *
     * @return string
     */
    public function getValeurChampEtape()
    {
        return $this->valeurChampEtape;
    }

    /**
     * Set champ
     *
     * @param \PS\SpecialiteBundle\Entity\Champ $champ
     *
     * @return EtapeSpecialite
     */
    public function setChamp(\PS\SpecialiteBundle\Entity\Champ $champ = null)
    {
        $this->champ = $champ;

        return $this;
    }

    /**
     * Get champ
     *
     * @return \PS\SpecialiteBundle\Entity\Champ
     */
    public function getChamp()
    {
        return $this->champ;
    }
}
