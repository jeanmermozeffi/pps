<?php

namespace PS\ParametreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\ParametreBundle\Entity\TypeEtat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EtatType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('libEtat', null, [
            'label' => 'etat.form.libEtat'
            ])
        ->add('typeEtat', EntityType::class, [
            'class' => TypeEtat::class, 'choice_label' => 'libTypeEtat', 'label' => 'etat.form.typeEtat'
            ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PS\ParametreBundle\Entity\Etat'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_parametrebundle_etat';
    }


}
