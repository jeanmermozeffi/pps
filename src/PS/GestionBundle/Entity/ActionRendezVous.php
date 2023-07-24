<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ActionRendezVous
 *
 * @ORM\Table(name="action_rendez_vous")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\ActionRendezVousRepository")
 */
class ActionRendezVous
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
     * @ORM\ManyToOne(targetEntity="\PS\UtilisateurBundle\Entity\Utilisateur")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;


     /**
     * @ORM\ManyToOne(targetEntity="\PS\GestionBundle\Entity\RendezVous")
     * @ORM\JoinColumn(nullable=false)
     */
    private $rendezVous;


    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAction;


    /**
     * @ORM\Column(type="string", length=20)
     */
    private $typeAction;


    const ACTION_EDIT = 'ACTION_EDIT';
    const ACTION_CREATE = 'ACTION_CREATE';
    const ACTION_CANCEL = 'ACTION_CANCEL';


    public function __construct()
    {
        $this->dateAction = new \DateTime();
    }


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
     * Set dateAction
     *
     * @param \DateTime $dateAction
     *
     * @return ActionRendezVous
     */
    public function setDateAction($dateAction)
    {
        $this->dateAction = $dateAction;

        return $this;
    }

    /**
     * Get dateAction
     *
     * @return \DateTime
     */
    public function getDateAction()
    {
        return $this->dateAction;
    }

    /**
     * Set utilisateur
     *
     * @param \PS\UtilisateurBundle\Entity\Utilisateur $utilisateur
     *
     * @return ActionRendezVous
     */
    public function setUtilisateur(\PS\UtilisateurBundle\Entity\Utilisateur $utilisateur)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return \PS\UtilisateurBundle\Entity\Utilisateur
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * Set rendezVous
     *
     * @param \PS\GestionBundle\Entity\RendezVous $rendezVous
     *
     * @return ActionRendezVous
     */
    public function setRendezVous(\PS\GestionBundle\Entity\RendezVous $rendezVous)
    {
        $this->rendezVous = $rendezVous;

        return $this;
    }

    /**
     * Get rendezVous
     *
     * @return \PS\GestionBundle\Entity\RendezVous
     */
    public function getRendezVous()
    {
        return $this->rendezVous;
    }

    /**
     * Set typeAction
     *
     * @param string $typeAction
     *
     * @return ActionRendezVous
     */
    public function setTypeAction($typeAction)
    {
        $this->typeAction = $typeAction;

        return $this;
    }

    /**
     * Get typeAction
     *
     * @return string
     */
    public function getTypeAction()
    {
        return $this->typeAction;
    }
}
