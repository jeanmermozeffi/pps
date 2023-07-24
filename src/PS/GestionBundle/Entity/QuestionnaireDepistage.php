<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * QuestionnaireDepistage
 *
 * @ORM\Table(name="questionnaire_depistage")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\QuestionnaireDepistageRepository")
 * @GRID\Source(columns="id, libelle, affection.nom")
 */
class QuestionnaireDepistage extends Questionnaire
{
    

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="json_array")
     */
    private $roles;


      /**
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\Affection")
     * @GRID\Column(field="affection.nom", title="Affection")
     */
    private $affection;



    /**
     * @ORM\ManyToMany(targetEntity="PS\ParametreBundle\Entity\Specialite")
     * @ORM\JoinTable(name="questionnaire_specialite")
     */
    private $specialites;


   

    /**
     * Set roles
     *
     * @param array $roles
     *
     * @return QuestionnaireDepistage
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->specialites = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set affection
     *
     * @param \PS\ParametreBundle\Entity\Affection $affection
     *
     * @return QuestionnaireDepistage
     */
    public function setAffection(\PS\ParametreBundle\Entity\Affection $affection = null)
    {
        $this->affection = $affection;

        return $this;
    }

    /**
     * Get affection
     *
     * @return \PS\ParametreBundle\Entity\Affection
     */
    public function getAffection()
    {
        return $this->affection;
    }

   
    /**
     * Add specialite
     *
     * @param \PS\ParametreBundle\Entity\Specialite $specialite
     *
     * @return QuestionnaireDepistage
     */
    public function addSpecialite(\PS\ParametreBundle\Entity\Specialite $specialite)
    {
        $this->specialites[] = $specialite;

        return $this;
    }

    /**
     * Remove specialite
     *
     * @param \PS\ParametreBundle\Entity\Specialite $specialite
     */
    public function removeSpecialite(\PS\ParametreBundle\Entity\Specialite $specialite)
    {
        $this->specialites->removeElement($specialite);
    }

    /**
     * Get specialites
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSpecialites()
    {
        return $this->specialites;
    }
}
