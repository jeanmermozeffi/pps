<?php

namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Faq
 *
 * @ORM\Table(name="faq")
 * @ORM\Entity(repositoryClass="PS\GestionBundle\Repository\FaqRepository")
 */
class Faq
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
     * @ORM\Column(name="question", type="string", length=255)
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(name="reponse", type="text")
     */
    private $reponse;

    /**
     * @var datetime
     *
     * @ORM\Column(name="date_ajout_faq", type="datetime")
     */
    private $dateAjoutFaq;



     /**
     * @var datetime
     *
     * @ORM\Column(name="date_modif_faq", type="datetime")
     */
    private $dateModifFaq;

    /**
     * @ORM\ManyToOne(targetEntity="Theme", inversedBy="faqs")
     */
    private $theme;



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
     * Set question
     *
     * @param string $question
     *
     * @return Faq
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set reponse
     *
     * @param string $reponse
     *
     * @return Faq
     */
    public function setReponse($reponse)
    {
        $this->reponse = $reponse;

        return $this;
    }

    /**
     * Get reponse
     *
     * @return string
     */
    public function getReponse()
    {
        return $this->reponse;
    }


    /**
     * Set dateAjoutFaq
     *
     * @param \DateTime $dateAjoutFaq
     *
     * @return Faq
     */
    public function setDateAjoutFaq($dateAjoutFaq)
    {
        $this->dateAjoutFaq = $dateAjoutFaq;

        return $this;
    }

    /**
     * Get dateAjoutFaq
     *
     * @return \DateTime
     */
    public function getDateAjoutFaq()
    {
        return $this->dateAjoutFaq;
    }

    /**
     * Set dateModifFaq
     *
     * @param \DateTime $dateModifFaq
     *
     * @return Faq
     */
    public function setDateModifFaq($dateModifFaq)
    {
        $this->dateModifFaq = $dateModifFaq;

        return $this;
    }

    /**
     * Get dateModifFaq
     *
     * @return \DateTime
     */
    public function getDateModifFaq()
    {
        return $this->dateModifFaq;
    }

    /**
     * Set theme
     *
     * @param \PS\GestionBundle\Entity\Theme $theme
     *
     * @return Faq
     */
    public function setTheme(\PS\GestionBundle\Entity\Theme $theme = null)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get theme
     *
     * @return \PS\GestionBundle\Entity\Theme
     */
    public function getTheme()
    {
        return $this->theme;
    }


    public function formatReponse()
    {
        $content = $this->replaceUrl($this->reponse);
        $content = $this->replaceMail($content);
        return $content;
    }

    private function replaceUrl($content)
    {
        return preg_replace('/(https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&\/\/=]*))/', '<a href="$0">$0</a>', $content);
    }


    private function replaceMail($content)
    {
        return preg_replace('~\[mail=(.+)\]~', '<a href="mailto:$1">$1</a>', $content);
    }
}
