<?php

namespace PS\UtilisateurBundle\Form;

use PS\ParametreBundle\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\UtilisateurBundle\Form\UtilisateurPersonnelType;
use PS\UtilisateurBundle\Form\RegistrationType;

class PersonnelType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder
            ->add('nom', null, array('empty_data' => ''))
            ->add('prenom', null, array('empty_data' => ''));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PS\UtilisateurBundle\Entity\Personne'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_utilisateurbundle_personne';
    }


}
