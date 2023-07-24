<?php

namespace PS\ParametreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LigneVaccinType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', 'date', array('widget' => 'single_text', 'format' => 'dd/MM/yyyy'))
            ->add('rappel', 'date', array('widget' => 'single_text', 'format' => 'dd/MM/yyyy'))
            ->add('vaccin','entity',array('class' => 'ParametreBundle:Vaccin',
                'property' => 'nom',
                'empty_value' => "--- lignevaccin.form.empty_value ---",
                'empty_data' => null,
                'required' => true));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PS\ParametreBundle\Entity\LigneVaccin'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_parametrebundle_lignevaccin';
    }


}
