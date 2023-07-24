<?php



namespace PS\GestionBundle\Entity;



use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

use APY\DataGridBundle\Grid\Mapping as GRID;



/**

 * FicheDepistageCancer

 *

 * @ORM\Table(name="fiche_depistage_cancer")

 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\FicheDepistageCancerRepository")

 * @GRID\Source(columns="id,dateDepistage,patient_nom_complet,patient.id,patient.personne.nom,patient.personne.prenom,taille,poids")

 * @GRID\Column(id="patient_nom_complet", type="join", title="Patiente", columns={"patient.personne.nom", "patient.personne.prenom"}, operatorsVisible=false, operators={"rlike"}, defaultOperator="rlike")

 */

class FicheDepistageCancer

{

    /**

     * @var int

     *

     * @ORM\Column(name="id", type="integer")

     * @ORM\Id

     * @ORM\GeneratedValue(strategy="AUTO")

     * @GRID\Column(visible=false)

     */

    private $id;



    /**

     * @var \DateTime

     *

     * @ORM\Column(name="dateDepistage", type="datetime")

     * @GRID\Column(title="Date de dépistage")

     */

    private $dateDepistage;



    /**
     * @var int
     * @ORM\Column(name="taille", type="integer")
     * @GRID\Column(title="Taille")
     */
    private $taille = 0;



    /**
     * @var float
     * @ORM\Column(name="poids", type="float")
     * @GRID\Column(title="Poids")
     */
    private $poids = 0;



    /**
     * @var bool
     *
     * @ORM\Column(name="autoExamen", type="boolean")
     */
    private $autoExamen;



    /**
     * @var bool
     *
     * @ORM\Column(name="controleExamenMedecin", type="boolean")
     */
    private $controleExamenMedecin;



    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDerniereMamno", type="datetime", nullable=true)
     */
    private $dateDerniereMamno;



    /**
     * @ORM\OneToOne(targetEntity="\PS\GestionBundle\Entity\InfoHygieneVie", inversedBy="ficheDepistageCancer", cascade={"persist", "remove"})
     * @Assert\Valid
     */
    private $infoHygieneVie;



    /**
     * @ORM\OneToOne(targetEntity="\PS\GestionBundle\Entity\ExamenPhysiqueDepCancer", inversedBy="ficheDepistageCancer", cascade={"persist", "remove"})
     * @Assert\Valid
     */
    private $examenPhysiqueDepCancer;



    /**

     * @ORM\OneToOne(targetEntity="\PS\GestionBundle\Entity\FacteurRisqueCancer", inversedBy="ficheDepistageCancer", cascade={"persist", "remove"})

     * @Assert\Valid

     */

    private $facteurRisquecancer;



    /**

     * @var Patient

     * @ORM\ManyToOne(targetEntity="Patient", inversedBy="ficheDepistageCancers")

     * @ORM\JoinColumn(nullable=false)

     * @GRID\Column(field="patient.personne.nom", visible=false)

     * @GRID\Column(field="patient.personne.prenom", visible=false)

     * @GRID\Column(field="patient.id", visible=false)

     */

    private $patient;





    /**

     * @var Medecin

     * @ORM\ManyToOne(targetEntity="Medecin")

     * @ORM\JoinColumn(nullable=true)

     */

    private $medecin;





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

     * Set dateDepistage

     *

     * @param \DateTime $dateDepistage

     * @return FicheDepistageCancer

     */

    public function setDateDepistage($dateDepistage)

    {

        $this->dateDepistage = $dateDepistage;

    

        return $this;

    }



    /**

     * Get dateDepistage

     *

     * @return \DateTime 

     */

    public function getDateDepistage()

    {

        return $this->dateDepistage;

    }



    /**

     * Set taille

     *

     * @param integer $taille

     * @return FicheDepistageCancer

     */

    public function setTaille($taille)

    {

        $this->taille = $taille;

    

        return $this;

    }



    /**

     * Get taille

     *

     * @return integer 

     */

    public function getTaille()

    {

        return $this->taille;

    }



    /**

     * Set poids

     *

     * @param float $poids

     * @return FicheDepistageCancer

     */

    public function setPoids($poids)

    {

        $this->poids = $poids;

    

        return $this;

    }



    /**

     * Get poids

     *

     * @return float 

     */

