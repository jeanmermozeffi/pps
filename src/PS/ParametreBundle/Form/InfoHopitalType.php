<?php

namespace PS\ParametreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\GestionBundle\Form\Type\EmailTypeEmptyDataExtension;

class InfoHopitalType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('contacts',null, [
            'label'=>'infohopital.form.contacts'
        ])
            //->add('fax', null, ['required' => false])
            ->add('emailHopital', EmailTypeEmptyDataExtension::class, ['empty_data' => ''])
            ->add('localisationHopital', TextareaType::class, [
                'label'=> 'infohopital.form.localisationHopital'
            ])
            ->add('nomResponsable', null,[
            'label' => 'infohopital.form.nomResponsable'
            ])
            ->add('logoHopital', FileType::class, [
            'label' => 'infohopital.form.logoHopital'
                , 'data_class' => null,

            ])
            ->add('pointVente', CheckboxType::class, ['label' => 'infohopital.form.pointVente', 'required' => false, 'empty_data' => false]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            $formName = $form->getName();

            if (!isset($data['pointVente'])) {
                $form->add('pointVente', NumberType::class, ['data' => 0, 'empty_data' => 0]);
                $data['pointVente'] = 0;
            }

            $event->setData($data);
        });

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'PS\ParametreBundle\Entity\InfoHopital',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_parametrebundle_infohopital';
    }

}
