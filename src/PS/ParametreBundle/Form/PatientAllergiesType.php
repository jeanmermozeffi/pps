<?php

namespace PS\ParametreBundle\Form;

use PS\ParametreBundle\Entity\PatientAllergies;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientAllergiesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('allergie',null,[
                'label'=> 'patientallergie.form.allergie'
            ])
            ->add('commentaire', TextareaType::class, [
                'required' => false,
                'label'=> 'patientallergie.form.commentaire'
                ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => PatientAllergies::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_parametrebundle_patientallergies';
    }


}
