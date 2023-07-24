<?php

namespace PS\GestionBundle\Form;

use PS\GestionBundle\Model\Patient;
use PS\ParametreBundle\Form\LigneVaccinType;
use PS\ParametreBundle\Form\PatientVaccinType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ListePatientVaccinType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('vaccinations', CollectionType::class, [
            'entry_type'    => PatientVaccinType::class,
            'label'         => false,
            'constraints' => [new Assert\Valid(), new Assert\Count(['min' => 1, 'minMessage' => 'Veuillez renseigner au moins {{ limit }} vaccin'])],
            //'error_bubbling' => false,
            'allow_add'     => true,
            'allow_delete'  => true,
            'required'      => true,
            'by_reference'  => false,
            'entry_options' => ['label' => false, 'patient' => $options['patient']]]);



        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use($options) {
            $form = $event->getForm();
            $data = $event->getData();

            $patient = $options['patient'];


            $vaccinations = $data['vaccinations'] ?? [];
            

            $event->setData($data);

        });
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
        ]);

        $resolver->setRequired('patient');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_vaccin';
    }

}
