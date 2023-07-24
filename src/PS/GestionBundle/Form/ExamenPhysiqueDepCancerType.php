<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\GestionBundle\Entity\ExamenPhysiqueDepCancer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ExamenPhysiqueDepCancerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('seinDroit', ChoiceType::class, [
                'choices' => ExamenPhysiqueDepCancer::EXAMENS,
                'label' => 'Sein droit et Creux axillaire droit',
                'multiple' => true,
                'expanded' => true,
                'required' => false
            ])
            ->add('seinGauche', ChoiceType::class, [
                'choices' => ExamenPhysiqueDepCancer::EXAMENS,
                'label' => 'Sein gauche et Creux axillaire gauche',
                'multiple' => true,
                'expanded' => true,
                'required' => false
            ])
            ->add('anomalies', ChoiceType::class, [
                'choices' => ExamenPhysiqueDepCancer::ANOMALIES,
                'label' => 'En cas d’anomalie retrouvée, quelle conduite à tenir a été proposée ?',
                'multiple' => true,
                'expanded' => true,
                'required' => false
            ]);

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExamenPhysiqueDepCancer::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_examenphysiquedepcancer';
    }


}
