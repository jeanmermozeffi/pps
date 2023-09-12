<?php

namespace PS\GestionBundle\Form;

use PS\GestionBundle\Form\EventListener\PatientEditListener;
use PS\ParametreBundle\Entity\Attribut;
use PS\ParametreBundle\Entity\Pays;
use PS\ParametreBundle\Entity\Nationalite;
use PS\ParametreBundle\Entity\Religion;
use PS\ParametreBundle\Entity\Statut;
use PS\ParametreBundle\Entity\Ville;
use PS\ParametreBundle\Entity\GroupeSanguin;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use PS\ParametreBundle\Entity\LienParente;
use PS\ParametreBundle\Form\PatientAffectionsType;
use PS\ParametreBundle\Form\PatientAllergiesType;
use PS\ParametreBundle\Form\LigneAssuranceType;
use PS\ParametreBundle\Form\PatientAssuranceType;
use PS\ParametreBundle\Form\PatientMedecinType;
use PS\ParametreBundle\Form\PatientVaccinType;
use PS\ParametreBundle\Form\TelephoneType;
use PS\ParametreBundle\Form\PatientTraitementsType;
use PS\UtilisateurBundle\Form\PersonneType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\UtilisateurBundle\Form\CompteAssocieType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\IsTrue;
use PS\ParametreBundle\Entity\Region;
use PS\GestionBundle\Entity\Patient;


