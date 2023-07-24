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

class TraitementType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('posologie', TextareaType::class)
            ->add('details', TextareaType::class)
            ->add('medicament', EntityType::class, array(
                'class' => 'ParametreBundle:Medicament',
                'empty_value' => "--- Choisir un médicament ---",
                'empty_data' => null,
                'property' => 'nom',
                'attr' => array('class' => 'medicament' )));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PS\GestionBundle\Entity\Traitement'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_traitement';
    }


}
