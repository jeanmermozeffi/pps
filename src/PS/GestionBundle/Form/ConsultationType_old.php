<?php

namespace PS\GestionBundle\Form;

use PS\GestionBundle\Entity\Patient;
use PS\ParametreBundle\Entity\Affection;
use PS\ParametreBundle\Form\AffectionType;
use PS\GestionBundle\Form\MedecinType;
use PS\UtilisateurBundle\Form\PersonneType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConsultationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateConsultation', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'html5' => false,
                'attr' => ['class' => 'datepicker'],
                'disabled' => true
            ))
            ->add('motif', TextareaType::class)
            ->add('diagnostique', TextareaType::class)
            ->add('poids')
            ->add('temperature')
            ->add('tension')
            //->add('patient', PatientType::class)
            ->add('specialite', EntityType::class, array(
                'class'=>'ParametreBundle:Specialite',
                'property' => 'nom',
                'empty_value' => "--- Choisir une specialité ---",
                'empty_data' => null
            ))/*
            ->add('affection', EntityType::class, array(
                'class'=>'ParametreBundle:Affection',
                'property' => 'nom',
                'empty_value' => "--- Choisir une affection ---",
                'empty_data' => null,
                'attr' => ['multiple' => 'datepicker']
            ))*/
            //->add('medecin', MedecinType::class)

            ->add('hospitalisation', TextareaType::class)

            ->add('affections', CollectionType::class, array(
                'type'    => EntityType::class,
                'label'   => false,
                'allow_add'     => true,
                'allow_delete'  => true,
                'by_reference'  => false,
                'options'       => array('label' => false,
                    'class'=>Affection::class,'property' => 'nom')
            ))
            ->add('analyses',CollectionType::class, array(
                'type'          => new LigneAnalyseType(),
                'label'         => false,
                'allow_add'     => true,
                'allow_delete'  => true,
                'by_reference'  => false,
                'options'       => array('label' => false)
            ))
            ->add('medicaments',CollectionType::class, array(
                'type'          => new TraitementType(),
                'label'         => false,
                'allow_add'     => true,
                'allow_delete'  => true,
                'by_reference'  => false,
                'options'       => array('label' => false)));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PS\GestionBundle\Entity\Consultation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_consultation';
    }


}