class PatientType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entityManager = $options['entity_manager'];
        $isAssociated = $options['is_associated'];
        $builder
            ->add('personne', PersonneType::class, ['label' => true, 'date_format' => $options['date_format']])
            ->add('email', EmailType::class, ['label' => 'Votre Email', 'required' => false])
            // ->add('identifiant', TextType::class, ['label' => 'patient.form.identifiant', 'required' => false,])
            // ->add('pin', TextType::class, ['label' => 'patient.form.pin', 'required' => false,])
            ->add('poids', IntegerType::class, ['required' => false, 'label' => 'patient.form.poids'])
            ->add('taille', IntegerType::class, ['required' => false, 'label' => 'patient.form.taille'])
            ->add('nombreEnfant', IntegerType::class, ['required' => false, 'label' => 'patient.form.nombreEnfant'])
            //  ->add('ethnie', TextType::class, ['label' => 'patient.form.ethnie', 'required' => false,])
            ->add('matricule', TextType::class, ['label' => 'patient.form.matricule', 'required' => false, 'empty_data' => ''])
            ->add('regime', TextareaType::class, ['label' => 'patient.form.regime', 'required' => false,])
            
            ->add('adresse', TextareaType::class, ['required' => false, 'label' => 'patient.form.adresse'])
            ->add('lieuhabitation', TextareaType::class, ['label' => 'patient.form.lieuhabitation'])
            ->add('profession', TextType::class, ['required' => false, 'label' => 'patient.form.profession'])
            ->add('societe', null, ['label' => 'patient.form.societe'])
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    "F" => "patient.form.sexe.female.label"
                    , "M" => "patient.form.sexe.male.label"
                ]   , 'label' => 'patient.form.sexe.label'
                , 'attr' => ['class' => 'select2']
            ])
            ->add('groupeSanguin', EntityType::class, [
                'class' => GroupeSanguin::class,
                'label' => 'groupe sanguin',
                'choice_label'                                         => 'libelle',
                'placeholder'                                      => "---",
                'attr' => ['class' => 'select2'],
                'required'                                         => false,
                'empty_data'                                       => null]
            )
            ->add('statut', EntityType::class, [
                    'class' => Statut::class,
                    'attr' => ['class' => 'select2'],
                    'label' => 'patient.form.statut',
                    'choice_label'                                         => 'libelle',
                    'placeholder'                                      => "---",
                    'required'                                         => false,
                ]
            )
            /*->add('familiaux', CollectionType::class, [
                'entry_type'         => PatientAntecedentType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'mapped' => false,
                'entry_options'      => ['label' => false, 'type' => 'familial'],
            ])
            /*->add('ville', EntityType::class, ['class' => Ville::class,
                'choice_label'                                 => 'nom',
                'placeholder'                              => "--- Choisir votre ville ---",
                'data' => $entityManager->getReference(Ville::class, 1),
                'required'                                 => false,
                'empty_data'                               => null]
            )*/

            ->add('pays', EntityType::class, [
                'class' => Pays::class,
                'choice_label'                                => 'nom',
                'label' => 'patient.form.pays',
                'attr' => ['class' => 'select2'],
                //'data' => $entityManager->getReference(Pays::class, 1),
                'placeholder'                             => "---",
                'required'                                => true,
                'empty_data'                              => null]
            )
             ->add('religion', EntityType::class, [
                 'class' => Religion::class,
                'label' => 'patient.form.religion',
                'attr' => ['class' => 'select2'],
                'choice_label'                                => 'nom',
                'placeholder'                             => "----",
                'required'                                => false,
                'empty_data'                              => null]
            )
              ->add('region', EntityType::class, [
                'class' => Region::class,
                'choice_label'                                => 'nom',
                'label' => 'patient.form.region',
                'attr' => ['class' => 'select2'],
                //'data' => $entityManager->getReference(Pays::class, 1),
                'placeholder'                             => "----",
                'required'                                => true,
                'empty_data'                              => null]
            )
             ->add('nationalite', EntityType::class, [
                 'class' => Nationalite::class,
                'label' => 'patient.form.nationalite',
                'attr' => ['class' => 'select2'],
                'choice_label'                                => 'libNationalite',
                'placeholder'                             => "----",
                'required'                                => false,
                'empty_data'                              => null]
            )

            ->add('attribut', EntityType::class, [
                'multiple'     => true,
                //'mapped' => false,
                'choice_label' => 'libAttribut',
                'expanded'     => true,
                'class'        => Attribut::class,
                //'entry_options'      => ['label' =>. false]
            ]
            )
             ->add('associes', CollectionType::class, [
                'entry_type'         => CompteAssocieType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                //'required'     => false,
               
                'entry_options'      => ['label' => false, 'em' => $options['entity_manager']]]
            )
            
            ->add('medecins', CollectionType::class, [
                'entry_type'         => PatientMedecinType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                //'required'     => false,
                'entry_options'      => ['label' => false]]
            )
            ->add('affections', CollectionType::class, [
                'entry_type'         => PatientAffectionsType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                //'required'     => false,
                'entry_options'      => ['label' => false]]
            )
            ->add('allergies', CollectionType::class, [
                'entry_type'         => PatientAllergiesType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                //'required'     => false,
                'by_reference' => false,
                'entry_options'      => ['label' => false]]
            )

            ->add('ligneAssurances', CollectionType::class, [
                'entry_type'         => LigneAssuranceType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                //'required'     => false,
                'by_reference' => false,
                'entry_options'      => ['label' => false]]
            )
            ->add('telephones', CollectionType::class, [
                'entry_type'         => TelephoneType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required'     => false,
                'entry_options'      => ['label' => false]]
            )
            ->add('vaccinations', CollectionType::class, [
                'entry_type'         => PatientVaccinType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                //'required'     => false,
                'by_reference' => false,
                'entry_options'      => ['label' => false]]
            )
            ->add('traitements', CollectionType::class, [
                'entry_type'         => PatientTraitementsType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                //'required'     => false,
                'by_reference' => false,
                'entry_options'      => ['label' => false]]
            )->add('condition', CheckboxType::class, [
            'mapped'     => false
            , 'required' => false
            , 'label' => 'patient.form.condition'
            , 'constraints' => [
                new IsTrue(['message' => 'Veuillez accepter nos conditions avant de continuer']),
            ],
            'block_name' => 'term_conditions',
        ]
        );



        $builder->add('resent_login', CheckboxType::class, [
            'mapped'     => false, 'label' => 'patient.form.resent_login',
            'block_name' => 'resent_login',

        ]);

        if ($isAssociated) {
            $builder->add('lien', EntityType::class, [
                'class'        => LienParente::class,
                'label' => 'patient.form.lien',
                'choice_label' => 'libLienParente',
                'placeholder'  => "",
                'required'     => false,
                'empty_data'   => null,
                'mapped' => false,
                'constraints' => [new NotBlank(['message' => 'Veuillez choisir le lien de parenté'])]
            ]);
        }
        $builder->addEventSubscriber(new PatientEditListener($entityManager));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
            'user' => null,
            'date_format' => 'dd/MM/yyyy',
            'is_associated' => false
        ]);

        
        $resolver->setRequired('entity_manager');
        $resolver->setRequired('user');
        $resolver->setRequired('date_format');
        $resolver->setRequired('is_associated');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_patient';
    }
}
