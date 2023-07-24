<?php

namespace PS\UtilisateurBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\UtilisateurBundle\Validator\Constraints\Alphanumeric;
use PS\UtilisateurBundle\EventListener\UpdateUserListener;
use PS\UtilisateurBundle\Validator\Constraints\PasswordStrength;

class ProfileType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entityManager = $options['entity_manager'];
        $builder
            //->add('personne')
            ->add('username', null, array(
                'label' => 'form.username'
                , 'translation_domain' => 'FOSUserBundle'
                , 'constraints' => array(new Alphanumeric())
            ))
            ->add('email')
            ->add('personne', PersonneType::class, ['required' => true])
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'fos_user.password.mismatch',
                'required' => $options['passwordRequired'],
                'first_options' => array(
                    'label' => false, 
                    'constraints' => array(new PasswordStrength())
                ),
                'second_options' => array('label' => false),
            ));
            

            $builder->addEventSubscriber(new UpdateUserListener($entityManager));
             //$resolver->setRequired('entity_manager');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PS\UtilisateurBundle\Entity\Utilisateur',
            'passwordRequired' => true,
            //'lockedRequired' => false,
        ));

         $resolver->setRequired('entity_manager');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_utilisateurbundle_utilisateur';
    }


}
