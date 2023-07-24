<?php

namespace PS\UtilisateurBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('roles', 'collection', array('type' => 'choice',
                                                'allow_add'    => true,
                                                'allow_delete' => true,
                                                'options' => array('label' => false,
                                                    'empty_value' => '-- Choisir un groupe --',
                                                    'empty_data'  => null,
                                                    'choices' => array('ROLE_USER' => 'Patient',
                                                                        'ROLE_MEDECIN' => 'Medecin',
                                                                        'ROLE_ADMIN' => 'Administrateur',
                                                                        'ROLE_SUPER_ADMIN' => 'Super Administrateur',
                    ))));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PS\UtilisateurBundle\Entity\Groupe'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_utilisateurbundle_groupe';
    }


}
