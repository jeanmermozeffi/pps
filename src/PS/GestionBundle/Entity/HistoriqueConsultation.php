<?php
namespace PS\GestionBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\ORM\Mapping as ORM;
use PS\ParametreBundle\Entity\Affection;
use PS\ParametreBundle\Entity\Specialite;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity()
 */
class HistoriqueConsultation
{
   /**
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

     /**
     * @ORM\ManyToOne(targetEntity="\PS\UtilisateurBundle\Entity\Utilisateur")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;


     /**
     * @ORM\ManyToOne(targetEntity="\PS\GestionBundle\Entity\Consultation", inversedBy="actions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $consultation;


    /**
     * @ORM\Column(type="datetime")
     */
    private $dateHistorique;


     /**
     * @ORM\Column(type="string", length=254)
     */
    private $libHistorique;


    const ACTION_CREATE = 'Création de la consultation';
    const ACTION_EDIT = 'Modification de la consultation';
    const ACTION_EDIT_AFTER_CONSTANTE = 'Modification de la consultation après prise de constantes';
    const ACTION_CONSTANTE_CREATE = 'Ajout des constantes';
    const ACTION_CONSTANTE_EDIT = 'Modification des constantes';

    /**
     * Set dateHistorique
     *
     * @param \DateTime $dateHistorique
     *
     * @return HistoriqueConsultation
     */
    public function setDateHistorique($dateHistorique = null)
    {
        if (is_null($dateHistorique)) {
            $dateHistorique = new \DateTime();
        }
        $this->dateHistorique = $dateHistorique;

        return $this;
    }


    public function __construct()
    {
        $this->dateHistorique =new \DateTime();
    }

    /**
     * Get dateHistorique
     *
     * @return \DateTime
     */
    public function getDateHistorique()
    {
        return $this->dateHistorique;
    }

    /**
     * Set libHistorique
     *
     * @param string $libHistorique
     *
     * @return HistoriqueConsultation
     */
    public function setLibHistorique($libHistorique)
    {
        $this->libHistorique = $libHistorique;

        return $this;
    }

    /**
     * Get libHistorique
     *
     * @return string
     */
    public function getLibHistorique()
    {
        return $this->libHistorique;
    }

    /**
     * Set utilisateur
     *
     * @param \PS\UtilisateurBundle\Entity\Utilisateur $utilisateur
     *
     * @return HistoriqueConsultation
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
     * Set consultation
     *
     * @param \PS\GestionBundle\Entity\Consultation $consultation
     *
     * @return HistoriqueConsultation
     */
    public function setConsultation(\PS\GestionBundle\Entity\Consultation $consultation)
    {
        $this->consultation = $consultation;

        return $this;
    }

    /**
     * Get consultation
     *
     * @return \PS\GestionBundle\Entity\Consultation
     */
    public function getConsultation()
    {
        return $this->consultation;
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
}