    public function getPoids()

    {

        return $this->poids;

    }



    /**

     * Set autoExamen

     *

     * @param boolean $autoExamen

     * @return FicheDepistageCancer

     */

    public function setAutoExamen($autoExamen)

    {

        $this->autoExamen = $autoExamen;

    

        return $this;

    }



    /**

     * Get autoExamen

     *

     * @return boolean 

     */

    public function getAutoExamen()

    {

        return $this->autoExamen;

    }



    /**

     * Set controleExamenMedecin

     *

     * @param boolean $controleExamenMedecin

     * @return FicheDepistageCancer

     */

    public function setControleExamenMedecin($controleExamenMedecin)

    {

        $this->controleExamenMedecin = $controleExamenMedecin;

    

        return $this;

    }



    /**

     * Get controleExamenMedecin

     *

     * @return boolean 

     */

    public function getControleExamenMedecin()

    {

        return $this->controleExamenMedecin;

    }



    /**

     * Set dateDerniereMamno

     *

     * @param \DateTime $dateDerniereMamno

     * @return FicheDepistageCancer

     */

    public function setDateDerniereMamno($dateDerniereMamno)

    {

        $this->dateDerniereMamno = $dateDerniereMamno;

    

        return $this;

    }



    /**

     * Get dateDerniereMamno

     *

     * @return \DateTime 

     */

    public function getDateDerniereMamno()

    {

        return $this->dateDerniereMamno;

    }



    /**

     * Set infoHygieneVie

     *

     * @param \PS\GestionBundle\Entity\InfoHygieneVie $infoHygieneVie

     * @return FicheDepistageCancer

     */

    public function setInfoHygieneVie(\PS\GestionBundle\Entity\InfoHygieneVie $infoHygieneVie = null)

    {

        $this->infoHygieneVie = $infoHygieneVie;

    

        return $this;

    }



    /**

     * Get infoHygieneVie

     *

     * @return \PS\GestionBundle\Entity\InfoHygieneVie 

     */

    public function getInfoHygieneVie()

    {

        return $this->infoHygieneVie;

    }



    /**

     * Set examenPhysiqueDepCancer

     *

     * @param \PS\GestionBundle\Entity\ExamenPhysiqueDepCancer $examenPhysiqueDepCancer

     * @return FicheDepistageCancer

     */

    public function setExamenPhysiqueDepCancer(\PS\GestionBundle\Entity\ExamenPhysiqueDepCancer $examenPhysiqueDepCancer = null)

    {

        $this->examenPhysiqueDepCancer = $examenPhysiqueDepCancer;

    

        return $this;

    }



    /**

     * Get examenPhysiqueDepCancer

     *

     * @return \PS\GestionBundle\Entity\ExamenPhysiqueDepCancer 

     */

    public function getExamenPhysiqueDepCancer()

    {

        return $this->examenPhysiqueDepCancer;

    }



    /**

     * Set facteurRisquecancer

     *

     * @param \PS\GestionBundle\Entity\FacteurRisqueCancer $facteurRisquecancer

     * @return FicheDepistageCancer

     */

    public function setFacteurRisqueCancer(\PS\GestionBundle\Entity\FacteurRisqueCancer $facteurRisquecancer = null)

    {

        $this->facteurRisquecancer = $facteurRisquecancer;

    

        return $this;

    }



    /**

     * Get facteurRisquecancer

     *

     * @return \PS\GestionBundle\Entity\FacteurRisqueCancer 

     */

    public function getFacteurRisqueCancer()

    {

        return $this->facteurRisquecancer;

    }



    /**

     * Get the value of patient

     *

     * @return  Patient

     */ 

    public function getPatient()

    {

        return $this->patient;

    }



    /**

     * Set the value of patient

     *

     * @param  Patient  $patient

     *

     * @return  self

     */ 

    public function setPatient(Patient $patient)

    {

        $this->patient = $patient;



        return $this;

    }



    /**

     * Get the value of medecin

     *

     * @return  Medecin

     */ 

    public function getMedecin()

    {

        return $this->medecin;

    }



    /**

     * Set the value of medecin

     *

     * @param  Medecin  $medecin

     *

     * @return  self

     */ 

    public function setMedecin(?Medecin $medecin)

    {

        $this->medecin = $medecin;



        return $this;

    }

}

