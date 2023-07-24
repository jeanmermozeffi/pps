<?php

namespace PS\GestionBundle\Form;

use PS\ParametreBundle\Entity\Affection;
use PS\UtilisateurBundle\Form\PersonneType;
use PS\ParametreBundle\Form\LigneAssuranceType;
use PS\ParametreBundle\Form\LigneVaccinType;
use PS\ParametreBundle\Form\TelephoneType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PatientType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('personne', new PersonneType(), array('label' => false))
            ->add('identifiant')
            ->add('pin')
            ->add('nombreEnfant')
            ->add('contact')
            ->add('adresse', 'textarea')
            ->add('lieuhabitation', 'textarea')
            ->add('profession')
            ->add('societe')
            ->add('sexe', 'choice', array('choices' => array("F" => "Féminin", "M" => "Masculin")))
            ->add('groupeSanguin', 'entity', array('class' => 'ParametreBundle:GroupeSanguin',
                    'property' => 'libelle',
                    'empty_value' => "--- Choisir votre groupe ---",
                    'empty_data' => null)
            )
            ->add('ville', 'entity', array('class' => 'ParametreBundle:Ville',
                    'property' => 'nom',
                    'empty_value' => "--- Choisir votre ville ---",
                    'empty_data' => null)
            )
            ->add('affections', CollectionType::class, array(
                'type' => EntityType::class,
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'options' => array('label' => false,
                    'class' => Affection::class,
                    'property' => 'nom',
                    'empty_value' => "--- Choisir une affection ---",
                    'empty_data' => null)
            ))
            ->add('allergies', "collection", array(
                    'type' => EntityType::class,
                    'label' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'options' => array('label' => false,
                        'class' => 'ParametreBundle:Allergie',
                        'property' => 'nom',
                        'empty_value' => "--- Choisir une allergie ---",
                        'empty_data' => null))
            )
            ->add('assurances', "collection", array(
                    'type' => new LigneAssuranceType(),
                    'label'         => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'options'       => array('label' => false))
            )
            ->add('telephones', 'collection', array(
                    'type' => new TelephoneType(),
                    'label'         => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'options' => array('label' => false))
            )
            ->add('vaccinations', 'collection', array(
                    'type' => new LigneVaccinType(),
                    'label'         => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'options'       => array('label' => false))
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PS\GestionBundle\Entity\Patient'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_patient';
    }


}
