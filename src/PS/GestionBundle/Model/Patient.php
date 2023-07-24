<?php



namespace PS\GestionBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use PS\ParametreBundle\Entity\ListeAntecedent;
use PS\GestionBundle\Entity\PatientAntecedent;
use PS\GestionBundle\Entity\Patient as EPatient;
use PS\GestionBundle\Entity\InfoDiabete as EInfoDiabete;
use PS\ParametreBundle\Entity\PatientVaccin;

class Patient
{

   
    private $patient;


    /**
     * @Assert\Count(min=1)
     * @Assert\Valid
     */
    private $vaccinations;


    /**
     * Constructor
     */
    public function __construct()
    {
        //$this->modeAccouchement = new \Doctrine\Common\Collections\ArrayCollection();
        $this->vaccinations      = new \Doctrine\Common\Collections\ArrayCollection();
    }



     /**
     * Add vaccination
     *
     * @param \PS\ParametreBundle\Entity\PatientVaccin $vaccination
     * @return Patient
     */
    public function addVaccination(\PS\ParametreBundle\Entity\PatientVaccin $vaccination)
    {
        $this->vaccinations[] = $vaccination;
        $vaccination->setPatient($this->getPatient());
        return $this;
    }

    /**
     * Remove vaccination
     *
     * @param \PS\ParametreBundle\Entity\PatientVaccin $vaccination
     */
    public function removeVaccination(\PS\ParametreBundle\Entity\PatientVaccin $vaccination)
    {
        $this->vaccinations->removeElement($vaccination);
    }

    /**
     * Get vaccination
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVaccinations()
    {
        return $this->vaccinations;
    }


        /**
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\Patient $patient
     * @return PatientVaccin
     */
    public function setPatient(\PS\GestionBundle\Entity\Patient $patient = null)
    {
        $this->patient = $patient;
    
        return $this;
    }

    /**
     * Get patient
     *
     * @return \PS\GestionBundle\Entity\Patient 
     */
    public function getPatient()
    {
        return $this->patient;
    }
}