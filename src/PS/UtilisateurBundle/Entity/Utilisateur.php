<?php
namespace PS\UtilisateurBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use PS\GestionBundle\Entity\Pharmacie;
use PS\ParametreBundle\Entity\Hopital;
use PS\ParametreBundle\Entity\Assurance;
use PS\UtilisateurBundle\Validator\Constraints as PSAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Encoder\EncoderAwareInterface;

// use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * @ExclusionPolicy("all")
 * Utilisateur
 *
 * @ORM\Table(name="utilisateur")
 * @ORM\Entity(repositoryClass="PS\UtilisateurBundle\Repository\UtilisateurRepository")
 * @GRID\Source(columns="id, username, email, roles")
 * @UniqueEntity(fields="usernameCanonical", errorPath="username", message="fos_user.username.already_used")
 * @ORM\AttributeOverrides({
 *      @ORM\AttributeOverride(name="emailCanonical", column=@ORM\Column(type="string", name="email_canonical", length=255, unique=false)),
 *      @ORM\AttributeOverride(name="salt", column=@ORM\Column(type="string", nullable=true))
 *
 * })
 */
class Utilisateur extends BaseUser implements EquatableInterface, EncoderAwareInterface
{

    /**
     * @Expose
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(title="ID", primary=true, filterable=false, sortable=false)
     * @Groups({"user"})
     */
    protected $id;

    /**
     * 
     * @ORM\OneToOne(targetEntity="\PS\UtilisateurBundle\Entity\Personne", cascade={"persist"}, inversedBy="utilisateur", orphanRemoval=true)
     */
    private $personne;

    /**
     * @var integer $smScode Current authentication code
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    private $smsCode;

    /**
     * @var integer $smScode Current authentication code
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $smsCodeExpiredAt;

    /**
     * @var array
     * 
     * @GRID\Column(
    title="Rôles"
    , type="array"
    , operatorsVisible=false
    , sortable=false
    , filter="select"
    , selectFrom="values"
    , values={"ROLE_SUPER_ADMIN":"Super Administrateur", "ROLE_ADMIN": "ADMINISTRATION DSI", "ROLE_CUSTOMER":"Patient", "ROLE_MEDECIN": "Medecin", "ROLE_INFIRMIER": "Infirmier", "ROLE_PHARMACIE": "Pharmacien", "ROLE_RECEPTION": "Réceptionniste", "ROLE_ADMIN_LOCAL": "Admin Local", "ROLE_ADMIN_CORPORATE": "Admin Du groupe médical", "ROLE_ADMIN_SUP": "AGENT ENROLEUR"}
    )
     * @Groups({"user"})
     * @Expose
     */
    protected $roles;

    /**
     * @Expose
     * @var string
     * @GRID\Column(title="utilisateur.form.username", operatorsVisible=false)
     * @Assert\NotBlank()
     * PSAssert\Alphanumeric(groups={"Profile", "Registration"})
     * @Groups({"user"})
     */
    protected $username;

    /**
     * @Expose
     * @var string
     * @GRID\Column(title="utilisateur.form.email", operatorsVisible=false)
     * @Groups({"user"})
     */
    protected $email;

    /**
     * 
     * @ORM\OneToMany(targetEntity="Utilisateur", mappedBy="parent")
     */
    private $associes;

    /**
     * @ORM\ManyToOne(targetEntity="Utilisateur", inversedBy="associes")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    private $parent;

    /**
     * 
     * @ORM\OneToMany(targetEntity="PS\UtilisateurBundle\Entity\UtilisateurHopital",mappedBy="utilisateur",cascade={"all"}, orphanRemoval=true))
     */
    private $utilisateurHopital;

    /**
     * @ORM\OneToMany(targetEntity="PS\UtilisateurBundle\Entity\UtilisateurPharmacie",mappedBy="utilisateur",cascade={"all"}, orphanRemoval=true))
     */
    private $utilisateurPharmacie;

    /**
     * 
     * @ORM\OneToMany(targetEntity="PS\UtilisateurBundle\Entity\UtilisateurAssurance",mappedBy="utilisateur",cascade={"all"}, orphanRemoval=true))
     */
    private $utilisateurAssurance;

    /**
     * 
     */
    private $hopital;

    /**
     * 
     */
    private $pharmacie;

    /**
     * 
     */
    private $assurance;


    /**
     *@ORM\Column(type="string", length=5, options={"default":"old"})
     */
    private $encoder = 'new';

