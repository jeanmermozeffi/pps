<?php

namespace PS\ParametreBundle\Form;

use PS\ParametreBundle\Entity\PatientMedecin;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use  PS\ParametreBundle\Form\SpecialiteType;
use  PS\ParametreBundle\Entity\Specialite;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;

class PatientMedecinType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('medecin',null,[
                'label'=>'patientmedecin.form.medecin'
            ])
            ->add('contact',null,[
                'label'=> 'patientmedecin.form.contact'
            ])
            ->add('specialite', EntityType::class, array('class' => Specialite::class, 'choice_label' => 'nom'))
            ->add('hopital', TextareaType::class, [
                'label'=> 'patientmedecin.form.hopital'
            ]);


            $builder->get('hopital')->addModelTransformer(new CallbackTransformer(
                function ($data) {
                    return $data;
                },
                function ($data) {
                    return is_null($data) ? 'N/A' : $data;
                }
            ));


            $builder->get('contact')->addModelTransformer(new CallbackTransformer(
                function ($data) {
                    return $data;
                },
                function ($data) {
                    return is_null($data) ? 'N/A' : $data;
                }
            ));

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => PatientMedecin::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_parametrebundle_patientmedecin';
    }


}
