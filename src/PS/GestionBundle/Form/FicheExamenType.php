<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\GestionBundle\Entity\FicheExamen;
use PS\ParametreBundle\Entity\ListeExamen;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\CallbackTransformer;
use PS\ParametreBundle\Entity\Constante;
class FicheExamenType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('valeur')
            ->add('examen', EntityType::class, [
                'class' => ListeExamen::class,
                'choice_label' => 'libelle',
                'choice_attr' => function ($e) {
                    return ['data-libelle' == $e->getLibelle()];
                },
                'query_builder' => function($e) use($options) {
                    return $e->createQueryBuilder('l')->andWhere('l.categorie = :categorie')->setParameter('categorie', $options['categorie']);
                }
            ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FicheExamen::class
        ]);

        $resolver->setRequired('categorie');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_ficheexamen';
    }


}
