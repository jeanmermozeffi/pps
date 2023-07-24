<?php

namespace PS\MobileBundle\Form;

use PS\MobileBundle\DTO\ConsultationDTO;
use PS\GestionBundle\Entity\Corporate;
use PS\MobileBundle\DTO\MedecinDTO;
use PS\ParametreBundle\Entity\Hopital;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use PS\ParametreBundle\Entity\Image;
use PS\ParametreBundle\Entity\Specialite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class MedecinType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('specialites', EntityType::class, [
            'class' => Specialite::class,
            'choice_label' => 'nom',
            'multiple' => true
        ])
        ->add('hopital', EntityType::class, [
            'class' => Hopital::class,
            'choice_label' => 'nom'
        ])
        ->add('username')
        ->add('matricule')
        ->add('password')
        ->add('email', EmailType::class)
        ->add('nom')
        ->add('prenom')
        ->add('contact');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => MedecinDTO::class,
            'csrf_protection' => false,
           
            
        ));
    }
}
