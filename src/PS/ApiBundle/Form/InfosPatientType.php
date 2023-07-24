<?php

namespace PS\ApiBundle\Form;

use PS\ApiBundle\Form\PersonneType;
use PS\ParametreBundle\Entity\Attribut;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InfosPatientType extends AbstractType
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
            ->add('nombreEnfant', 'number', ['required' => false])
             ->add('poids', IntegerType::class, ['required' => false])
            ->add('taille', IntegerType::class, ['required' => false])
            //->add('contact')
            ->add('adresse', 'textarea',['required' => false])
            ->add('lieuhabitation', 'textarea', ['required' => false])
            ->add('profession', 'text', ['required' => false])
            ->add('societe', 'text', ['required' => false])
            ->add('sexe', 'choice', array(
                'choices' => array("F" => "Féminin", "M" => "Masculin")
                , 'invalid_message' => 'Veuillez choisir votre sexe',
                'required' => false
                )
            )
             ->add('pays', EntityType::class, ['class' => 'ParametreBundle:Pays',
                'property'                                => 'nom',
                'empty_value'                             => "--- Choisir votre pays ---",
                'required'                                => false,
                'empty_data'                              => null]
            )
            ->add('groupeSanguin', EntityType::class, array(
                    'required' => false,
                    'class' => 'ParametreBundle:GroupeSanguin',
                    'property'     => 'libelle',
                    'empty_value'  => "--- Choisir votre groupe ---",
                    'empty_data'   => null,
                    'invalid_message' => 'Veuillez choisir un groupe sanguin dans la liste'
                )
            )
            ->add('ville', EntityType::class, array(
                    'required' => false,
                    'class' => 'ParametreBundle:Ville',
                    'property'     => 'nom',
                    'empty_value'  => "--- Choisir votre ville ---",
                    'empty_data'   => null,
                    'invalid_message' => 'Veuillez choisir une ville'
                )
            )
             ->add('attribut', EntityType::class, [
                'multiple'     => true,
                //'mapped' => false,
                'choice_label' => 'libAttribut',
                'expanded'     => true,
                'class'        => Attribut::class,
                //'options'      => ['label' =>. false]
            ]
            );
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PS\GestionBundle\Entity\Patient',
            'csrf_protection' => false
        ));
    }

}
