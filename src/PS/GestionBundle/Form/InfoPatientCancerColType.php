<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class InfoPatientCancerColType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('parite', IntegerType::class,[
            'label' => 'Parité :',
            'required' => false,
        ])
        ->add('gestilite', IntegerType::class,[
            'required' => false,
            'label' => 'Géstilité :'
            
        ])
        ->add('dateDepistageIva', DateType::class, [
            'required' => false,
            'label' => 'Date du dépistage IVA :',
            'widget' => 'single_text',
            'html5' => false,
            'attr' => ['class' => 'js-datepicker'],
            'format' => 'dd-MM-yyyy',
        ])
        ->add('ageRapportSexuel', IntegerType::class,[
            'label' => 'Âge du prémier rapport sexuel :'
        ])
        ->add('structureDepistage', StructureDepistageType::class, [
            'label'=>false
        ])
        ->add('statutTraitementIVH', StatutTraitementIVHType::class, [
            'label'=>false
        ])
        ->add('traitementCdip', TraitementCdipType::class, [
            'label' => false
        ])
        ->add('visiteCancer', VisiteCancerType::class, [
            'label' => false
        ])
        ->add('realisationIva', RealisationIvaType::class, [
            'label'=>false
        ])
        ->add('traitementCol', TraitementColType::class, [
            'label'=>false
        ])
        ->add('referenceCancerCol', ReferenceCancerColType::class, [
            'label'=>false
        ]);
        
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PS\GestionBundle\Entity\InfoPatientCancerCol'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_infopatientcancercol';
    }


}
