<?php

namespace PS\SpecialiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\SpecialiteBundle\Entity\Etape;
use PS\ParametreBundle\Entity\Specialite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class EtapeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $id = $options['id'];
        $builder->add('libEtape')
            ->add('etapeParente'
                , EntityType::class
                , [
                    'required' => false
                    , 'class' => Etape::class
                    , 'property' => 'libEtape'
                    , 'empty_value' => '--------'
                    , 'query_builder' => function (EntityRepository $em) use($id) {
                        $qb = $em->createQueryBuilder('u');
                        return $id ? $qb->andWhere('u.id <> :id')->setParameter('id', $id) : $qb;
                    }
                ]
            )
            ->add('specialite'
                , EntityType::class
                , [
                    'required' => true
                    , 'class' => Specialite::class
                    , 'property' => 'nom'
                    , 'empty_value' => '--------'
                ]
            );
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Etape::class
            , 'id' => null
        ));

        $resolver->setRequired('id');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_specialitebundle_etape';
    }


}
