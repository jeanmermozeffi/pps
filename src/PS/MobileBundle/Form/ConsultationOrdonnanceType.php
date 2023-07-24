<?php

namespace PS\MobileBundle\Form;

use PS\MobileBundle\DTO\ConsultationOrdonnanceDTO;
use PS\GestionBundle\Entity\Corporate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use PS\ParametreBundle\Entity\Image;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConsultationOrdonnanceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('medicament')->add('posologie')->add('commentaire', null, ['empty_data' => '']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ConsultationOrdonnanceDTO::class,
            'csrf_protection' => false,
        ));

    }
}
