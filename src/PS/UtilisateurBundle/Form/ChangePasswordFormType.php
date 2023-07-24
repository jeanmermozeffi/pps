<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PS\UtilisateurBundle\Form;

use PS\UtilisateurBundle\Validator\Constraints\PasswordStrength;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Security\Core\Validator\Constraint\UserPassword as OldUserPassword;

class ChangePasswordFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (class_exists('Symfony\Component\Security\Core\Validator\Constraints\UserPassword')) {
            $constraint = new UserPassword();
        } else {
            // Symfony 2.1 support with the old constraint class
            $constraint = new OldUserPassword();
        }

        $builder->add('current_password', 'password', [
            'label'              => 'form.current_password',
            'translation_domain' => 'FOSUserBundle',
            'mapped'             => false,
            'constraints'        => $constraint,
        ]);
        $builder->add('new', 'repeated', [
            'type'            => 'password',
            'options'         => ['translation_domain' => 'FOSUserBundle'],
            'first_options'   => [
                'label' => 'form.new_password'
                , 'constraints' => array(
                    new PasswordStrength()
            )],
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
            'data_class' => 'FOS\UserBundle\Form\Model\ChangePassword',
            'intention'  => 'change_password',
        ]);
    }

    public function getName()
    {
        return 'app_user_change_password';
    }
}
