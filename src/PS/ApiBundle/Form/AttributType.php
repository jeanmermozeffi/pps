<?php

namespace PS\ApiBundle\Form;

use PS\ParametreBundle\Entity\Attribut;
use PS\ParametreBundle\Entity\LigneAttribut;
use PS\GestionBundle\Entity\Patient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AttributType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('attribut', EntityType::class, [
            'multiple'     => true,
            //'mapped' => false,
            'choice_label' => 'libAttribut',
            'expanded'     => true,
            'class'        => Attribut::class,
            //'options'      => ['label' =>. false]
        ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
            'csrf_protection' => false
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_apibundle_attribut';
    }

}
