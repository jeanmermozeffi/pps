<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\GestionBundle\Entity\FicheAffection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FicheAffectionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('compteRendu', Textareatype::class, ['label' => 'Compte Rendu', 'attr' => ['rows' => 10]])
                //->add('affection')
                ->add('constantes', CollectionType::class, [
                'type'         => FicheParamCliniqueType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'options'      => ['label' => false],
            ])
                ->add('traitements', CollectionType::class, [
                'type'         => FicheTraitementType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'options'      => ['label' => false],
            ])
                ->add('biologiques', CollectionType::class, [
                    'type'         => FicheExamenType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                 'mapped' => false,
                'options'      => ['label' => false, 'categorie' => 1],
                ])
                ->add('cliniques', CollectionType::class, [
                    'type'         => FicheExamenType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'mapped' => false,
                'options'      => ['label' => false, 'categorie' => 2],
                ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FicheAffection::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_ficheaffection';
    }


}
