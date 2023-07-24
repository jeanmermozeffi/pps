<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\GestionBundle\Entity\ConsultationConstante;
use PS\ParametreBundle\Entity\Constante;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\CallbackTransformer;


class ConsultationConstanteType extends AbstractType
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
                    return $dbValue;
                },
                function ($inputValue) use($options) {
                    // transform the string back to an array
                    return strval($inputValue);
                }
            ))
        ;

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ConsultationConstante::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_consultationconstante';
    }


}
