<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * FicheAnalyse
 *
 * @ORM\Table(name="fiche_analyse")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\FicheAnalyseRepository")
 */
class FicheAnalyse
{
    const LIBELLES = ['NFS', 'Créatine', 'Transaminases', 'TSH', 'CRP', 'Na', 'K', 'Ca'];
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
     * @ORM\Column(name="libelle", type="string", length=255)
     * @Assert\NotBlank(message="Veuillez renseigner l'examen")
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="valeur", type="string", length=255, nullable=true)
     */
    private $valeur;




     /**
     * @ORM\ManyToOne(targetEntity="Fiche", inversedBy="analyses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fiche;


     /**
     * Constructor
     */
    public function __construct()
    {
       
        $this->setValeur('');
    }



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
     * @return FicheAnalyse
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
     * Set valeur
     *
     * @param string $valeur
     *
     * @return FicheAnalyse
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;

        return $this;
    }

    /**
     * Get valeur
     *
     * @return string
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * Set fiche
     *
     * @param \PS\GestionBundle\Entity\Fiche $fiche
     *
     * @return FicheAnalyse
     */
    public function setFiche(\PS\GestionBundle\Entity\Fiche $fiche)
    {
        $this->fiche = $fiche;

        return $this;
    }

    /**
     * Get fiche
     *
     * @return \PS\GestionBundle\Entity\Fiche
     */
    public function getFiche()
    {
        return $this->fiche;
    }
}
