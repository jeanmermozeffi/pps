<?php

namespace PS\GestionBundle\Form;

use PS\ParametreBundle\Entity\Pays;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Component\OptionsResolver\OptionsResolver;

class SiteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('logo', FileType::class, [
                'label' => 'Logo'
                , 'data_class' => null,

                'empty_data' => null

            ])
            ->add('url', UrlType::class, ['label' => 'Site WEB'])
            ->add('libelle', null, ['label' => 'Libellé'])
            ->add('statut', CheckboxType::class, ['label' => 'Activer le site', 'required' => false, 'empty_data' => false])

            ->add('pays', EntityType::class, [
                'class' => Pays::class
                , 'choice_label' => 'nom'
                , 'multiple' => true
                , 'label' => 'Pays'
                , 'attr' => ['class' => 'select2']
            ])
             ->add('options', CollectionType::class, [
                'entry_type'         => OptionSiteType::class,
                'label'        => false,
                //'constraints' => [new Valid()],
                //'error_bubbling' => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'required'     => true,
                'by_reference' => false,
                'entry_options'      => ['label' => false]]
            );

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            $formName = $form->getName();

            if (!isset($data['statut'])) {
                $form->add('statut', NumberType::class, ['data' => 0, 'empty_data' => 0]);
                $data['statut'] = 0;
            }

            $event->setData($data);
        });

        
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'PS\GestionBundle\Entity\Site',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_site';
    }

}
