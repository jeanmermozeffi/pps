<?php

namespace PS\ApiBundle\Form;

use PS\GestionBundle\Entity\RendezVous;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\CallbackTransformer;

class RendezVousType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('identifiant', TextType::class, ['label' => 'Identifiant du patient', 'required' => true, 'mapped' => false, 'constraints' => [new NotBlank(['message' => 'Veuillez renseigner l\'identifiant du patient'])]])
            ->add('pin', TextType::class, ['label' => 'Pin du patient', 'required' => true, 'mapped' => false, 'constraints' => [new NotBlank(['message' => 'Veuillez renseigner le pin du patient'])]])
            ->add('libRendezVous'
                , TextType::class,
                [
                    'label' => 'Libellé'
                    , 'required' => true
                    , 'constraints' => [
                        new NotBlank(['message' => 'Veuillez renseigner le libellé du RDV']),
                        new Length(['max' => 255, 'maxMessage' => 'Le libellé ne doit pas excéder {{ limit }} caractères'])
                    ]
                    , 'groups' => ['Default', 'new']
                ]
            )
            ->add('motifAnnulationRendezVous', null, ['required' => false])
            ->add('statutRendezVous', CheckBoxType::class, ['label' => 'Présence effective du patient', 'required' => false])
            ->add('dateRendezVous', null, ['widget' => 'single_text']);

         $builder->get('statutRendezVous')->addModelTransformer(new CallbackTransformer(
             function ($activeAsString) {

                if ($activeAsString == 2) {
                    $activeAsString = 0;
                }
                 // transform the string to boolean
                 return (bool)(int)$activeAsString;
             },
             function ($activeAsBoolean) {
                 // transform the boolean to string
                 return (string)(int)$activeAsBoolean;
             }
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'        => RendezVous::class,
            'csrf_protection'   => false,
            'validation_groups' => ['cancel-rdv'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_api_gestionbundle_rdv';
    }

}
