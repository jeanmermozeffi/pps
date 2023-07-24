<?php

namespace PS\ParametreBundle\Form;

use PS\ParametreBundle\Entity\PatientVaccin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PatientVaccinType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['date_format'] !== 'api') {
            $builder
            ->add('date', DateType::class, ['widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'required' => false, 'attr' => ['autocomplete' => 'off', 'class' => 'datepicker']])
            ->add('rappel', DateType::class, ['required' => false, 'widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'attr' => ['autocomplete' => 'off', 'class' => 'datepicker']]);
        } else {
            $fields = ['date', 'rappel'];
            foreach ($fields as $field) {
                $builder->add($field, DateTimeType::class, ['widget' => 'single_text']);
            }

        }
        
        $builder->add('vaccin', null, ['required' => true])
            ->add('details', TextareaType::class, ['required' => false]);
            /*->add('dose', null, ['required' => false])
            ->add('lot', null, ['required' => false]);*/

        $builder->get('date')->addModelTransformer(new CallbackTransformer(
            function ($dateObj) {

                if ($dateObj && $dateObj->format('Y') == '-0001') {
                    return null;
                }

                return $dateObj;
            },
            function ($dateString) {
                return $dateString;
            })) ;



        

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PatientVaccin::class,
            'patient' => null,
            'date_format' => null
        ]);



        $resolver->setRequired('patient');
        $resolver->setRequired('date_format');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_parametrebundle_patientvaccin';
    }

}
