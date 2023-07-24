<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ReferenceCancerColType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('libReference', ChoiceType::class,[
            'placeholder' => false,
            'required' => false,
            'label' => false,
            'choices'=>[
                1=> 'Suspicion de Cancer',
                0=> 'Lésion Large',
            ],
            'expanded'=>true,
        ])
        ->add('typeReference', ChoiceType::class,[
            'label'=> 'Résultat du Suspicion / Lésion du Cancer référé',
            'placeholder' => false,
            'required' => false,
            'choices' => [
                1 => 'Cancer confirmé',
                0 => 'Cancer non confirmé',
                2 => 'LEEP réalisée',
                -2 => 'Autre',
            ],
            'expanded' => true,
        ])
        ->add('problemeGynegologique', TextType::class,[
            'label'=>'Autre problème Gynécologique(spécifé)',
            'required'=>false
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PS\GestionBundle\Entity\ReferenceCancerCol'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_referencecancercol';
    }


}
