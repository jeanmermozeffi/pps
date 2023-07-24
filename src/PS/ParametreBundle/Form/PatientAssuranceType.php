<?php

namespace PS\ParametreBundle\Form;

use PS\ParametreBundle\Entity\PatientAssurance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientAssuranceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('taux',null,[
                'label'=>'patientassurance.form.taux'
            ])
            ->add('numero',null,[
            'label' => 'patientassurance.form.numero'
            ])
            ->add('assurance',null,[
            'label' => 'patientassurance.form.assurance'
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => PatientAssurance::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_parametrebundle_patientassurance';
    }


}
