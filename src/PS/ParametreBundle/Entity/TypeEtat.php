<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use PS\UtilisateurBundle\Validator\Constraints as PSAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * TypeEtat
 *
 * @ORM\Table(name="type_etat")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\TypeEtatRepository")
 * @UniqueEntity(fields="libTypeEtat", errorPath="libTypeETat", message="Libellé déjà existant")
 * @GRID\Source(columns="id, libTypeEtat", filterable=false, searchable=false)
 */
class TypeEtat
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
     * @ORM\Column(name="lib_type_etat", type="string", length=100, unique=true)
     * @GRID\Column(title="Libellé")
     */
    private $libTypeEtat;


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
     * Set libTypeEtat
     *
     * @param string $libTypeEtat
     *
     * @return TypeEtat
     */
    public function setLibTypeEtat($libTypeEtat)
    {
        $this->libTypeEtat = $libTypeEtat;

        return $this;
    }

    /**
     * Get libTypeEtat
     *
     * @return string
     */
    public function getLibTypeEtat()
    {
        return $this->libTypeEtat;
    }
}

