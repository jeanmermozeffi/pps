<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\GestionBundle\Entity\Abonnement;
use PS\ParametreBundle\Entity\Pack;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class AbonnementType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('pack', EntityType::class, [
                
                //'mapped' => false,
                'choice_label' => 'libelle',
                //'expanded'     => true,
                'class'        => Pack::class
                , 'query_builder' => function (EntityRepository $e) {
                    $qb = $e->createQueryBuilder('p');

                    return $qb->andWhere($qb->expr()->in('p.alias', ['PACK_CARD', 'PACK_BRACELET']));
                },
                //'options'      => ['label' =>. false]
            ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Abonnement::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_abonnement';
    }


}
