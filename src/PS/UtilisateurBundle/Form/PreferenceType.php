<?php

namespace PS\UtilisateurBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PreferenceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('libPreference',null,[
            'label'=>'preference.form.libPreference'
        ])
        ->add('aliasPreference', null, [
            'label' => 'preference.form.aliasPreference'
        ])
        ->add('descPreference', null, [
            'label' => 'preference.form.descPreference'
        ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PS\UtilisateurBundle\Entity\Preference'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_utilisateurbundle_preference';
    }


}