    /**
     * {@constructor}
     */
    public function __construct()
    {
        parent::__construct();
        $this->associes             = new \Doctrine\Common\Collections\ArrayCollection();
        $this->utilisateurHopital   = new \Doctrine\Common\Collections\ArrayCollection();
        $this->utilisateurPharmacie = new \Doctrine\Common\Collections\ArrayCollection();
        $this->utilisateurAssurance = new \Doctrine\Common\Collections\ArrayCollection();
    }


     /**
     * @return mixed
     */
    public function getEncoderName()
    {
        return $this->encoder;
    }

    /**
     * Set encoder.
     *
     * @param string $encoder
     *
     * @return Utilisateur
     */
    public function setEncoder($encoder)
    {
        if ($encoder == 'new') {
            $this->salt = null;
        }
        
        $this->encoder = $encoder;

        return $this;
    }

    /**
     * Get encoder.
     *
     * @return string
     */
    public function getEncoder()
    {
        return $this->encoder;
    }

    /**
     * Set personne
     *
     * @param \PS\UtilisateurBundle\Entity\Personne $personne
     * @return Utilisateur
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
     * Add assocy
     *
     * @param \PS\UtilisateurBundle\Entity\Utilisateur $assocy
     *
     * @return Utilisateur
     */
    public function addAssocy(\PS\UtilisateurBundle\Entity\Utilisateur $assocy)
    {
        $this->associes[] = $assocy;

        $this->assocy->setParent($this);

        return $this;
    }

    /**
     * Remove assocy
     *
     * @param \PS\UtilisateurBundle\Entity\Utilisateur $assocy
     */
    public function removeAssocy(\PS\UtilisateurBundle\Entity\Utilisateur $assocy)
    {
        $this->associes->removeElement($assocy);
    }

    /**
     * Get associes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAssocies()
    {
        return $this->associes;
    }

    /**
     * @param $roles
     */
    public function hasNotRoles($roles)
    {
        return !array_intersect($this->getRoles(), $roles);
    }

