<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InfoRendezVous
 *
 * @ORM\Table(name="info_rendez_vous")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\InfoRendezVousRepository")
 */
class InfoRendezVous
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}

