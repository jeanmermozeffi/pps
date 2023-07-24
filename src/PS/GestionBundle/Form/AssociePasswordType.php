<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PS\GestionBundle\Form;

use PS\UtilisateurBundle\Validator\Constraints\PasswordStrength;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AssociePasswordType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('plainPassword', 'repeated', [
            'type'            => 'password',
            'options'         => ['translation_domain' => 'FOSUserBundle'],
            'first_options'   => [
                'attr'  => [
                    'class' => 'validate-password',
                ],
                'label' => 'form.new_password'
                , 'constraints' => [
                    new PasswordStrength(),
                ]],
            'second_options'  => ['label' => 'form.new_password_confirmation'],
            'invalid_message' => 'fos_user.password.mismatch',
        ]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            //'intention'  => 'change_password',
        ]);
    }

    public function getName()
    {
        return 'app_compte_associe_change_password';
    }
}
