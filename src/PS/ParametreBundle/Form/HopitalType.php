<?php

namespace PS\ParametreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\ParametreBundle\Entity\Specialite;
use PS\ParametreBundle\Entity\Ville;
use PS\ParametreBundle\Entity\Pays;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use PS\ParametreBundle\Entity\Assurance;
use PS\ParametreBundle\Entity\Hopital;

class HopitalType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = null;
        $builder
            ->add('nom', null, [
                'label'=>'hopital.form.nom'
            ])
            ->add('ville',EntityType::class,array('class' => Ville::class,
                                        'choice_label' => 'nom',
                                        'required' => true )
            )
            ->add('pays',EntityType::class,array('class' => Pays::class,
                                        'choice_label' => 'nom',
                                        'required' => true )
            )
            ->add('info', InfoHopitalType::class, ['label' => false, 'required' => false])
            ->add('assurances', EntityType::class, [
                'class' => Assurance::class
                , 'choice_label' => 'nom'
                , 'placeholder' => 'Choisir une assurance'
                , 'attr' => ['class' => 'select2 select2-assurance']
                , 'expanded'          => false
                , 'multiple'          => true
                , 'required' => false

                //'data' => $this->em->getReference(Hopital::class, 3)

            ])
            ->add('specialites', EntityType::class, [
                    'class' => Specialite::class
                    , 'choice_label' => 'nom'
                    /*,'query_builder' => function (EntityRepository $e) use($user) {
                        return $e->createQueryBuilder('a');
                    }*/
                    //, 'mapped' => false
                    , 'placeholder' => 'Choisir une spécialité'
                    , 'attr' => ['class' => 'select2 select2-specialite'],
                    'expanded'          => false,
                    'multiple'          => true,
                    'required' => false

                    //'data' => $this->em->getReference(Hopital::class, 3)

                ]);
        //S @nt3m0uss0
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Hopital::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_parametrebundle_hopital';
    }


}
