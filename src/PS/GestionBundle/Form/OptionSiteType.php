<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\GestionBundle\Entity\OptionSite;
use PS\ParametreBundle\Entity\ListeOption;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class OptionSiteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('option', EntityType::class, [
            'class' => ListeOption::class,
            'choice_label' => 'libelle',
            'label' => 'Option'
        ])->add('valeur', null, ['label' => 'Valeur']);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OptionSite::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_optionsite';
    }


}
