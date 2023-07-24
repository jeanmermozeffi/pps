<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DonneeQuestionnaire
 *
 * @ORM\Table(name="donnee_questionnaire")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\DonneeQuestionnaireRepository")
 */
class DonneeQuestionnaire
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
     * @var array
     *
     * @ORM\Column(name="reponse", type="json_array")
     */
    private $reponse;



     /**
     * @ORM\ManyToOne(targetEntity="PatientQuestionnaire", inversedBy="donnees")
     * @ORM\JoinColumn(nullable=false)
     */
    private $patient;



     /**
     * @ORM\ManyToOne(targetEntity="LigneQuestionnaire")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ligne;





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
     * Set reponse
     *
     * @param array $reponse
     *
     * @return DonneeQuestionnaire
     */
    public function setReponse($reponse)
    {
        $this->reponse = $reponse;

        return $this;
    }

    /**
     * Get reponse
     *
     * @return array
     */
    public function getReponse()
    {
        return $this->reponse;
    }

    /**
     * Set patient
     *
     * @param \PS\GestionBundle\Entity\PatientQuestionnaire $patient
     *
     * @return DonneeQuestionnaire
     */
    public function setPatient(\PS\GestionBundle\Entity\PatientQuestionnaire $patient)
    {
        $this->patient = $patient;

        return $this;
    }

    /**
     * Get patient
     *
     * @return \PS\GestionBundle\Entity\PatientQuestionnaire
     */
    public function getPatient()
    {
        return $this->patient;
    }

    /**
     * Set ligne
     *
     * @param \PS\GestionBundle\Entity\LigneQuestionnaire $ligne
     *
     * @return DonneeQuestionnaire
     */
    public function setLigne(\PS\GestionBundle\Entity\LigneQuestionnaire $ligne)
    {
        $this->ligne = $ligne;

        return $this;
    }

    /**
     * Get ligne
     *
     * @return \PS\GestionBundle\Entity\LigneQuestionnaire
     */
    public function getLigne()
    {
        return $this->ligne;
    }
}
