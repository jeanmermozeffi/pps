<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RealisationIvaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('statutRealisation', ChoiceType::class,[
            'label'=>'IVA réalisée ce Jour ?',
            'required' => false,
            'placeholder'=>false,
            'choices'=>[
                1=>'OUI',
                0=>'NON'
            ],
            'expanded' => true,
            // 'multiple' => false
        ])
        ->add('resultatIVA', ChoiceType::class,[
            'label' => 'Résulat du dépistage selon IVA réalisée:',
            'required' => false,
            'placeholder' => false,
            'choices' => [
                1=>'Suspicion de Cancer',
                0=>'Autres raisons',
                2=>'IVA négative',
                -2=>'IVA positive',
            ],
            'expanded' => true,
            // 'multiple' => false
        ])
        ->add('raisonAnnulation', TextType::class,[
            'label'=>'Raison de l\'annulation',
            'required' => false,
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PS\GestionBundle\Entity\RealisationIva'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_realisationiva';
    }


}
