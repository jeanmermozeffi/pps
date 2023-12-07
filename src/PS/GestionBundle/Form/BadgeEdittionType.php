<?php

namespace PS\GestionBundle\Form;

use App\Entity\BadgeEdittion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BadgeEdittionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateImpression')
            ->add('statut')
            ->add('motif')
            ->add('patient')
            ->add('utilisateur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BadgeEdittion::class,
        ]);
    }
}
