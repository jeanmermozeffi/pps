<?php

namespace PS\GestionBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('identifiant')
            ->add('pin')
            ->add('nom')
            ->add('prenom')
            ->add('dateNaissance', DateType::class, array(
                'widget' => 'single_text',
            ))
            ->add('nombreEnfant')
            ->add('contact')
            ->add('adresse')
            ->add('lieuhabitation')
            ->add('profession')
            ->add('societe')
            ->add('sexe', ChoiceType::class, array(
                'choices'  => array(
                    'Homme' => 'H',
                    'Femme' => 'F',
                ),
                // *this line is important*
                'choices_as_values' => true,
                ))
            ->add('groupeSanguin', EntityType::class, array(
                'class'=>'ParametreBundle:GroupeSanguin',
                'property' => 'code',
                'empty_value' => "--- Choisir un groupe sanguin ---",
                'empty_data' => null
            ))
            ->add('ville', EntityType::class, array(
                'class'=>'ParametreBundle:Ville',
                'property' => 'nom',
                'empty_value' => "--- Choisir une ville ---",
                'empty_data' => null
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PS\GestionBundle\Entity\Patient'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_patient';
    }


}
