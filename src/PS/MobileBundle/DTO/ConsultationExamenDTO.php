<?php



namespace PS\MobileBundle\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use PS\GestionBundle\Entity\Consultation;
use PS\GestionBundle\Service\Util;



class ConsultationExamenDTO
{

    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez renseigner l'examen")
     */
    private $examen;


    /**
     * @var string
     */
    private $commentaire = '';



    /**
     * Get the value of examen
     *
     * @return  string
     */ 
    public function getExamen()
    {
        return $this->examen;
    }



    /**
     * Set the value of examen
     *
     * @param  string  $examen
     *
     * @return  self
     */ 
    public function setExamen(?string $examen)
    {
        $this->examen = $examen;
        return $this;
    }



    /**
     * Get the value of commentaire
     *
     * @return  string
     */ 
    public function getCommentaire()
    {
        return $this->commentaire;
    }



    /**
     * Set the value of commentaire
     *
     * @param  string  $commentaire
     *
     * @return  self
     */ 
    public function setCommentaire(?string $commentaire)
    {
        $this->commentaire = $commentaire ?? '';
        return $this;
    }

}