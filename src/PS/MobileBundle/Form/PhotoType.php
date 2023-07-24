<?php

namespace PS\MobileBundle\Form;

use PS\GestionBundle\Entity\Corporate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use PS\ParametreBundle\Entity\Image;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class PhotoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['patient']) {
            /* $builder->add('identifiant', null, [
                'label' => ' Identifiant'
                , 'mapped' => false
                ,  'constraints' => [
             
                    new NotBlank(['message' => 'Veuillez renseigner un ID'])
                ]
            ]);
            $builder->add('pin', null, [
                'label' => 'PIN' 
                , 'mapped' => false
                ,  'constraints' => [
                    new NotBlank(['message' => 'Veuillez renseigner un ID'])
                ]
            ]);*/
            
            $builder->add('matricule', null, [
                'label' => 'MATRICULE / Identifiant',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez renseigner un MATRICULE', 'groups' => ['Matricule']])
                ]
            ]);

            $builder->add('nom', null, [
                'label' => 'Nom et Prénoms * (Veuillez utiliser le nom et le prénom complets)',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez renseigner le nom et/ou le prénom', 'groups' => ['Nom']])
                ]
            ]);

            $builder->add('corporate', EntityType::class, [
                'class' => Corporate::class,
                'label' => 'Corporate',
                'required' => false,
                'placeholder' => '---',
                'choice_label' => 'raisonSociale',
                'mapped' => false,
            ]);

            $builder->add('filter', HiddenType::class, ['data' => $options['view'], 'mapped' => false]);
        }
        $builder
            ->add('file', FileType::class, [
                'label' => 'Photo',
                'attr' => [
                    'accept' => 'image/jpg,image/jpeg,image/png'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '5M', 'mimeTypes' => ['image/jpeg', 'image/png'],
                    ]),
                    new NotBlank(['message' => 'Veuillez sélectionner une photo'])
                ]
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Image::class,
            'csrf_protection' => false,
            'patient' => false,
            'view' => null
        ));

        $resolver->setRequired('patient');
        $resolver->setRequired('view');
    }
}
