<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Theme
 *
 * @ORM\Table(name="theme")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\ThemeRepository")
 */
class Theme
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
     * @ORM\Column(name="libTheme", type="string", length=255, unique=true)
     */
    private $libTheme;

    /**
     * @ORM\OneToMany(targetEntity="Faq", mappedBy="theme")
     */
    private $faqs;


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
     * Set libTheme
     *
     * @param string $libTheme
     *
     * @return Theme
     */
    public function setLibTheme($libTheme)
    {
        $this->libTheme = $libTheme;

        return $this;
    }

    /**
     * Get libTheme
     *
     * @return string
     */
    public function getLibTheme()
    {
        return $this->libTheme;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->faqs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add faq
     *
     * @param \PS\GestionBundle\Entity\Faq $faq
     *
     * @return Theme
     */
    public function addFaq(\PS\GestionBundle\Entity\Faq $faq)
    {
        $this->faqs[] = $faq;
        $faq->setTheme($this);
        return $this;
    }

    /**
     * Remove faq
     *
     * @param \PS\GestionBundle\Entity\Faq $faq
     */
    public function removeFaq(\PS\GestionBundle\Entity\Faq $faq)
    {
        $this->faqs->removeElement($faq);
    }

    /**
     * Get faqs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFaqs()
    {
        return $this->faqs;
    }
}
