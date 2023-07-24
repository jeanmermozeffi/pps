<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\GestionBundle\Entity\PatientAntecedent;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use PS\ParametreBundle\Entity\ListeAntecedent;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PatientAntecedentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $signes = ListeAntecedent::TYPES;
         $groupes = ListeAntecedent::GROUPES;
        $builder/*->add('antecedent',EntityType::class, [
            'class' => ListeAntecedent::class, 'choice_label' => 'libelle', 'label' => false, 'placeholder' => '', 'attr' => ['class' => 'select2']])*/
            ->add('antecedent', null, ['label' => false])
        ->add('type', ChoiceType::class, ['choices' => array_combine($signes, $signes), 'data' => $options['type']]);

        if ($options['type'] == 'familial') {
            $builder->add('lien', null, ['label' => false]);
        }
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PatientAntecedent::class
        ]);

         $resolver->setRequired('type');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_patientantecedent';
    }


}
