<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\GestionBundle\Entity\Fiche;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
class FicheType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder/*->add('cpn', ChoiceType::class, [
            'choices' => array_combine(range(1, 4), range(1, 4))
            ,  'expanded' => true
            , 'multiple' => false
            , 'label' => 'CPN'
        ])*/
        ->add('gestite', null, ['label' => 'Gestité'])
        ->add('traitement', null, ['label' => false,   'required' => false, 'empty_data' => '', 'attr' => ['rows' => 10]])
        ->add('parite', null, ['label' => 'Parité'])
        ->add('hgpo', CheckboxType::class)->add('typeHgpo', ChoiceType::class, [
            'choices' => ['Normale' => 'Normale', 'Pathologique' => 'Pathologique'],
            'expanded' => false,
            'required' => false,
            'multiple' => false
        ])
        ->add('ageGestationnel', null, ['label' => 'Age gestationnel (SA)'])
        ->add('constantes', CollectionType::class, [
                'entry_type'         => FicheConstanteType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required'     => false,
               
                'entry_options'      => ['label' => false]]
            )->add('gcs', CollectionType::class, [
                'entry_type'         => FicheGcType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required'     => false,
               
                'entry_options'      => ['label' => false]]
            )->add('traitements', CollectionType::class, [
                'entry_type'         => FicheTraitementType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required'     => false,
               
                'entry_options'      => ['label' => false]]
            )->add('glycemies', CollectionType::class, [
                'entry_type'         => FicheGlycemieType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required'     => false,
               
                'entry_options'      => ['label' => false]]
            )->add('antecedents', CollectionType::class, [
                'entry_type'         => FicheAntecedentType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required'     => false,
               
                'entry_options'      => ['label' => false]]
            )
            ->add('complications', CollectionType::class, [
                'entry_type'         => FicheComplicationType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                //'required'     => false,
               
                'entry_options'      => ['label' => false]]
            )
            ->add('analyses', CollectionType::class, [
                'entry_type'         => FicheAnalyseType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required'     => false,
               
                'entry_options'      => ['label' => false]]
            )->add('traitements', CollectionType::class, [
                'entry_type'         => FicheTraitementType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required'     => false,
               
                'entry_options'      => ['label' => false]]
            );

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fiche::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_fiche';
    }


}
