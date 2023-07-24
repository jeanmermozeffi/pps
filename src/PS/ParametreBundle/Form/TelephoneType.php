<?php

namespace PS\ParametreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\ParametreBundle\Entity\Telephone;

class TelephoneType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = Telephone::CHOICES;

        $choices = array_combine($choices, $choices);
        
        $builder
            ->add('nom',null,[
                'label'=>'telephone.form.nom'
            ])
            ->add('sms', CheckboxType::class, [
                'label' => 'telephone.form.sms',
            ])
            ->add('numero',null,[
            'label' => 'telephone.form.numero'
            ])
            ->add('lien'
                , ChoiceType::class
                , [
                    'choices' => $choices,
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Telephone::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_parametrebundle_telephone';
    }

}
