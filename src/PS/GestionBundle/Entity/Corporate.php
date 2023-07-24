<?php
namespace PS\GestionBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;
use PS\UtilisateurBundle\Entity\Personne;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\CorporateRepository")
 * @GRID\Source(columns="id, raisonSociale, contact, email", sortable=false, filterable=false)
 */
class Corporate
{
    /**
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @GRID\Column(title="ID", primary=true)
     * @Groups({"corporate"})
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank()
     * @GRID\Column(title="corporate.form.raisonSociale")
     * @Groups({"corporate"})
     */
    private $raisonSociale;

    /**
     * @ORM\Column(type="string",length=255)
     * @GRID\Column(title="corporate.form.contacts")
     * @Groups({"corporate"})
     */
    private $contact;

    /**
     * @ORM\Column(type="string",length=255, nullable=true)
     * @GRID\Column(title="corporate.form.email")
     * @Exclude
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="Commande", mappedBy="corporate")
     * @Groups({"corporate"})
     */
    private $commandes;

    /**
     * @ORM\OneToMany(targetEntity="\PS\UtilisateurBundle\Entity\Personne", mappedBy="corporate")
     * @Groups({"corporate"})
     */
    private $personnes;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\File(mimeTypes={ "image/jpeg", "image/png" }, maxSize="1024")
     */
    private $logo;

    /**
     * @Exclude
     */

    private $pharmacies;

    /**
     * @Exclude
     */
    private $hopitaux;

    /**
     * @Exclude
     */
    private $specialites;

    /**
     * @ORM\OneToMany(targetEntity="PS\GestionBundle\Entity\CorporateHopital", mappedBy="corporate", cascade={"persist"}, orphanRemoval=true)
     * @GRID\Column(visible=false)
     * @Exclude
     */
    private $corporateHopitaux;

    /**
     * @ORM\OneToMany(targetEntity="PS\GestionBundle\Entity\CorporatePharmacie", mappedBy="corporate", cascade={"persist"}, orphanRemoval=true)
     * @GRID\Column(visible=false)
     * @Exclude
     */
    private $corporatePharmacies;


     /**
     * @ORM\ManyToMany(targetEntity="PS\GestionBundle\Entity\CorporateSpecialite", cascade={"persist"}, orphanRemoval=true)
     * @GRID\Column(visible=false)
     * @Exclude
     */
    private $corporateSpecialites;


    /**
     * @ORM\ManyToOne(targetEntity="Corporate", inversedBy="corporateChildren")
     */
    private $corporateParent;

    /**
     * @ORM\OneToMany(targetEntity="Corporate", mappedBy="corporateParent")
     */
    private $corporateChildren;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->commandes           = new \Doctrine\Common\Collections\ArrayCollection();
        $this->hopitaux           = new \Doctrine\Common\Collections\ArrayCollection();
        $this->personnes           = new \Doctrine\Common\Collections\ArrayCollection();
        $this->corporatePharmacies = new \Doctrine\Common\Collections\ArrayCollection();
        $this->corporateHopitaux   = new \Doctrine\Common\Collections\ArrayCollection();
        $this->corporateSpecialites   = new \Doctrine\Common\Collections\ArrayCollection();
        $this->corporateChildren   = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @param $hopitaux
     * @return mixed
     */
    public function setHopitaux($hopitaux)
    {
    
        foreach ($hopitaux as $hopital) {

            $corporateHopital = new CorporateHopital();

            $corporateHopital->setCorporate($this);
            $corporateHopital->setHopital($hopital);

            $this->addCorporateHopital($corporateHopital);

        }

        return $this;
    }


    /**
     * @param $hopitaux
     * @return mixed
     */
    public function setSpecialites($specialites)
    {
    
        foreach ($specialites as $specialite) {

            $corporateSpecialite = new CorporateSpecialite();

            $corporateSpecialite->setCorporate($this);
            $corporateSpecialite->setSpecialite($specialite);

            $this->addCorporateSpecialite($corporateSpecialite);

        }

        return $this;
    }

