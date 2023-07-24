<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\GestionBundle\Entity\InfoHygieneVie;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Choice;

class InfoHygieneVieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('frequenceActPhysique', ChoiceType::class, [
                'label' => 'A quelle fréquence pratiquez-vous un sport ou une autre activité physique ? Par exemple de la marche, du vélo, du jogging, de la natation, de la gymnastique ou des tâches demandant un effort physique ?',
                'choices' => array_flip(InfoHygieneVie::FREQ_PHYSIQUES),
                'multiple' => false,
                'expanded' => true,
            
            ])
            ->add('natureConsommation', ChoiceType::class, [
                'label' => 'Consommez-vous suffisamment de ',
                'choices' => array_flip(InfoHygieneVie::NAT_CONSOMMATION),
                'multiple' => true,
                'expanded' => true,
            
            ])
            ->add('consommationAlcool', ChoiceType::class, [
                'label' => 'Votre consommation d\'alcool',
                'choices' => array_flip(InfoHygieneVie::FREQ_ALCOOL),
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('consommationTabac', ChoiceType::class, [
                'label' => 'Votre consommation de tabac',
                'choices' => array_flip(InfoHygieneVie::FREQ_TABAC),
                'multiple' => false,
                'expanded' => true,
            ])
            ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InfoHygieneVie::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_infohygienevie';
    }


}
