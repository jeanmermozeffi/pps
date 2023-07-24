<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\GestionBundle\Entity\FicheDepistageCancer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class FicheDepistageCancerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('taille', IntegerType::class, [
                'label' => 'Taille',
                'required'=> false,
                'empty_data' => '0'
            ])
            ->add('poids', NumberType::class, [
                'label' => 'Poids',
                'required' => false,
                'empty_data' => '0'
            ])
            ->add('autoExamen', CheckboxType::class, ['required' => false, 'label' => 'Effectuez-vous un auto-examen de vos seins au moins une fois par mois ?'])
            ->add('controleExamenMedecin', CheckboxType::class, ['required' => false,'label' => 'Vous rendez-vous régulièrement chez votre médecin pour un contrôle qui inclut également un examen des seins ?'])
            ->add('dateDerniereMamno', DateType::class, [
                'widget'   => 'single_text',
                'format'   => 'dd-MM-yyyy',
                'html5'    => false,
                'required' => false,
                'attr'     => ['class' => 'datepicker'],
               
                'label' => 'Si vous êtes âgée de 45 ans ou plus : pesez-vous une mammographie tous les deux ans ? Si oui, renseignez la date'
            ])
            ->add('facteurRisquecancer', FacteurRisqueCancerType::class, ['label_attr' => ['class' => 'label-title'], 'label' => 'Cancer du sein – Quels sont les principaux facteurs de risque ?'])
            ->add('infoHygieneVie', InfoHygieneVieType::class, ['label_attr' => ['class' => 'label-title'],'label' => 'Votre Hygiène de vie'])
            ->add('examenPhysiqueDepCancer', ExamenPhysiqueDepCancerType::class, ['label_attr' => ['class' => 'label-title'],'label' => 'Examen Physique']);



            $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use($options) {
                $data = $event->getData();
    
                 /** @var FormInterface */
                $form = $event->getForm();
    
                if (!isset($data['autoExamen'])) {
                    $form->add('autoExamen', NumberType::class, ['data' => 0, 'empty_data' => 0]);
                    $data['autoExamen'] = 0;
                }
    
    
                if (!isset($data['controleExamenMedecin'])) {
                    $form->add('controleExamenMedecin', NumberType::class, ['data' => 0, 'empty_data' => 0]);
                    $data['controleExamenMedecin'] = 0;
                }
        
        
                $event->setData($data);
              
               
                
            });
           
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FicheDepistageCancer::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_fichedepistagecancer';
    }


}
