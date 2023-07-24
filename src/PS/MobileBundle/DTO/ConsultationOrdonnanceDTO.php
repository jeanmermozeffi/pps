<?php



namespace PS\MobileBundle\DTO;


use Symfony\Component\Validator\Constraints as Assert;
use PS\GestionBundle\Entity\Consultation;
use PS\GestionBundle\Service\Util;


class ConsultationOrdonnanceDTO
{

    /**
     * @var string
     * @Assert\NotBlank(message="Veuilez renseigner le médicament")
     */
    private $medicament;


    /**
     * @var string
     * @Assert\NotBlank(message="Veuilez renseigner la posologie")
     */
    private $posologie;

    /**
     * @var string
     */
    private $commentaire = '';


    public function __construct()
    {
        $this->setCommentaire('');
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

        $this->commentaire = $commentaire;



        return $this;

    }



    /**

     * Get the value of posologie

     *

     * @return  string

     */ 

    public function getPosologie()

    {

        return $this->posologie;

    }



    /**

     * Set the value of posologie

     *

     * @param  string  $posologie

     *

     * @return  self

     */ 

    public function setPosologie(?string $posologie)

    {

        $this->posologie = $posologie;



        return $this;

    }



    /**

     * Get the value of medicament

     *

     * @return  string

     */ 

    public function getMedicament()

    {

        return $this->medicament;

    }



    /**

     * Set the value of medicament

     *

     * @param  string  $medicament

     *

     * @return  self

     */ 

    public function setMedicament(?string $medicament)

    {

        $this->medicament = $medicament;



        return $this;

    }

}