<?php

namespace PS\ParametreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VilleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',null,[
                'label'=>'ville.form.nom'
            ])
            ->add('region', "entity", array('class'=>'ParametreBundle:Region',
                                            'property' => 'nom',
                                            'empty_value' => "ville.form.region",
                                            'empty_data' => null))
            ->add('pays', 'entity', array('class'=>'ParametreBundle:Pays',
                                            'property' => 'nom',
                                            'empty_value' => "ville.form.pays",
                                            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PS\ParametreBundle\Entity\Ville'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_parametrebundle_ville';
    }


}
