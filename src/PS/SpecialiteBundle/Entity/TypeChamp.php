<?php

namespace PS\SpecialiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TypeChamp
 *
 * @ORM\Table(name="type_champ")
 * @ORM\Entity(repositoryClass="PS\SpecialiteBundle\Repository\TypeChampRepository")
 * @GRID\Source(columns="id, libTypeChamp, aliasTypeChamp", filterable=false, sortable=false)
 */
class TypeChamp
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
     * @var string
     *
     * @ORM\Column(name="lib_type_champ", type="string", length=255)
     * @GRID\Column(title="Libellé")
     */
    private $libTypeChamp;

    /**
     * @var array
     *
     * @ORM\Column(name="alias_type_champ", type="string", length=50, unique=true)
     * @Assert\Choice(choices= {"ChoiceType", "SelectType", "BoolType", "NumberType", "TextType", "TextareaType", "RadioType", "CheckboxType", "DateType", "VoidType", "DateTimeType"}, message= "Veuillez sélectionner un élément de la liste")
     * @GRID\Column(title="Alias")
     */
    private $aliasTypeChamp;


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
     * Set libTypeChamp
     *
     * @param string $libTypeChamp
     *
     * @return TypeChamp
     */
    public function setLibTypeChamp($libTypeChamp)
    {
        $this->libTypeChamp = $libTypeChamp;

        return $this;
    }

    /**
     * Get libTypeChamp
     *
     * @return string
     */
    public function getLibTypeChamp()
    {
        return $this->libTypeChamp;
    }

   

    /**
     * Set aliasTypeChamp
     *
     * @param string $aliasTypeChamp
     *
     * @return TypeChamp
     */
    public function setAliasTypeChamp($aliasTypeChamp)
    {
        $this->aliasTypeChamp = $aliasTypeChamp;

        return $this;
    }

    /**
     * Get aliasTypeChamp
     *
     * @return string
     */
    public function getAliasTypeChamp()
    {
        return $this->aliasTypeChamp;
    }
}
