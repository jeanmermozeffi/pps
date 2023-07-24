<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\GestionBundle\Entity\FicheParamClinique;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\CallbackTransformer;
use PS\ParametreBundle\Entity\Constante;

class FicheParamCliniqueType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $builder->add('valeur', null, ['required' => false, 'empty_data' => ''])

            ->add('constante', EntityType::class, [
                'class' => Constante::class,
                'choice_label' => 'libelle'
            ]);

        $builder->get('valeur')
            ->addModelTransformer(new CallbackTransformer(
                function ($dbValue) {
                    // transform the array to a string
                    return (string)$dbValue;
                },
                function ($inputValue) use($options) {
                    // transform the string back to an array
                    return (string)$inputValue;
                }
            ))
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FicheParamClinique::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_ficheparamclinique';
    }


}
