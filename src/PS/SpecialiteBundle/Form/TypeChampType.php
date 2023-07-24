<?php

namespace PS\SpecialiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TypeChampType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = ["ChoiceType", "CheckBoxType","RadioType", "SelectType", "BoolType", "NumberType", "TextType", "TextareaType", "DateType", "VoidType", "DateTimeType"];
        sort($choices);
        $builder->add('libTypeChamp')
            ->add('aliasTypeChamp'
                , ChoiceType::class
                , [

                    'choices' => array_combine($choices, $choices)
                ]
            );
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PS\SpecialiteBundle\Entity\TypeChamp'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_specialitebundle_typechamp';
    }


}
