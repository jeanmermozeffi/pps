<?php
// src/AppBundle/Form/RegistrationType.php

namespace PS\ApiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**
         * @Assert\Blank(groups={"registration"})
         */
        $builder->add('condition','checkbox', array(
                'mapped' => false
                , 'required' => false
                , 'label' => 'J\'accepte les <a href="#" data-toggle="modal" data-target="#conditions">Termes et conditions</a>'
                , 'constraints' => array(
                    new isTrue(array('message' => 'Veuillez accepter nos conditions avant de continuer'))
                ),
                'block_name' => 'term_conditions'
             )
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

    public function getName()
    {
        return 'app_user_rest_registration';
    }
}