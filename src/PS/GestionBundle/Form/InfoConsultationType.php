<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use PS\ParametreBundle\Entity\Etat;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class InfoConsultationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('detailsInfo', TextareaType::class, ['label' => 'Détails', 'attr' => ['rows' => 8]])
            ->add('etat'
            , EntityType::class
            , [
                'label' => 'Etat',
                'class' => Etat::class,
                'choice_label' => 'libEtat',
                'expanded' => true,
                'query_builder' => function (EntityRepository $e) {
                    return $e->createQueryBuilder('a')->andWhere('a.typeEtat = 1');
                }
            ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PS\GestionBundle\Entity\InfoConsultation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_infoconsultation';
    }


}
