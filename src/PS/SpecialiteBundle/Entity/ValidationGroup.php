<?php

namespace PS\SpecialiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ValidationGroup
 *
 * @ORM\Table(name="validation_group")
 * @ORM\Entity(repositoryClass="PS\SpecialiteBundle\Repository\ValidationGroupRepository")
 */
class ValidationGroup
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
     * @ORM\Column(name="type_validation", type="string", length=100)
     */
    private $typeValidation;

    

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
     * Set typeValidation
     *
     * @param string $typeValidation
     *
     * @return ValidationGroup
     */
    public function setTypeValidation($typeValidation)
    {
        $this->typeValidation = $typeValidation;

        return $this;
    }

    /**
     * Get typeValidation
     *
     * @return string
     */
    public function getTypeValidation()
    {
        return $this->typeValidation;
    }

   
}
