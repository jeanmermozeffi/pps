<?php

namespace PS\GestionBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use PS\ParametreBundle\Entity\ListeAntecedent;
use PS\GestionBundle\Entity\PatientAntecedent;
use PS\GestionBundle\Entity\Patient;
use PS\GestionBundle\Entity\InfoDiabete as EInfoDiabete;

class InfoDiabete
{

    /**
     * @Assert\NotBlank(message="Veuillez entrer la date de la découverte")
     * @Assert\Type("\DateTime")
     */
    protected $dateDecouverte;

    /**
     * @Assert\NotBlank(message="Veuillez entrer la date de début des symptomes")
     * @Assert\Type("\DateTime")
     */
    protected $dateSymptome;

    /**
     * @Assert\NotBlank(message="Veuillez entrer l'histoire")
     */
    protected $histoire;

    /**
     * @var mixed
     */
    protected $antecedentPersonnels;

    /**
     * @var mixed
     */
    protected $antecedentFamiliaux;

    /**
     * @var mixed
     */
    protected $antecedents;



    /**
     * @var mixed
     */
    protected $patient;

    public function __construct()
    {
        $this->antecedentPersonnels = new \Doctrine\Common\Collections\ArrayCollection();
        $this->antecedentFamiliaux  = new \Doctrine\Common\Collections\ArrayCollection();
        $this->antecedents          = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @param ListeAntecedent $antecedent
     * @return mixed
     */
    public function addAntecedentFamiliaux(ListeAntecedent $antecedent)
    {

        $ligne = new PatientAntecedent();
        $ligne->setType('familiaux');
        $ligne->setAntecedent($antecedent);
        $ligne->setGroupe($antecedent->getGroupe());
        $ligne->setPatient($this->getPatient());
        $this->antecedents[] = $ligne;
        //$antecedent->setType('familiaux');

        return $this;
    }



     /**
     * @param ListeAntecedent $antecedent
     */
    public function removeAntecedentFamiliaux(ListeAntecedent $antecedent)
    {
        $this->antecedentFamiliaux->removeElement($antecedent);
    }

    /**
     * @return mixed
     */
    public function getAntecedentFamiliaux()
    {

        return $this->antecedents->filter(function ($antecedent) {
            return $antecedent->getType() == 'familiaux';
        })->map(function ($antecedent) {
            return $antecedent->getAntecedent();
        });
    }



    /**
     * @return mixed
     */
    public function setAntecedentFamiliaux($antecedents)
    {

        foreach ($antecedents as $antecedent) {
            $this->addAntecedentFamiliaux($antecedent);
        }

        return $this;
    }



    /**
     * @return mixed
     */
    public function setAntecedentPersonnels($antecedents)
    {
        
        foreach ($antecedents as $antecedent) {
            $this->addAntecedentPersonnel($antecedent);
        }

        return $this;
    }


    /**
     * @param ListeAntecedent $antecedent
     * @return mixed
     */
    public function addAntecedent(PatientAntecedent $antecedent)
    {
        $this->antecedents[] = $antecedent;
        if ($antecedent->getType() == 'familiaux') {
            $this->antecedentFamiliaux[] = $antecedent->getAntecedent();
        } else {
            $this->antecedentPersonnels[] = $antecedent->getAntecedent();
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAntecedents()
    {
        return $this->antecedents;
    }

    /**
     * @return mixed
     */
    public function getAllAntecedents()
    {
        $data = $this->antecedentFamiliaux;

        foreach ($this->antecedentPersonnels as $antecedent) {
            $data[] = $antecedent;
        }

        return $data;
    }

    /**
     * @param ListeAntecedent $antecedent
     */
    public function removeAntecedent(ListeAntecedent $antecedent)
    {
        $this->antecedents->removeElement($antecedent);
    }

    /**
     * @param ListeAntecedent $antecedent
     * @return mixed
     */
    public function addAntecedentPersonnel(ListeAntecedent $antecedent)
    {
        $ligne = new PatientAntecedent();
        $ligne->setType('personnels');
        $ligne->setAntecedent($antecedent);
        $ligne->setGroupe($antecedent->getGroupe());
        $ligne->setPatient($this->getPatient());
        $this->antecedents[] = $ligne;
        //$antecedent->setType('familiaux');

        return $this;
    }


     /**
     * @param ListeAntecedent $antecedent
     */
    public function removeAntecedentPersonnel(ListeAntecedent $antecedent)
    {
        $this->antecedentPersonnels->removeElement($antecedent);
    }

    /**
     * @return mixed
     */
    public function getAntecedentPersonnels()
    {
        return $this->antecedents->filter(function ($antecedent) {
            return $antecedent->getType() == 'personnels';
        })->map(function ($antecedent) {
            return $antecedent->getAntecedent();
        });
    }

    /**
     * Set dateDecouverte
     *
     * @param \DateTime $dateDecouverte
     *
     * @return InfoDiabete
     */
    public function setDateDecouverte(\DateTime $dateDecouverte)
    {
        $this->dateDecouverte = $dateDecouverte;

        return $this;
    }

    /**
     * Get dateDecouverte
     *
     * @return \DateTime
     */
    public function getDateDecouverte()
    {
        return $this->dateDecouverte;
    }

    /**
     * Set histoire
     *
     * @param string $histoire
     *
     * @return InfoDiabete
     */
    public function setHistoire($histoire)
    {
        $this->histoire = $histoire;

        return $this;
    }

    /**
     * Get histoire
     *
     * @return string
     */
    public function getHistoire()
    {
        return $this->histoire;
    }

    /**
     * Set dateSymptome
     *
     * @param \DateTime $dateSymptome
     *
     * @return InfoDiabete
     */
    public function setDateSymptome(\DateTime $dateSymptome)
    {
        $this->dateSymptome = $dateSymptome;

        return $this;
    }

    /**
     * Get dateSymptome
     *
     * @return \DateTime
     */
    public function getDateSymptome()
    {
        return $this->dateSymptome;
    }


    /**
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\Patient $patient
     * @return Consultation
     */
    public function setPatient(Patient $patient)
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


    public function fromData(?EInfoDiabete $infoDiabete)
    {
        
    }

}
