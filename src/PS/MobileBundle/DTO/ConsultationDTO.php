<?php



namespace PS\MobileBundle\DTO;



use PS\GestionBundle\Entity\Consultation;

use PS\GestionBundle\Service\Util;

use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\Common\Collections\Collection;



class ConsultationDTO
{
    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez renseigner l'ID du patient")
     */
    private $identifiant;


    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez renseigner le code PIN du patient")
     */
    private $pin;


    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez renseigner le motif de la consultation")
     */
    private $motif;


    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez renseigner les symptômes")
     */
    private $symptome;


    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez renseigner le diagnostic")
     */
    private $diagnostic;



    /**
     * @var int
     * @Assert\NotBlank(message="Veuillez renseigner la spécialité")
     */
    private $specialite;




    /**
     * @var Collection
     * @Assert\Valid
     */
    private $ordonnances;





    /**
     * @var Collection
     * @Assert\Valid
     */
    private $examens;



    public function __construct()
    {
        $this->ordonnances = new ArrayCollection();
        $this->examens = new ArrayCollection();
    }


    /**
     * Get the value of motif

     *

     * @return  string

     */ 

    public function getMotif()
    {

        return $this->motif;

    }



    /**

     * Set the value of motif

     *

     * @param  string  $motif

     *

     * @return  self

     */ 

    public function setMotif(string $motif)

    {

        $this->motif = $motif;



        return $this;

    }



    /**

     * Get the value of symptome

     *

     * @return  string

     */ 

    public function getSymptome()

    {

        return $this->symptome;

    }



    /**

     * Set the value of symptome

     *

     * @param  string  $symptome

     *

     * @return  self

     */ 

    public function setSymptome(string $symptome)

    {

        $this->symptome = $symptome;



        return $this;

    }



    /**

     * Get the value of diagnostic

     *

     * @return  string

     */ 

    public function getDiagnostic()

    {

        return $this->diagnostic;

    }



    /**

     * Set the value of diagnostic

     *

     * @param  string  $diagnostic

     *

     * @return  self

     */ 

    public function setDiagnostic(string $diagnostic)

    {

        $this->diagnostic = $diagnostic;



        return $this;

    }







    public function toEntity(Consultation $consultation)

    {

        $util = new Util();

        $consultation->setDateConsultation(new \DateTime());

        $consultation->setRefConsultation($util->random(8));

    }



    /**

     * Get the value of specialite

     *

     * @return  int

     */ 

    public function getSpecialite()

    {

        return $this->specialite;

    }



    /**

     * Set the value of specialite

     *

     * @param  int  $specialite

     *

     * @return  self

     */ 

    public function setSpecialite(int $specialite)

    {

        $this->specialite = $specialite;



        return $this;

    }





    /**

     * @return Collection|ConsultationOrdonnanceDTO[]

     */

    public function getOrdonnances(): ?Collection
    {
        return $this->ordonnances;
    }



    public function addOrdonnance(ConsultationOrdonnanceDTO $ordonnance): self
    {
        $this->ordonnances[] = $ordonnance;
        return $this;
    }



    public function removeOrdonnance(ConsultationOrdonnanceDTO $ordonnance): self
    {
        $this->ordonnances->removeElement($ordonnance);
        return $this;
    }





    /**
     * @return Collection|ConsultationExamenDTO[]
     */
    public function getExamens(): ?Collection
    {
        return $this->examens;
    }



    public function addExamen(ConsultationExamenDTO $examen): self
    {
        if (!$this->examens->contains($examen)) {
            $this->examens[] = $examen;
        }
        return $this;

    }



    public function removeExamen(ConsultationExamenDTO $examen): self
    {
        $this->examens->removeElement($examen);
        return $this;

    }


      /**
     * Get the value of identifiant
     *
     * @return  string
     */ 
    public function getIdentifiant()
    {
        return $this->identifiant;
    }

    /**
     * Set the value of identifiant
     *
     * @param  string  $identifiant
     *
     * @return  self
     */ 
    public function setIdentifiant(string $identifiant)
    {
        $this->identifiant = $identifiant;

        return $this;
    }

    /**
     * Get the value of pin
     *
     * @return  string
     */ 
    public function getPin()
    {
        return $this->pin;
    }

    /**
     * Set the value of pin
     *
     * @param  string  $pin
     *
     * @return  self
     */ 
    public function setPin(string $pin)
    {
        $this->pin = $pin;

        return $this;
    }

}