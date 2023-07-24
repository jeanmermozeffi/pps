<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\ParametreBundle\Entity\Commune;
use PS\ParametreBundle\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class InfoPharmacieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contacts')
            ->add('nomResponsable')
            ->add('localisationPharmacie', TextareaType::class, ['attr' => ['rows' => 5]])
            ->add('ville', EntityType::class, ['class' => Ville::class, 'choice_label' => 'nom'])
            ->add('commune', EntityType::class, ['class' => Commune::class, 'choice_label' => 'libCommune'])
             ->add('pointVente', CheckboxType::class, ['label' => 'Point de vente', 'required' => false, 'empty_data' => false]);


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
        $resolver->setDefaults(array(
            'data_class' => 'PS\GestionBundle\Entity\InfoPharmacie'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_infopharmacie';
    }


}
