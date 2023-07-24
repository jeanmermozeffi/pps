<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\GestionBundle\Entity\LigneExamen;
use PS\ParametreBundle\Entity\TypeExamen;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class LigneExamenType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('etat', CheckboxType::class, [
           
             'required' => false
            , 'label' => 'Terminé'
        ])
        ->add('details', null, ['label' => 'Détails', 'empty_data' => ''])
       
        ->add('diagnostic',  null, ['label' => 'Interprétation', 'empty_data' => ''])
        ->add('libelle', null, ['label' => 'Examen']);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LigneExamen::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_ligneexamen';
    }


}
