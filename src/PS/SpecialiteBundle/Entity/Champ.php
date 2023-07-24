<?php

namespace PS\SpecialiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * Champ
 *
 * @ORM\Table(name="champ")
 * @ORM\Entity(repositoryClass="PS\SpecialiteBundle\Repository\ChampRepository")
 * @GRID\Source(columns="id,libChamp,etape.libEtape,typeChamp.libTypeChamp", filterable=false, sortable=false)
 */
class Champ
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
     * @ORM\Column(name="lib_champ", type="string", nullable=true)
     * @GRID\Column(title="Libellé", filterable=true, operatorsVisible=false, operators={"like"}, defaultOperator="like")
     */
    private $libChamp;


    /**
     * @var array
     *
     * @ORM\Column(name="alias_champ", type="string")
     * @GRID\Column(title="Libellé")
     */
    private $aliasChamp;


    /**
     * @ORM\Column(name="valeur_autorisee_champ", type="json_array")
     */
    private $valeurAutoriseeChamp;


    /**
     * @ORM\Column(name="valeur_champ_defaut", type="text", nullable=true)
     */
    private $valeurChampDefaut;


    /**
     * @ORM\ManyToOne(targetEntity="Etape", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     * @GRID\Column(field="etape.libEtape", title="Etape")
     */
    private $etape;


    

     /**
     * @ORM\ManyToOne(targetEntity="TypeChamp", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     * @GRID\Column(field="typeChamp.libTypeChamp", title="Type")
     */
    private $typeChamp;



    /**
     * @ORM\ManyToOne(targetEntity="Champ", fetch="EAGER")
     */
    private $champParent;


    /**
     * @ORM\OneToMany(targetEntity="Champ", mappedBy="champParent")
     */
    private $champEnfants;

    /**
     *
     * @ORM\Column(name="valeur_champ_parent", type="string", nullable=true)
     */
    private $valeurChampParent;


    /**
     * @ORM\Column(name="champ_requis", type="boolean", options={"default": 1})
     */
    private $champRequis;

    /**
     * @ORM\Column(name="prop_champ", type="json_array")
    */
    private $propChamp;

    /**
     * @ORM\Column(name="validation_group", type="string", length=100, nullable=true)
     */
    private $validationGroup;


    public function __construct()
    {
        $this->propChamp = [];
    }



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
     * Set libChamp
     *
     * @param string $libChamp
     *
     * @return Champ
     */
    public function setLibChamp($libChamp)
    {
        $this->libChamp = $libChamp;

        return $this;
    }

    /**
     * Get libChamp
     *
     * @return string
     */
    public function getLibChamp()
    {
        return $this->libChamp;
    }

    /**
     * Set etape
     *
     * @param \PS\SpecialiteBundle\Entity\Etape $etape
     *
     * @return Champ
     */
    public function setEtape(\PS\SpecialiteBundle\Entity\Etape $etape)
    {
        $this->etape = $etape;

        return $this;
    }

    /**
     * Get etape
     *
     * @return \PS\SpecialiteBundle\Entity\Etape
     */
    public function getEtape()
    {
        return $this->etape;
    }

    

    /**
     * Set typeChamp
     *
     * @param \PS\SpecialiteBundle\Entity\TypeChamp $typeChamp
     *
     * @return Champ
     */
    public function setTypeChamp(\PS\SpecialiteBundle\Entity\TypeChamp $typeChamp)
    {
        $this->typeChamp = $typeChamp;

        return $this;
    }

    /**
     * Get typeChamp
     *
     * @return \PS\SpecialiteBundle\Entity\TypeChamp
     */
    public function getTypeChamp()
    {
        return $this->typeChamp;
    }



    /**
     * Set valChampParent
     *
     * @param string $valChampParent
     *
     * @return Champ
     */
    public function setValChampParent($valChampParent)
    {
        $this->valChampParent = $valChampParent;

        return $this;
    }

    /**
     * Get valChampParent
     *
     * @return string
     */
    public function getValChampParent()
    {
        return $this->valChampParent;
    }

    /**
     * Set champParent
     *
     * @param \PS\SpecialiteBundle\Entity\Champ $champParent
     *
     * @return Champ
     */
    public function setChampParent(\PS\SpecialiteBundle\Entity\Champ $champParent = null)
    {
        $this->champParent = $champParent;

        return $this;
    }

    /**
     * Get champParent
     *
     * @return \PS\SpecialiteBundle\Entity\Champ
     */
    public function getChampParent()
    {
        return $this->champParent;
    }

    /**
     * Set valeurAutoriseeChamp
     *
     * @param json $valeurAutoriseeChamp
     *
     * @return Champ
     */
    public function setValeurAutoriseeChamp($valeurAutoriseeChamp)
    {
        $this->valeurAutoriseeChamp = $valeurAutoriseeChamp;

        return $this;
    }

    /**
     * Get valeurAutoriseeChamp
     *
     * @return json
     */
    public function getValeurAutoriseeChamp()
    {
        return $this->valeurAutoriseeChamp;
    }

   

    /**
     * Set valeurChampParent
     *
     * @param string $valeurChampParent
     *
     * @return Champ
     */
    public function setValeurChampParent($valeurChampParent)
    {
        $this->valeurChampParent = $valeurChampParent;

        return $this;
    }

    /**
     * Get valeurChampParent
     *
     * @return string
     */
    public function getValeurChampParent()
    {
        return $this->valeurChampParent;
    }

    /**
     * Set champRequis
     *
     * @param boolean $champRequis
     *
     * @return Champ
     */
    public function setChampRequis($champRequis)
    {
        $this->champRequis = $champRequis;

        return $this;
    }

    /**
     * Get champRequis
     *
     * @return boolean
     */
    public function getChampRequis()
    {
        return $this->champRequis;
    }

    /**
     * Set propChamp
     *
     * @param json $propChamp
     *
     * @return Champ
     */
    public function setPropChamp($propChamp)
    {
        $this->propChamp = $propChamp;

        return $this;
    }

    /**
     * Get propChamp
     *
     * @return json
     */
    public function getPropChamp()
    {
        return $this->propChamp;
    }

    /**
     * Set valeurChampDefaut
     *
     * @param string $valeurChampDefaut
     *
     * @return Champ
     */
    public function setValeurChampDefaut($valeurChampDefaut)
    {
        $this->valeurChampDefaut = $valeurChampDefaut;

        return $this;
    }

    /**
     * Get valeurChampDefaut
     *
     * @return string
     */
    public function getValeurChampDefaut()
    {
        return $this->valeurChampDefaut;
    }

    /**
     * Set aliasChamp
     *
     * @param string $aliasChamp
     *
     * @return Champ
     */
    public function setAliasChamp($aliasChamp)
    {
        $this->aliasChamp = $aliasChamp;

        return $this;
    }

    /**
     * Get aliasChamp
     *
     * @return string
     */
    public function getAliasChamp()
    {
        return $this->aliasChamp;
    }


    /**
     * Add champEnfant
     *
     * @param \PS\SpecialiteBundle\Entity\Champ $champEnfant
     *
     * @return Champ
     */
    public function addChampEnfant(\PS\SpecialiteBundle\Entity\Champ $champEnfant)
    {
        $this->champEnfants[] = $champEnfant;

        return $this;
    }

    /**
     * Remove champEnfant
     *
     * @param \PS\SpecialiteBundle\Entity\Champ $champEnfant
     */
    public function removeChampEnfant(\PS\SpecialiteBundle\Entity\Champ $champEnfant)
    {
        $this->champEnfants->removeElement($champEnfant);
    }

    /**
     * Get champEnfants
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChampEnfants()
    {
        return $this->champEnfants;
    }




    public function getAllParents()
    {
        $result = [];
        $initial = $this;

        while ($champParent = $initial->getChampParent()) {
            $result[$champParent->getAliasChamp()] = $champParent->getTypeChamp()->getAliasTypeChamp();
            $initial = $champParent;
        }

        return $result;
    }


    private function getSiblings()
    {
        
    }

    

    /**
     * Set validationGroup
     *
     * @param string $validationGroup
     *
     * @return Champ
     */
    public function setValidationGroup($validationGroup)
    {
        $this->validationGroup = $validationGroup;

        return $this;
    }

    /**
     * Get validationGroup
     *
     * @return string
     */
    public function getValidationGroup()
    {
        return $this->validationGroup;
    }
}
