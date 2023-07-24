<?php

namespace PS\ParametreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
/**
 * Permission
 *
 * @ORM\Table(name="permission")
 * @ORM\Entity(repositoryClass="PS\ParametreBundle\Repository\PermissionRepository")
 */
class Permission
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
     * @ORM\Column(name="lib_permission", type="string", length=255)
     */
    private $libPermission;

    /**
     * @var string
     *
     * @ORM\Column(name="code_permission", type="string", length=150, unique=true)
     */
    private $codePermission;

     /**
     * @var string
     *
     * @ORM\Column(name="url_permission", type="string", length=150, unique=true)
     */
    private $urlPermission;

    /**
     * @ORM\ManyToOne(targetEntity="Module", inversedBy="permissions")
     */
    private $module;


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
     * Set libPermission
     *
     * @param string $libPermission
     *
     * @return Permission
     */
    public function setLibPermission($libPermission)
    {
        $this->libPermission = $libPermission;

        return $this;
    }

    /**
     * Get libPermission
     *
     * @return string
     */
    public function getLibPermission()
    {
        return $this->libPermission;
    }

    /**
     * Set codePermission
     *
     * @param string $codePermission
     *
     * @return Permission
     */
    public function setCodePermission($codePermission)
    {
        $this->codePermission = $codePermission;

        return $this;
    }

    /**
     * Get codePermission
     *
     * @return string
     */
    public function getCodePermission()
    {
        return $this->codePermission;
    }

    /**
     * Set urlPermission
     *
     * @param string $urlPermission
     *
     * @return Permission
     */
    public function setUrlPermission($urlPermission)
    {
        $this->urlPermission = $urlPermission;

        return $this;
    }

    /**
     * Get urlPermission
     *
     * @return string
     */
    public function getUrlPermission()
    {
        return $this->urlPermission;
    }
}
