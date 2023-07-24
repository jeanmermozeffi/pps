<?php



namespace PS\MobileBundle\Form;



use PS\MobileBundle\DTO\ConsultationDTO;

use PS\GestionBundle\Entity\Corporate;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\FileType;

use PS\ParametreBundle\Entity\Image;
use PS\ParametreBundle\Entity\Specialite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\File;

use Symfony\Component\Validator\Constraints\NotBlank;



class ConsultationType extends AbstractType

{

    /**

     * {@inheritdoc}

     */

    public function buildForm(FormBuilderInterface $builder, array $options)

    {

        $builder
            ->add('identifiant')
            ->add('pin')
            ->add('motif')

            ->add('diagnostic')

            ->add('symptome')
            ->add('specialite', IntegerType::class)
            ->add('ordonnances', CollectionType::class, [

                'entry_type'         => ConsultationOrdonnanceType::class,

                'label'        => false,

                'allow_add'    => true,

                'allow_delete' => true,

                'by_reference' => false,

                'entry_options'      => ['label' => false],

            ])

            ->add('examens', CollectionType::class, [

                'entry_type'         => ConsultationExamenType::class,

                'label'        => false,

                'allow_add'    => true,

                'allow_delete' => true,

                'by_reference' => false,

                'entry_options'      => ['label' => false],

            ]);

    }



    /**

     * {@inheritdoc}

     */

    public function configureOptions(OptionsResolver $resolver)

    {

        $resolver->setDefaults(array(

            'data_class' => ConsultationDTO::class,

            'csrf_protection' => false,

           

            

        ));

    }

}

