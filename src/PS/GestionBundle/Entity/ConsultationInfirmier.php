<?php
namespace PS\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PS\ParametreBundle\Entity\Affection;
use PS\ParametreBundle\Entity\Specialite;
use Symfony\Component\Validator\Constraints as Assert;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="\PS\GestionBundle\Repository\ConsultationInfirmierRepository")
 */
class ConsultationInfirmier
{
    /**
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Infirmier",inversedBy="consultationInfirmier")
     */
    private $infirmier;

    /**
     * @ORM\ManyToOne(targetEntity="Consultation")
     */
    private $consultation;
    

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set infirmier
     *
     * @param \PS\GestionBundle\Entity\Infirmier $infirmier
     *
     * @return ConsultationInfirmier
     */
    public function setInfirmier(\PS\GestionBundle\Entity\Infirmier $infirmier = null)
    {
        $this->infirmier = $infirmier;

        return $this;
    }

    /**
     * Get infirmier
     *
     * @return \PS\GestionBundle\Entity\Infirmier
     */
    public function getInfirmier()
    {
        return $this->infirmier;
    }

    /**
     * Set consultation
     *
     * @param \PS\GestionBundle\Entity\Consultation $consultation
     *
     * @return ConsultationInfirmier
     */
    public function setConsultation(\PS\GestionBundle\Entity\Consultation $consultation = null)
    {
        $this->consultation = $consultation;
        
        return $this;
    }

    /**
     * Get consultation
     *
     * @return \PS\GestionBundle\Entity\Consultation
     */
    public function getConsultation()
    {
        return $this->consultation;
    }
}
