<?php

namespace PS\ApiBundle\Form;

use PS\ApiBundle\Form\PersonneMedecinType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InfosMedecinType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $builder
            
            ->add('personne', new PersonneMedecinType(), array('label' => false));
            
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PS\GestionBundle\Entity\Medecin',
            'csrf_protection' => false
        ));
    }

}
