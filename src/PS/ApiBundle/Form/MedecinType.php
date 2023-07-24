<?php

namespace PS\ApiBundle\Form;

use PS\ParametreBundle\Entity\Hopital;
use PS\ParametreBundle\Entity\Specialite;
use PS\UtilisateurBundle\Form\PersonneType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MedecinType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('personne', new PersonneType(), ['label' => false])
            ->add('hopital'
                , EntityType::class
                , [
                    'class' => Hopital::class
                    , 'property' => 'nom'
                ]
            );
           
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => 'PS\GestionBundle\Entity\Medecin',
            'csrf_protection' => false,
        ]);
    }

}
