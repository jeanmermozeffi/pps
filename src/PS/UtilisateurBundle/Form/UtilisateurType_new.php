<?php

namespace PS\UtilisateurBundle\Form;

use FOS\UserBundle\Form\Type\RegistrationFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roles', 'collection', array(
                'type' => 'choice',
                'allow_add'     => false,
                'allow_delete'  => true,
                'by_reference'  => false,
                'options'       => array(
                    'label' => false,
                    'required' => false,
                    'multiple' => false,
                    'choices' => array(
                        'ROLE_CUSTOMER' => 'Patient',
                        'ROLE_MEDECIN' => 'Medecin',
                        'ROLE_ADMIN' => 'Administrateur',
                        'ROLE_SUPER_ADMIN' => 'Super Administrateur',
                    )
                )
            ))
            ->add('enabled', CheckboxType::class, array(
                'label' => 'Activer',
                'required' => false,
            ))
            ->add('locked', CheckboxType::class, array(
                'label' => 'Vérrouiller',
                'required' => false,
            ));;
    }

    public function getParent()
    {
        return RegistrationFormType::class;
    }
}
