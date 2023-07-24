<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class VisiteCancerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('typeVisite', ChoiceType::class,[
            'placeholder' => false,
            'required' => false,
            'label'=>'Veuillez coché les type de visite réalisées',
            'choices'=>[
                1=>'IVA initial réalisée',
                2=>'Cryothèrapie reportée',
                3=>'Suivi un an rendez vous un an après cryothérapie ou LEEP lors de la visite passée (indépendamment du statut VIH)',
                4=> 'Dépistage de routine - Patient revu un an après été dépistage négatif lors de la prémière visite(indépendamment du statut VIH)',
                5=> 'LEEP(Uniquement pour les sites de référence) Indiqué la date en dessous',
            ],
            'expanded' => true, 
            'multiple' => true
        ])
        ->add('statutPostTraitement', ChoiceType::class,[
            'placeholder' => false,
            'required' => false,
            'label' => 'Complication post-traitement en relation avec:',
            'choices' => [
                1 => 'Cryothérapie',
                2 => 'LEEP',
                
            ],
            'expanded'=>true
        ])
        ->add('dateTraitementLeep', DateType::class,[
            'required' => false,
            'label'=>'Date du traitement LEEP',
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PS\GestionBundle\Entity\VisiteCancer'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_visitecancer';
    }


}
