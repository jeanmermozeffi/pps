<?php

namespace PS\SpecialiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Diagnostic
 *
 * @ORM\Table(name="diagnostic")
 * @ORM\Entity(repositoryClass="PS\SpecialiteBundle\Repository\DiagnosticRepository")
 */
class Diagnostic
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
     * @ORM\Column(name="libDiagnostic", type="string", length=255)
     */
    private $libDiagnostic;


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
     * Set libDiagnostic
     *
     * @param string $libDiagnostic
     *
     * @return Diagnostic
     */
    public function setLibDiagnostic($libDiagnostic)
    {
        $this->libDiagnostic = $libDiagnostic;

        return $this;
    }

    /**
     * Get libDiagnostic
     *
     * @return string
     */
    public function getLibDiagnostic()
    {
        return $this->libDiagnostic;
    }
}

