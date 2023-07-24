<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @todo: @UniqueConstraints
 */

/**
 * OptionSite
 *
 * @ORM\Table(name="option_site")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\OptionSiteRepository")
 * @UniqueEntity(fields={"site", "option"}, message="cette option existe déjà pour ce site")
 */
class OptionSite
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
     * @ORM\ManyToOne(targetEntity="PS\ParametreBundle\Entity\ListeOption")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Veuillez renseigner une option pour chaque ligne")
     */
    private $option;


    /**
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="options")
     * @ORM\JoinColumn(nullable=false)
     */
    private $site;


     /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Veuillez renseigner la valeur pour chaque ligne")
     */
    private $valeur;



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
     * Set option
     *
     * @param \PS\ParametreBundle\Entity\ListeOption $option
     *
     * @return OptionSite
     */
    public function setOption(\PS\ParametreBundle\Entity\ListeOption $option )
    {
        $this->option = $option;

        return $this;
    }

    /**
     * Get option
     *
     * @return \PS\ParametreBundle\Entity\ListeOption
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * Set site
     *
     * @param \PS\GestionBundle\Entity\Site $site
     *
     * @return OptionSite
     */
    public function setSite(\PS\GestionBundle\Entity\Site $site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return \PS\GestionBundle\Entity\Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set valeur
     *
     * @param string $valeur
     *
     * @return OptionSite
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;

        return $this;
    }

    /**
     * Get valeur
     *
     * @return string
     */
    public function getValeur()
    {
        return $this->valeur;
    }
}
