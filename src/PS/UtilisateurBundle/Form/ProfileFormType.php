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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use PS\UtilisateurBundle\Entity\Utilisateur;
use PS\UtilisateurBundle\Validator\Constraints\Alphanumeric;
use PS\UtilisateurBundle\Validator\Constraints\PasswordStrength;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileFormType extends AbstractType
{
     /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$entityManager = $options['entity_manager'];
        $builder
            //->add('personne')
            ->add('username', null, array(
                'label' => 'form.username'
                , 'translation_domain' => 'FOSUserBundle'
                , 'constraints' => array(new Alphanumeric())
            ))
            ->add('email', EmailType::class, ['label' => 'form.email',  'translation_domain' => 'FOSUserBundle'])
            ->add('personne', PersonneType::class, ['required' => true])
            ->add('plainPassword', RepeatedType::class, array(
                'type' => 'password',
                'translation_domain' => 'FOSUserBundle',
                'invalid_message' => 'fos_user.password.mismatch',
                'required' => $options['passwordRequired'],
                'first_options' => array(
                    'label' => 'form.new_password', 
                    'constraints' => array(new PasswordStrength())
                ),
                'second_options' => array('label' => 'form.new_password_confirmation'),
            ));
            

            //$builder->addEventSubscriber(new UpdateUserListener($entityManager));
             //$resolver->setRequired('entity_manager');
    }


    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
            'passwordRequired' => true,
            //'lockedRequired' => false,
        ]);

         $resolver->setRequired('passwordRequired');
    }


    public function getName()
    {
        return 'app_user_profile_edit';
    }

    
}
