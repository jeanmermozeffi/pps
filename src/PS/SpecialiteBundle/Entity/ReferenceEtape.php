<?php

namespace PS\SpecialiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReferenceEtape
 *
 * @ORM\Table(name="reference_etape")
 * @ORM\Entity(repositoryClass="PS\SpecialiteBundle\Repository\ReferenceEtapeRepository")
 */
class ReferenceEtape
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
     * @ORM\Column(name="valeur_reference", type="string", length=180)
     */
    private $valeurReference;

    /**
     * @ORM\ManyToOne(targetEntity="Champ")
     */
    private $champ;

    /**
     * @ORM\ManyToOne(targetEntity="Etape", inversedBy="references")
     */
    private $etape;


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
     * Set valeurReference
     *
     * @param string $valeurReference
     *
     * @return ReferenceEtape
     */
    public function setValeurReference($valeurReference)
    {
        $this->valeurReference = $valeurReference;

        return $this;
    }

    /**
     * Get valeurReference
     *
     * @return string
     */
    public function getValeurReference()
    {
        return $this->valeurReference;
    }

    /**
     * Set champ
     *
     * @param \PS\SpecialiteBundle\Entity\Champ $champ
     *
     * @return ReferenceEtape
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

    /**
     * Set etape
     *
     * @param \PS\SpecialiteBundle\Entity\Etape $etape
     *
     * @return ReferenceEtape
     */
    public function setEtape(\PS\SpecialiteBundle\Entity\Etape $etape = null)
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
}
