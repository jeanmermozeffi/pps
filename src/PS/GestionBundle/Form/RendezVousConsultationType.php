<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\GestionBundle\Entity\RendezVousConsultation;
use SC\DatetimepickerBundle\Form\Type\DatetimeType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
class RendezVousConsultationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('libRendezVous'
                , TextareaType::class,
                [
                    'label' => 'Motif'
                    , 'required' => false
                    , 'constraints' => [
                        //new NotBlank(['message' => 'Veuillez renseigner le libellé du RDV']),
                        new Length(['max' => 255, 'maxMessage' => 'Le libellé ne doit pas excéder {{ limit }} caractères']),
                    ]
                ]
            )

         ->add('dateRendezVous'
                , DatetimeType::class
                , [
                    'label' => 'Date et heure du RDV'
                    , 'widget' => 'single_text'
                    , 'attr' => ['autocomplete' => 'off']
                    , 'required' => false
                   
                    , 'pickerOptions' => [
                        'format'             => 'dd/mm/yyyy hh:ii',
                        'weekStart'          => 1,
                        //'startDate' => date('Y-m-d'), //example
                        //'endDate' => '01/01/3000', //example
                        'autoclose'          => true,
                        'startView'          => 'month',
                        'minView'            => 'hour',
                        'maxView'            => 'decade',
                        'todayBtn'           => true,
                        'todayHighlight'     => true,
                        'keyboardNavigation' => true,
                        'language'           => 'fr',
                        'forceParse'         => false,
                        'minuteStep'         => 15,
                        'pickerPosition'     => 'bottom-right',
                        'viewSelect'         => 'hour',
                        'showMeridian'       => false,
                    ],
                ]
            );
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RendezVousConsultation::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_rendezvousconsultation';
    }


}
