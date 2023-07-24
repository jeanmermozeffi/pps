<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TraitementCdipType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('cdipOffert', ChoiceType::class, [
            'label' => 'CDIP offert ? (pour tout statut HIV inconnu ou négatives supérieur à plus de trois mois)',
            'required' => false,
            'placeholder' => false,
            'choices' => [
                1 => 'OUI',
                0 => 'NON'
                ],
            'expanded' => true,
            ])
            ->add('raisonCdipOffert', TextType::class, [
            'required' => false,
                'label' => 'Raison :',
                'required' => false
            ])
            ->add('cdipAccepter', ChoiceType::class, [
                'placeholder' => false,
                'required' => false,
                'label' => 'CDIP accepté ?',
                'choices' => [
                    1 => 'OUI',
                    0 => 'NON'
                ],
                'expanded' => true,
            ])
            ->add('raisonCdipAccepter', TextType::class, [
                'label' => 'Raison :',
                'required'=>false
            ])
            ->add('resultatCdip', ChoiceType::class, [
            'required' => false,
            'placeholder' => false,
                'label' => 'HIV Résultat du CDIP',
                'choices' => [
                    1 => 'Positive',
                    0 => 'Négative',
                    -1 => 'Indéterminé'
                ],
                'expanded'=>true
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PS\GestionBundle\Entity\TraitementCdip'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_traitementcdip';
    }
}
