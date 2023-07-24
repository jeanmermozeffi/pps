<?php

namespace PS\ApiBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConsultationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateConsultation', 'date', [
                'widget'   => 'single_text',
                'format'   => 'dd-MM-yyyy',
                'html5'    => false,
                'attr'     => ['class' => 'datepicker'],
                'disabled' => true,
            ])
            ->add('motif', TextareaType::class)
            ->add('diagnostique', TextareaType::class)
            ->add('poids')
            ->add('temperature')
            ->add('tension')
            //->add('patient', PatientType::class)
            ->add('specialite', EntityType::class, [
                'class'       => 'ParametreBundle:Specialite',
                'property'    => 'nom',
                'empty_value' => "--- Choisir une specialité ---",
                'empty_data'  => null,
            ])

            ->add('hospitalisation', TextareaType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => 'PS\GestionBundle\Entity\Consultation',
            'csrf_protection' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_consultation';
    }

}
