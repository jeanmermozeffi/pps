<?php

namespace PS\ApiBundle\Form;

use PS\UtilisateurBundle\Validator\Constraints\PasswordStrength;
use PS\UtilisateurBundle\Validator\Constraints\Alphanumeric;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('username', null, [
                'constraints' => [
                    new Alphanumeric()
                ]
            ])
            ->add('email', 'email')
            ->add(
                'plainPassword'
                , 'repeated'
                , [
                    'type'            => 'password',
                    'invalid_message' => 'Les mots de passe doivent être identiques.',
                    'required'        => false,
                    'first_options'   => ['label' => 'Mot de passe', 'constraints' => [
                        new PasswordStrength(),
                    ]],
                    'second_options'  => ['label' => 'Répétez'],
                ]
            );

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => 'PS\UtilisateurBundle\Entity\Utilisateur',
            'csrf_protection' => false,

            //'lockedRequired' => false,
        ]);
    }

}
