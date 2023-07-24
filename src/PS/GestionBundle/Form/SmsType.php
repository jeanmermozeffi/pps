<?php

namespace PS\GestionBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\IsTrue;

class SmsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contacts', TextareaType::class, ['constraints' => [
                        new NotBlank()
                    ]])
            ->add('limit', null, ['label' => 'Limite d\'envoi', 'required' => false])
            ->add('message'
                , TextareaType::class
                , [
                    'label' => 'Message (160 caractères)',
                    'required' => true,
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'max' => 160,
                            'maxMessage' => 'Le message ne doit pas excéder {{ limit }} caractères'
                        ])
                    ]
                ]
            )
            ->add('condition', CheckboxType::class, [
                'mapped'     => false
                , 'required' => false
                , 'label' => 'En cliquant, vous êtes sûr du contenu du SMS et de la validité des destinataires'
                , 'constraints' => [
                    new IsTrue(['message' => 'Veuillez accepter avant d\'envoyer les SMS']),
                ],
               
            ]
            );
    
            
    }


    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_sms';
    }


}