    public function getSpecialites()
    {
        $specialites = [];
        foreach($this->corporateSpecialites as $corporate)
        {
            $specialites[] = $corporate->getSpecialite();
        }

        return $specialite;
    }



    public function getHopitauxId()
    {
        $ids = [];

        
        foreach($this->corporateHopitaux as $corporate)
        {
            $ids[] = $corporate->getHopital()->getId();
        }

        return $ids;
    }


    public function getPharmaciesId()
    {
        $ids = [];

        foreach($this->corporatePharmacies as $corporate)
        {
            $ids[] = $corporate->getPharmacie()->getId();
        }

        return $ids;
    }




    public function getHopitaux()
    {
        $hopitaux = [];
        foreach($this->corporateHopitaux as $corporate)
        {
            $hopitaux[] = $corporate->getHopital();
        }

        return $hopitaux;
    }


    public function getPharmacies()
    {
        $pharmacies = [];

        foreach($this->corporatePharmacies as $corporate)
        {
            $pharmacies[] = $corporate->getPharmacie();
        }

        return $pharmacies;
    }



    /**
     * @param $hopitaux
     * @return mixed
     */
    public function setPharmacies($pharmacies)
    {
        foreach ($pharmacies as $pharmacie) {
            $corporatePharmacie = new CorporatePharmacie();

            $corporatePharmacie->setCorporate($this);
            $corporatePharmacie->setHopital($hopital);

            $this->addCorporatePharmacy($corporateHopital);

        }

        return $this;

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
     * Set raisonSociale
     *
     * @param string $raisonSociale
     *
     * @return Corporate
     */
    public function setRaisonSociale($raisonSociale)
    {
        $this->raisonSociale = $raisonSociale;

        return $this;
    }

    /**
     * Get raisonSociale
     *
     * @return string
     */
    public function getRaisonSociale()
    {
        return $this->raisonSociale;
    }

    /**
     * Set contact
     *
     * @param string $contact
     *
     * @return Corporate
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Add commande
     *
     * @param \PS\GestionBundle\Entity\Commande $commande
     *
     * @return Corporate
     */
    public function addCommande(\PS\GestionBundle\Entity\Commande $commande)
    {
        $this->commandes[] = $commande;

        $commande->setCorporate($this);

        return $this;
    }

    /**
     * Remove commande
     *
     * @param \PS\GestionBundle\Entity\Commande $commande
     */
    public function removeCommande(\PS\GestionBundle\Entity\Commande $commande)
    {
        $this->commandes->removeElement($commande);
    }

    /**
     * Get commandes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommandes()
    {
        return $this->commandes;
    }

    /**
     * Add personne
     *
     * @param \PS\UtilisateurBundle\Entity\Personne $personne
     *
     * @return Corporate
     */
    public function addPersonne(\PS\UtilisateurBundle\Entity\Personne $personne)
    {
        $this->personnes[] = $personne;
        $this->personne->setCorporate($this);
        return $this;
    }

    /**
     * Remove personne
     *
     * @param \PS\UtilisateurBundle\Entity\Personne $personne
     */
    public function removePersonne(\PS\UtilisateurBundle\Entity\Personne $personne)
    {
        $this->personnes->removeElement($personne);
    }

    /**
     * Get personnes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getpersonnes()
    {
        return $this->personnes;
    }

    /**
     * Set logo
     *
     * @param string $logo
     *
     * @return Corporate
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Corporate
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Add pharmacy
     *
     * @param \PS\GestionBundle\Entity\CorporatePharmacie $pharmacy
     *
     * @return Corporate
     */
    public function addCorporatePharmacy(\PS\GestionBundle\Entity\CorporatePharmacie $pharmacy)
    {
        $this->corporatePharmacies[] = $pharmacy;
        $pharmacy->setPharmacie($this);
        return $this;
    }

    /**
     * Remove pharmacy
     *
     * @param \PS\GestionBundle\Entity\CorporatePharmacie $pharmacy
     */
    public function removeCorporatePharmacy(\PS\GestionBundle\Entity\CorporatePharmacie $pharmacy)
    {
        $this->corporatePharmacies->removeElement($pharmacy);
    }

    /**
     * Get pharmacies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCorporatePharmacies()
    {
        return $this->corporatePharmacies;
    }

    /**
     * Add hopitaux
     *
     * @param \PS\GestionBundle\Entity\CorporateHopital $hopitaux
     *
     * @return Corporate
     */
    public function addCorporateHopital(\PS\GestionBundle\Entity\CorporateHopital $hopital)
    {
        $this->corporateHopitaux[] = $hopital;
        $hopital->setCorporate($this);
        return $this;
    }

    /**
     * Remove hopitaux
     *
     * @param \PS\GestionBundle\Entity\CorporateHopital $hopitaux
     */
    public function removeCorporateHopital(\PS\GestionBundle\Entity\CorporateHopital $hopital)
    {
        $this->corporateHopitaux->removeElement($hopital);
    }


    public function hasHopitaux()
    {
        return count($this->corporateHopitaux);
    }

    /**
     * Get hopitaux
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCorporateHopitaux()
    {
        return $this->corporateHopitaux;
    }

    /**
     * Add corporateHopitaux
     *
     * @param \PS\GestionBundle\Entity\CorporateHopital $corporateHopitaux
     *
     * @return Corporate
     */
    public function addCorporateHopitaux(\PS\GestionBundle\Entity\CorporateHopital $corporateHopitaux)
    {
        $this->corporateHopitaux[] = $corporateHopitaux;

        return $this;
    }

    /**
     * Remove corporateHopitaux
     *
     * @param \PS\GestionBundle\Entity\CorporateHopital $corporateHopitaux
     */
    public function removeCorporateHopitaux(\PS\GestionBundle\Entity\CorporateHopital $corporateHopitaux)
    {
        $this->corporateHopitaux->removeElement($corporateHopitaux);
    }

    /**
     * Add corporateSpecialite
     *
     * @param \PS\GestionBundle\Entity\CorporateSpecialite $corporateSpecialite
     *
     * @return Corporate
     */
    public function addCorporateSpecialite(\PS\GestionBundle\Entity\CorporateSpecialite $corporateSpecialite)
    {
        $this->corporateSpecialites[] = $corporateSpecialite;

        return $this;
    }

    /**
     * Remove corporateSpecialite
     *
     * @param \PS\GestionBundle\Entity\CorporateSpecialite $corporateSpecialite
     */
    public function removeCorporateSpecialite(\PS\GestionBundle\Entity\CorporateSpecialite $corporateSpecialite)
    {
        $this->corporateSpecialites->removeElement($corporateSpecialite);
    }

    /**
     * Get corporateSpecialites
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCorporateSpecialites()
    {
        return $this->corporateSpecialites;
    }

    /**
     * Set corporateParent
     *
     * @param \PS\GestionBundle\Entity\Corporate $corporateParent
     *
     * @return Corporate
     */
    public function setCorporateParent(\PS\GestionBundle\Entity\Corporate $corporateParent = null)
    {
        $this->corporateParent = $corporateParent;

        return $this;
    }

    /**
     * Get corporateParent
     *
     * @return \PS\GestionBundle\Entity\Corporate
     */
    public function getCorporateParent()
    {
        return $this->corporateParent;
    }

    /**
     * Add corporateChild
     *
     * @param \PS\GestionBundle\Entity\Corporate $corporateChild
     *
     * @return Corporate
     */
    public function addCorporateChild(\PS\GestionBundle\Entity\Corporate $corporateChild)
    {
        $this->corporateChildren[] = $corporateChild;

        return $this;
    }

    /**
     * Remove corporateChild
     *
     * @param \PS\GestionBundle\Entity\Corporate $corporateChild
     */
    public function removeCorporateChild(\PS\GestionBundle\Entity\Corporate $corporateChild)
    {
        $this->corporateChildren->removeElement($corporateChild);
    }

    /**
     * Get corporateChildren
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCorporateChildren()
    {
        return $this->corporateChildren;
    }
}
