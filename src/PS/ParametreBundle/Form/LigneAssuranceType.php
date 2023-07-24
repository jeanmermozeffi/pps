<?php

namespace PS\ParametreBundle\Form;

use Symfony\Component\Form\AbstractType;
use PS\ParametreBundle\Entity\Assurance;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class LigneAssuranceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('taux', NumberType::class, ['empty_data' => '0'])
            ->add('numero', null,[
                'label'=>'ligneassurance.form.numero'
            ])
            ->add('assurance'
                , EntityType::class
                , [
                    'class' => Assurance::class,
                    'property'                                      => 'nom',
                    'empty_value'                                   => "--- ligneassurance.form.empty_value ---",
                    'required'                                      => true,
                    'empty_data'                                    => null
                ]
            );
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PS\ParametreBundle\Entity\LigneAssurance'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_parametrebundle_ligneassurance';
    }


}
