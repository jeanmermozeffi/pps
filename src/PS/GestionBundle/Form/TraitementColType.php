<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TraitementColType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('typeTraitementCol', ChoiceType::class,[
            'label'=>'Traitement :',
            'required' => false,
            'placeholder' => false,
            'choices'=>[
                1=>'Cryothérapie réalisée ce jour',
                0=>'Cryothérapie réportée (Veuillez indiqué la raison en dessous !)',
                -1=>'LEEP réalisée ce jour ( Seulement pour les sites de réfernce )',
            ],
            'expanded'=>true,
        ])
        ->add('raisonReportCryotherapie', TextType::class,[
            'label'=>'Raison du repport :', 
            'required' => false,
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PS\GestionBundle\Entity\TraitementCol'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_traitementcol';
    }


}
