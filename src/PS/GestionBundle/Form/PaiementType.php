<?php

namespace PS\GestionBundle\Form;

use PS\GestionBundle\Entity\Paiement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaiementType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('montant', IntegerType::class, [
                'label' => false
            ])
            ->add('nom', TextType::class, [
                'label' => false,
            ])
            ->add('prenoms', TextType::class, [
                'label' => false,
            ])
            ->add('moyenPayement', ChoiceType::class, [
                'choices' => [
                    'OMCIV2' => 'ORANGE MONEY',
                    'MOMOCI' => 'MTN MONEY',
                    'FLOOZ' => 'MOOV MONEY',
                    'CARD' => 'MASTER CARD',
                    'WAVECI' => 'WAVE CI',
                ],
                'multiple' => false,
                'expanded' => false,

            ])
            ->add('contact', TextType::class, [
                'label' => false,
            ])
            ->add('emailPaiement', TextType::class, [
                'label' => false,
            ])
            // ->add('typeDemande')
        ;
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Paiement::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestion_bundle_paiement';
    }
}
