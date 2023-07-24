<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\GestionBundle\Entity\DiagnosticQuestionnaire;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;

class DiagnosticQuestionnaireType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('libelle', null, ['label' => false])
        ->add('stat', null, ['label' => 'Libellé Stat'])
        ->add('pourcentageMin', null, ['label' => false])
        ->add('pourcentageMax', null, ['label' => false]);


        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            $data['stat'] = $data['stat'] ?? $data['libelle'];
            
            $event->setData($data);
        });
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DiagnosticQuestionnaire::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_diagnosticquestionnaire';
    }


}
