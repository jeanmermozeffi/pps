<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\GestionBundle\Entity\Suivi;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use PS\ParametreBundle\Entity\PatientAffections;


class SuiviType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('affection', EntityType::class, ['choices' => $options['patient']->getAffections(), 'class' => PatientAffections::class, 'choice_label' => 'affection', 'required' => false, 'placeholder' => ''])->add('motif')->add('details', null, ['attr' => ['rows' => 15]]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Suivi::class,
        ]);

        $resolver->setRequired('patient');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_suivi';
    }


}
