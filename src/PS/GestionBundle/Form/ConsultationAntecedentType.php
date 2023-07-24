<?php

namespace PS\GestionBundle\Form;

use Mpdf\Tag\TextArea;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\GestionBundle\Entity\ConsultationAntecedent;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use PS\ParametreBundle\Entity\ListeAntecedent;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ConsultationAntecedentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $signes = ListeAntecedent::TYPES;
         $groupes = ListeAntecedent::GROUPES;
        $builder
            ->add('antecedent', TextareaType::class, ['label' => false])
            /*->add('antecedent',EntityType::class, [
            'class' => ListeAntecedent::class, 'choice_label' => 'libelle', 'label' => false, 'placeholder' => '', 'attr' => ['class' => 'select2']])*/
        ->add('type', ChoiceType::class, ['choices' => array_combine($signes, $signes), 'data' => $options['type']])
        ->add('lien',TextareaType::class, ['label' => false, 'empty_data' => ''])
        /*->add('groupe', ChoiceType::class, ['choices' => array_combine($groupes, $groupes), 'label' => false])*/;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ConsultationAntecedent::class
        ]);

         $resolver->setRequired('type');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_consultationantecedent';
    }


}
