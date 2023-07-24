<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\GestionBundle\Entity\FacteurRisqueCancer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FacteurRisqueCancerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('cancerSeinMamno', ChoiceType::class, [
                'expanded' => true,
                'choices' => [1 => 'Oui', 0 => 'Non'],
                'label' => 'Votre mère, votre sœur et/ou votre fille ont-elles souffert d’un cancer du sein avant la ménopause ?'
            ])
            ->add('traitementHormoMamno', ChoiceType::class, [
                'expanded' => true,
                'choices' => [1 => 'Oui', 0 => 'Non'],
                'label' => 'Suivez-vous depuis plus de 5 ans un traitement hormonal contre les troubles de la ménopause ?'
            ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FacteurRisqueCancer::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_facteurrisquecancer';
    }


}
