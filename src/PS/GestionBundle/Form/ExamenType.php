<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\GestionBundle\Entity\Examen;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ExamenType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('dateResultat', DateType::class, [
                'widget'   => 'single_text',
                'format'   => 'dd-MM-yyyy',
                'html5'    => false,
                'empty_data' => date('d-m-Y'),
                'label' => 'Date prévue des résultats',
                'attr'     => ['class' => 'datepicker'],
                //'disabled' => true,
            ])
        ->add('lignes', CollectionType::class, [
                'entry_type'         => LigneExamenType::class,
                'label'        => false,
                //'constraints' => [new Valid()],
                //'error_bubbling' => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'required'     => true,
                'by_reference' => false,
                'entry_options'      => ['label' => false]]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Examen::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_examen';
    }


}
