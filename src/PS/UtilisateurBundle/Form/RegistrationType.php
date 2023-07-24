<?php
// src/AppBundle/Form/RegistrationType.php

namespace PS\UtilisateurBundle\Form;

use PS\UtilisateurBundle\Validator\Constraints\Alphanumeric;
use PS\UtilisateurBundle\Validator\Constraints\PasswordStrength;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
class RegistrationType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('condition', CheckboxType::class, [
            'mapped'     => false
            , 'required' => false
            , 'label' => 'registration.form.condition'
            , 'constraints' => [
                new isTrue(['message' => 'registration.form.message']),
            ],
            'block_name' => 'term_conditions',
        ]
        )
         ->add('pass', CheckboxType::class, [
            'mapped'     => false
            , 'required' => false
            , 'label' => 'registration.form.pass'
        ]
        )
         ->add('contact', null, ['label' => 'registration.form.contact', 'mapped' => false])
            ->add('username'
                , null
                , [
                    'label' => 'form.username'
                    , 'translation_domain' => 'FOSUserBundle'
                    , 'constraints' => [new Alphanumeric()],
                ]
            )
            ->add('plainPassword', RepeatedType::class, [
                'type'            => PasswordType::class,
                'options'         => ['translation_domain' => 'FOSUserBundle'],
                'first_options'   => [
                    'label' => 'form.password'
                    , 'constraints' => [
                        new PasswordStrength(),
                    ]],
                'second_options'  => ['label' => 'form.password_confirmation'],
                'invalid_message' => 'fos_user.password.mismatch',
            ]);

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }

    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'app_user_registration';
    }
}
