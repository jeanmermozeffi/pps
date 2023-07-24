<?php

namespace PS\ParametreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use PS\ParametreBundle\Entity\PatientAffections;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class PatientAffectionsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('visible', ChoiceType::class, [
                'choices'           => array_flip([
                    1        => 'Public',
                    0         => 'Privé',
                    
                ]),
                'choices_as_values' => true,
                'expanded'          => false,
                'multiple'          => false,
                'label' => 'patientaffection.form.visible'
        ])
            ->add('affection', null, ['label' => false])
            ->add('commentaire', TextareaType::class, ['required' => false, 'label' => false])
            ->add('date', DateType::class, ['widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'required' => false, 'attr' => ['autocomplete' => 'off', 'class' => 'datepicker']]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => PatientAffections::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_parametrebundle_patientaffections';
    }


}
