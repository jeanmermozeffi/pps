<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use APY\DataGridBundle\Grid\Mapping as GRID;
use JMS\Serializer\Annotation\Groups;


/**
 * Medecin
 *
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\InfirmierRepository")
 * @GRID\Source(columns="id, nom_complet, personne.nom, personne.prenom, personne.utilisateur.hopital.nom", sortable=false)
 * @GRID\Column(id="nom_complet", type="join", title="Nom et prénoms", columns={"personne.nom", "personne.prenom"}, operatorsVisible=false)
 */
class Infirmier
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(title="Id", filterable=false, primary=true)
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="\PS\UtilisateurBundle\Entity\Personne", cascade={"persist"})
     * @GRID\Column(field="personne.nom", title="Nom et prénoms", operatorsVisible=false, joinType="inner",visible=false)
     * @GRID\Column(field="personne.prenom", operatorsVisible=false, joinType="inner",visible=false)
     * @ORM\JoinColumn(nullable=false)
     */
    private $personne;


    /**
     * @ORM\OneToMany(targetEntity="\PS\GestionBundle\Entity\ConsultationInfirmier", cascade={"persist"}, mappedBy="infirmier")
    */
    private $consultationInfirmier;
    



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set medecin
     *
     * @param \PS\UtilisateurBundle\Entity\Personne $personne
     *
     * @return Infirmier
     */
    public function setPersonne(\PS\UtilisateurBundle\Entity\Personne $personne)
    {
        $this->personne = $personne;

        return $this;
    }

    /**
     * Get medecin
     *
     * @return \PS\UtilisateurBundle\Entity\Personne
     */
    public function getPersonne()
    {
        return $this->personne;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->consultationInfirmier = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add consultationInfirmier
     *
     * @param \PS\GestionBundle\Entity\ConsultationInfirmier $consultationInfirmier
     *
     * @return Infirmier
     */
    public function addConsultationInfirmier(\PS\GestionBundle\Entity\ConsultationInfirmier $consultationInfirmier)
    {
        $this->consultationInfirmier[] = $consultationInfirmier;

        return $this;
    }

    /**
     * Remove consultationInfirmier
     *
     * @param \PS\GestionBundle\Entity\ConsultationInfirmier $consultationInfirmier
     */
    public function removeConsultationInfirmier(\PS\GestionBundle\Entity\ConsultationInfirmier $consultationInfirmier)
    {
        $this->consultationInfirmier->removeElement($consultationInfirmier);
    }

    /**
     * Get consultationInfirmier
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConsultationInfirmier()
    {
        return $this->consultationInfirmier;
    }
}
