<?php

namespace PS\UtilisateurBundle\Form;

use PS\UtilisateurBundle\Validator\Constraints\Alphanumeric;
use PS\UtilisateurBundle\Validator\Constraints\PasswordStrength;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\AbstractType;
use PS\UtilisateurBundle\Form\PersonnelType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class UtilisateurPersonnelType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            
            ->add('username'
                , null
                , [
                    'label' => 'form.username'
                    , 'translation_domain' => 'FOSUserBundle'
                    , 'constraints' => [
                        new NotBlank(),
                        new Alphanumeric([
                            'message' => 'utilisateurpersonne.form.username.message',
                            'emptyMessage' => 'utilisateurpersonne.form.username.emptyMessage'
                        ]),

                    ],

                ]
            )
           
            ->add('plainPassword',
                'repeated', [
                    'type'            => 'password',
                    'invalid_message' => 'utilisateurpersonne.form.username.invalid_message',
                    'required'        => $options['passwordRequired'],
                    'first_options'   => [
                        'label' => 'utilisateurpersonne.form.username.firtsoption'
                        , 'constraints' => [
                            new PasswordStrength(),
                        ]],
                    'second_options'  => ['label' => 'utilisateurpersonne.form.username.second_options'],
                ]
            );
            

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        
    }

    public function getParent()
    {
        return 'fos_user_registration';
    }

    

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_utilisateurbundle_utilisateur';
    }

}
