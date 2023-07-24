<?php

namespace PS\GestionBundle\Form;

use PS\GestionBundle\Entity\Commande;
use PS\GestionBundle\Entity\Corporate;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('corporate'
                , EntityType::class
                , [
                    'class' => Corporate::class
                    , 'property' => 'raison_sociale'
                    , 'label' => 'Corporate',
                ])
        ->add('qteCommande', NumberType::class, ['label' => 'Quantité'])
            ->add('detailsCommande', TextareaType::class, ['label' => 'Détails', 'required' => false]);
            /*->add('statutCommande'
                , ChoiceType::class
                , [
                    'choices'           => [
                        -1 => "En attente"
                        , 1 => "Traité"
                        , 0 => "Annulé",
                    ]
                    , "data" => -1,
                    //'choices_as_values' => true,
                ]);*/
            

        $builder->get('detailsCommande')->addModelTransformer(
            new CallbackTransformer(
                function ($details) {
                    return (string) $details;
                },
                function ($details) {
                    return (string) $details;
                }
            )
        );

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_commande';
    }

}