    /**
     * Set parent
     *
     * @param \PS\UtilisateurBundle\Entity\Utilisateur $parent
     *
     * @return Utilisateur
     */
    public function setParent(\PS\UtilisateurBundle\Entity\Utilisateur $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \PS\UtilisateurBundle\Entity\Utilisateur
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return mixed
     */
    public function getSmsCode()
    {
        return $this->smsCode;
    }

    /**
     * @param $smsCode
     * @return mixed
     */
    public function setSmsCode($smsCode)
    {
        $this->smsCode = $smsCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSmsCodeExpiredAt()
    {
        return $this->smsCodeExpiredAt;
    }

    /**
     * @param $smsCodeExpiredAt
     * @return mixed
     */
    public function setSmsCodeExpireAt($smsCodeExpiredAt)
    {
        $this->smsCodeExpiredAt = $smsCodeExpiredAt;
        return $this;
    }

    /**
     * @param UserInterface $user
     * @return mixed
     */
    public function isEqualTo(UserInterface $user)
    {
        if ($user instanceof User) {
            // Check that the roles are the same, in any order
            $isEqual = count($this->getRoles()) == count($user->getRoles());
            if ($isEqual) {
                foreach ($this->getRoles() as $role) {
                    $isEqual = $isEqual && in_array($role, $user->getRoles());
                }
            }
            return $isEqual;
        }

        return false;
    }

    /**
     * Set smsCodeExpiredAt
     *
     * @param \DateTime $smsCodeExpiredAt
     *
     * @return Utilisateur
     */
    public function setSmsCodeExpiredAt($smsCodeExpiredAt)
    {
        $this->smsCodeExpiredAt = $smsCodeExpiredAt;

        return $this;
    }

    public function getPharmacie()
    {
        $pharmacie = [];
        foreach ($this->utilisateurPharmacie as $utilisateur) {
            $pharmacie[] = $utilisateur->getPharmacie();
        }

        return current($pharmacie);
    }

    // Important
    /**
     * @param $pharmacie
     * @return mixed
     */
    public function setPharmacie($pharmacie)
    {
        if (!$pharmacie) {
            return $this;
        }

        $utilisateurPharmacie = new UtilisateurPharmacie();

        $utilisateurPharmacie->setUtilisateur($this);
        $utilisateurPharmacie->setPharmacie($pharmacie);

        $this->addUtilisateurPharmacie($utilisateurPharmacie);

    }


    public function getAssurance()
    {

        $assurance = [];
        
        foreach ($this->getUtilisateurAssurance() as $utilisateur) {
            $assurance[] = $utilisateur->getAssurance();
        }


        //dump(current($assurance));exit;

        return current($assurance);
    }

    // Important
    /**
     * @param $pharmacie
     * @return mixed
     */
    public function setAssurance($assurance)
    {
        if (!$assurance) {
            return $this;
        }

        $utilisateurAssurance = new UtilisateurAssurance();

        $utilisateurAssurance->setUtilisateur($this);
        $utilisateurAssurance->setAssurance($assurance);


        $this->addUtilisateurAssurance($utilisateurAssurance);

    }

    public function getHopital()
    {

        $hopital = [];
        foreach ($this->utilisateurHopital as $utilisateur) {
            $hopital[] = $utilisateur->getHopital();
        }

        return current($hopital);
    }

    /**
     * @param $roles
     */
    public function hasRoles($roles)
    {
        return array_intersect($this->getRoles(), $roles);
    }

    // Important
    /**
     * @param $hopital
     * @return mixed
     */
    public function setHopital($hopital)
    {
        if (!$hopital) {
            return $this;
        }

        $utilisateurHopital = new UtilisateurHopital();

        $utilisateurHopital->setUtilisateur($this);
        $utilisateurHopital->setHopital($hopital);

        $this->addUtilisateurHopital($utilisateurHopital);

    }

    /**
     * Add UtilisateurHopital
     *
     * @param \PS\UtilisateurBundle\Entity\UtilisateurHopital $utilisateurHopital
     *
     * @return Patient
     */
    public function addUtilisateurHopital(\PS\UtilisateurBundle\Entity\UtilisateurHopital $utilisateurHopital)
    {
        $this->utilisateurHopital[] = $utilisateurHopital;

        return $this;
    }

    /**
     * Remove UtilisateurHopital
     *
     * @param \PS\UtilisateurBundle\Entity\UtilisateurHopital $utilisateurHopital
     */
    public function removeUtilisateurHopital(\PS\UtilisateurBundle\Entity\UtilisateurHopital $utilisateurHopital)
    {
        $this->utilisateurHopital->removeElement($utilisateurHopital);
    }

    /**
     * Get UtilisateurHopitals
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUtilisateurHopital()
    {
        return $this->utilisateurHopital;
    }

    /**
     * Add UtilisateurHopital
     *
     * @param \PS\UtilisateurBundle\Entity\UtilisateurHopital $utilisateurHopital
     *
     * @return Patient
     */
    public function addUtilisateurPharmacie(\PS\UtilisateurBundle\Entity\UtilisateurPharmacie $utilisateurPharmacie)
    {
        $this->utilisateurPharmacie[] = $utilisateurPharmacie;

        return $this;
    }

    /**
     * Remove UtilisateurHopital
     *
     * @param \PS\UtilisateurBundle\Entity\UtilisateurHopital $utilisateurHopital
     */
    public function removeUtilisateurPharmacie(\PS\UtilisateurBundle\Entity\UtilisateurPharmacie $utilisateurPharmacie)
    {
        $this->utilisateurPharmacie->removeElement($utilisateurPharmacie);
    }

    /**
     * Get UtilisateurHopitals
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUtilisateurPharmacie()
    {
        return $this->utilisateurPharmacie;
    }


    /**
     * Add utilisateurAssurance
     *
     * @param \PS\UtilisateurBundle\Entity\UtilisateurAssurance $utilisateurAssurance
     *
     * @return Utilisateur
     */
    public function addUtilisateurAssurance(\PS\UtilisateurBundle\Entity\UtilisateurAssurance $utilisateurAssurance)
    {
        
        $this->utilisateurAssurance[] = $utilisateurAssurance;
        
        return $this;
    }

    /**
     * Remove utilisateurAssurance
     *
     * @param \PS\UtilisateurBundle\Entity\UtilisateurAssurance $utilisateurAssurance
     */
    public function removeUtilisateurAssurance(\PS\UtilisateurBundle\Entity\UtilisateurAssurance $utilisateurAssurance)
    {
        $this->utilisateurAssurance->removeElement($utilisateurAssurance);
    }

    /**
     * Get utilisateurAssurance
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUtilisateurAssurance()
    {
        return $this->utilisateurAssurance;
    }


        /**
     * @return mixed
     */
    public function getPatient()
    {
        return $this->getPersonne() ? $this->getPersonne()->getPatient() : false;
    }


     /**
     * @return mixed
     */
    public function getSite()
    {
        return $this->getPersonne() ? $this->getPersonne()->getSite() : false;
    }



    /**
     * @return mixed
     */
    public function getMedecin()
    {
        if ($this->getPersonne()) {
            return $this->getPersonne()->getMedecin();
        }
    }


    public function getCorporate()
    {
        if ($this->getPersonne()) {
            return $this->getPersonne()->getCorporate();
        }
    }

}
