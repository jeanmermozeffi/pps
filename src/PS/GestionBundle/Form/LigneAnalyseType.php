<?php

namespace PS\GestionBundle\Form;

use PS\ParametreBundle\Form\AffectionType;
use PS\ParametreBundle\Form\AnalyseType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LigneAnalyseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', TextareaType::class)
            ->add('analyse', EntityType::class, array(
                'class' => 'ParametreBundle:Analyse',
                'property' => 'nom',
                'empty_value' => "--- Choisir une analyse ---",
                'empty_data' => null,
                'attr' => array('class' => 'analyse' )));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PS\GestionBundle\Entity\LigneAnalyse'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_ligneanalyse';
    }


}
