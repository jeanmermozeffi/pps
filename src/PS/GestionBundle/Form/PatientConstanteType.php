<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\GestionBundle\Entity\PatientConstante;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class PatientConstanteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $constante = $options['constante'];
        if (!$constante) {
            $builder->add('valeur', NumberType::class);
        } else {
            $builder->add('valeur', null, ['label' => $constante->getLibelle().' ('.$constante->getUnite().')']);
        }
        if ($options['date_format'] != 'api') {
            $builder->add('date', DateType::class, ['widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'label' => 'Date de la mesure', 'required' => true]);
        } else {
            $builder->add('date', DateTimeType::class, ['widget' => 'single_text']);
        }
        
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PatientConstante::class,
            'constante' => null,
            'date_format' => null,
        ]);

        $resolver->setRequired('constante');
        
       
        $resolver->setRequired('date_format');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_patientconstante';
    }


}
