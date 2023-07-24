<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\GestionBundle\Entity\Admission;
use PS\ParametreBundle\Entity\Prestation;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdmissionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('identifiant', null, ['mapped' => false, 'attr' => ['placeholder' => 'Identifiant', 'autocomplete' => 'off'], 'constraints' => [new NotBlank(['message' => 'Veuillez renseigner l\'ID'])]])
            ->add('pin', null, ['mapped' => false, 'attr' => ['placeholder' => 'PIN', 'autocomplete' => 'off'], 'constraints' => [new NotBlank(['message' => 'Veuillez renseigner le PIN'])]])
            ->add('prestation', EntityType::class, [
                'class'       => Prestation::class,
                'choice_label'    => 'libelle',

                
        

            ])->add('details', Textareatype::class, ['empty_data'  => '', 'label' => 'Détails complémentaires']);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Admission::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_admission';
    }


}
