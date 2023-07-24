<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class StatutTraitementIVHType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('dateTestArv', DateType::class, [
            'required' => false,
            'label' => 'Date du test',
            'widget' => 'single_text',
            'html5' => false,
            'attr' => ['class' => 'js-datepicker'],
            'format' => 'dd-MM-yyyy',
        ])
        ->add('statutIVH', ChoiceType::class,[
            'required' => false,
            'placeholder' => false,
            'label'=>' Statut HIV',
            'choices'=>[
               1=>'Positive',
                0=> 'Négative',
                -1 => 'Inconnu',
            ],
            'expanded'=>true,
            'multiple'=>false
        ])
        ->add('etatARV', ChoiceType::class,[
            'required' => false,
            'placeholder' => false,
            'label'=>'Pour un HIV positive, veuillez sélectionné le traitement',
            'choices' => [
                'Sous ARV' => 1,
                'Pas Sous ARV' => 0,
            ],
            'choices_as_values' => true,
            'expanded' => true,
            'multiple' => false

        ])
        ->add('raisonNonArv', TextareaType::class, [
            'label' => 'Raison du Statut HIV sans ARV',
            'required' => false,
        ])
        ->add('tempsTraitementArv', DateType::class,[
            'required' => false,
            'label' => 'Durée du traitement sous ARV',
            'widget' => 'single_text',
            'html5' => false,
            'attr' => ['class' => 'js-datepicker'],
            'format' => 'dd-MM-yyyy',
        ])
        
        ->add('dateTestNegative', DateType::class,[
            'required' => false,
            'label'=> 'Date du dernier Test Négative',
            'widget' => 'single_text',
            'html5' => false,
            'attr' => ['class' => 'js-datepicker'],
            'format' => 'dd-MM-yyyy',
        ])
        ->add('provenanceStatut', ChoiceType::class,[
            'required' => false,
            'label' => 'Provenance du statut HIV',
            'placeholder' => false,
            'choices' => [
                'Sans support dur' => 1,
                'Avec support dur' => 0,
            ],
            'choices_as_values' => true,
            'expanded' => true,
            'multiple' => false
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PS\GestionBundle\Entity\StatutTraitementIVH'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_statuttraitementivh';
    }


}
