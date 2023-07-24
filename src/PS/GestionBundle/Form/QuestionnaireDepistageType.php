<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\GestionBundle\Entity\QuestionnaireDepistage;
use PS\ParametreBundle\Entity\Specialite;
use PS\ParametreBundle\Entity\Affection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
class QuestionnaireDepistageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('libelle', null, ['label' => 'Libellé'])
                ->add('introduction', null, ['label' => 'Introduction', 'attr' => ['rows' => 10, 'class' => 'has-editor']])
                ->add('conclusion', null, ['label' => 'Conclusion', 'required' => false, 'attr' => ['rows' => 10, 'class' => 'has-editor']])
                ->add('affection', EntityType::class, [
                    'class' => Affection::class
                    , 'choice_label' => 'nom',
                    'label' => 'Affection concernée',
                    'query_builder' => function ($e) {
                        return $e->createQueryBuilder('a')->andWhere('a.depistage = 1');
                    },
                    'attr' => ['class' => 'select2'],
                ])
                ->add('roles', ChoiceType::class, [
                'choices'           => array_flip([
                   
                    'ROLE_INFIRMIER'       => 'Infirmier',
                    'ROLE_MEDECIN'         => 'Medecin',
                   
                ]),
                'choices_as_values' => true,
                'expanded'          => false,
                'multiple'          => true
                , 'attr' => ['class' => 'select2'],
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
                    'required' => true,
                   

                    //'data' => $this->em->getReference(Hopital::class, 3)

                ])
                 ->add('diagnostics', CollectionType::class, [
                'entry_type'         => DiagnosticQuestionnaireType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                //'required'     => false,
               
                'entry_options'      => ['label' => 'Diagnostics Probables']
            ]);



                   $builder->get('conclusion')->addModelTransformer(new CallbackTransformer(
            function ($libelle) {
                return strval($libelle);

            },
            function ($libelle) {
                return strval($libelle);
            }
        ));

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => QuestionnaireDepistage::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_questionnairedepistage';
    }


}
