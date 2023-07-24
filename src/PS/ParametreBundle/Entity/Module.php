<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * Module
 *
 * @ORM\Table(name="module")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\ModuleRepository")
 * @GRID\Source(Columns="id,libModule", filterable=false, sortable=false)
 */
class Module
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @GRID\Column(size=4)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="lib_module", type="string", length=100, unique=true)
     * @GRID\Column(title="Libellé")
     */
    private $libModule;


    /**
     * @ORM\ManyToOne(targetEntity="Module")
     */
    private $moduleParent;


     /**
     * @ORM\OneToMany(targetEntity="Permission", mappedBy="module")
     */
    private $permissions;


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
     * Set libModule
     *
     * @param string $libModule
     *
     * @return Module
     */
    public function setLibModule($libModule)
    {
        $this->libModule = $libModule;

        return $this;
    }

    /**
     * Get libModule
     *
     * @return string
     */
    public function getLibModule()
    {
        return $this->libModule;
    }

    /**
     * Set moduleParent
     *
     * @param \PS\ParametreBundle\Entity\Module $moduleParent
     *
     * @return Module
     */
    public function setModuleParent(\PS\ParametreBundle\Entity\Module $moduleParent = null)
    {
        $this->moduleParent = $moduleParent;

        return $this;
    }

    /**
     * Get moduleParent
     *
     * @return \PS\ParametreBundle\Entity\Module
     */
    public function getModuleParent()
    {
        return $this->moduleParent;
    }
}
