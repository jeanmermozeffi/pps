<?php

namespace PS\ParametreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use PS\ParametreBundle\Entity\PatientTraitement;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class PatientTraitementsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('visible', ChoiceType::class, [
                'choices'           => array_flip([
                    1        => 'Public',
                    0         => 'Privé',
                    
                ]),
                'choices_as_values' => true,
                'expanded'          => false,
                'multiple'          => false,
                'label' => 'Visibilité'
        ])->add('libelle', null, ['label' => false]);
         ;


        if ($options['date_format'] !== 'api') {
            $builder->add('dateDebut', DateType::class, ['widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'required' => false, 'attr' => ['autocomplete' => 'off', 'class' => 'datepicker']])
            ->add('dateFin', DateType::class, ['widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'attr' => ['autocomplete' => 'off', 'class' => 'datepicker']]);
        } else {
            $fields = ['dateDebut', 'dateFin'];
            foreach ($fields as $field) {
                $builder->add($field, DateTimeType::class, ['widget' => 'single_text']);
            }
        }
    }

     /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => PatientTraitement::class,
            'date_format' => null
        ));

        $resolver->setRequired('date_format');

    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_parametrebundle_patienttraitements';
    }


}
