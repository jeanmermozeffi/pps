<?php

namespace PS\ParametreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\ParametreBundle\Entity\Categorie;
use PS\ParametreBundle\Entity\TypeCategorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CategorieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('libelle')->add('type', EntityType::class, [
                'class' => TypeCategorie::class
                , 'choice_label' => 'libelle'
                , 'placeholder' => 'Choisir le type'
                , 'attr' => ['class' => 'select2 select2-type']
                , 'expanded' => false
               
                //'data' => $this->em->getReference(Hopital::class, 3)

            ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_parametrebundle_categorie';
    }


}
