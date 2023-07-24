<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExamenAffection
 *
 * @ORM\Table(name="examen_affection")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\ExamenAffectionRepository")
 */
class ExamenAffection
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
     * @ORM\Column(name="valeur_norme", type="string", length=20)
     */
    private $valeurNorme;


    /**
     * @ORM\ManyToOne(targetEntity="ListeExamen", inversedBy="affections")
     * @ORM\JoinColumn(nullable=false)
     */
    private $examen;


    /**
     * @ORM\ManyToOne(targetEntity="Affection")
     * @ORM\JoinColumn(nullable=false)
     */
    private $affection;


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
     * Set valeurNorme
     *
     * @param string $valeurNorme
     *
     * @return ExamenAffection
     */
    public function setValeurNorme($valeurNorme)
    {
        $this->valeurNorme = $valeurNorme;

        return $this;
    }

    /**
     * Get valeurNorme
     *
     * @return string
     */
    public function getValeurNorme()
    {
        return $this->valeurNorme;
    }

    /**
     * Set examen
     *
     * @param \PS\ParametreBundle\Entity\ListeExamen $examen
     *
     * @return ExamenAffection
     */
    public function setExamen(\PS\ParametreBundle\Entity\ListeExamen $examen)
    {
        $this->examen = $examen;

        return $this;
    }

    /**
     * Get examen
     *
     * @return \PS\ParametreBundle\Entity\ListeExamen
     */
    public function getExamen()
    {
        return $this->examen;
    }

    /**
     * Set affection
     *
     * @param \PS\ParametreBundle\Entity\Affection $affection
     *
     * @return ExamenAffection
     */
    public function setAffection(\PS\ParametreBundle\Entity\Affection $affection)
    {
        $this->affection = $affection;

        return $this;
    }

    /**
     * Get affection
     *
     * @return \PS\ParametreBundle\Entity\Affection
     */
    public function getAffection()
    {
        return $this->affection;
    }
}
