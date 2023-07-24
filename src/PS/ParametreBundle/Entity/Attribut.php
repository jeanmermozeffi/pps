<?php
namespace PS\ParametreBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @GRID\Source(columns="id, libAttribut", sortable=false, filterable=false)
 */
class Attribut
{
    /**
     * @ORM\GeneratedValue
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @GRID\Column(title="ID", primary=true)
     * @Groups({"attribut", "liste-attribut"})
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=255)
     * @Assert\NotBlank()
     * @GRID\Column(title="attribut.form")
     * @Groups({"attribut", "liste-attribut"})
     */
    private $libAttribut;



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
     * Set LibAttribut
     *
     * @param string $libAttribut
     * @return Pays
     */
    public function setLibAttribut($libAttribut)
    {
        $this->libAttribut = $libAttribut;

        return $this;
    }

    /**
     * Get LibAttribut
     *
     * @return string
     */
    public function getLibAttribut()
    {
        return $this->libAttribut;
    }

}
