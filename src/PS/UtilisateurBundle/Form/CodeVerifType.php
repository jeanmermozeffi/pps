<?php

namespace PS\UtilisateurBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CodeVerifType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('smsCode', null, ['attr' => ['placeholder' => 'codeverif.form.smsCode']]);
    }
    
    

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_utilisateurbundle_code';
    }

}
