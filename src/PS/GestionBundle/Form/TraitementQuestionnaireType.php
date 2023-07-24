<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\GestionBundle\Entity\TraitementQuestionnaire;
use PS\GestionBundle\Entity\DiagnosticQuestionnaire;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TraitementQuestionnaireType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$types = array_combine(TraitementQuestionnaire::OPTIONS, TraitementQuestionnaire::OPTIONS);
        $builder
        ->add('diagnostic', EntityType::class, [
            'class' => DiagnosticQuestionnaire::class, 
            'label' => 'Hypothèse',

            'choice_label' => 'libelle',
            'query_builder' => function ($e) use($options) {
                return $e->createQueryBuilder('a')->andWhere('a.questionnaire = :questionnaire')->setParameter('questionnaire', $options['questionnaire']);
            },        
            'expanded' => true
        ])
        ->add('info', TextareaType::class, ['attr' => ['rows' => 10], 'required' => false, 'label' => 'Autres recommendations']);

         $builder->get('info')->addModelTransformer(new CallbackTransformer(
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
            'data_class' => TraitementQuestionnaire::class
        ]);

        $resolver->setRequired('questionnaire');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_traitementquestionnaire';
    }


}
