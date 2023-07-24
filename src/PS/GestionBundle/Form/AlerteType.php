<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\GestionBundle\Entity\Alerte;
use PS\ParametreBundle\Entity\TypeAlerte;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use SC\DatetimepickerBundle\Form\Type\DatetimeType as CollotDateTime;
class AlerteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', null, ['label' => 'Libellé'])
            /*->add('dateDebut',CollotDateTime::class, [
                'widget'     => 'single_text',
                'format'     => 'dd/MM/yyyy',
                'html5'      => false,
                'empty_data' => date('d-m-Y'),
                'label'      => 'Date de début'
               
                , 'pickerOptions' => [
                    'format'             => $options['date_format'] ?? 'dd/mm/yyyy hh:ii',
                    'weekStart'          => 1,
                    //'startDate' => date('Y-m-d'), //example
                    //'endDate' => '01/01/3000', //example
                    'autoclose'          => true,
                    'startView'          => 'month',
                    'minView'            => 'hour',
                    'maxView'            => 'decade',
                    'todayBtn'           => true,
                    'todayHighlight'     => true,
                    'keyboardNavigation' => true,
                    'language'           => 'fr',
                    'forceParse'         => false,
                    'minuteStep'         => 5,
                    'pickerPosition'     => 'bottom-right',
                    'viewSelect'         => 'hour',
                    'showMeridian'       => false,
                ],
            ])
            ->add('dateFin', DateType::class, [
                'widget'     => 'single_text',
                'format'     => 'dd/MM/yyyy',
                'required' => false,
                'html5'      => false,
                'empty_data' => null,
                'label'      => 'Date de fin',
                'attr'       => ['class' => 'datepicker no-update'],
            ])
            ->add('frequence', IntegerType::class, [
                'label' => 'Fréquence (en jours)'
            ])*/
            ->add('type', EntityType::class, [
                'class' => TypeAlerte::class,
                'choice_label' => 'libelle',
                'label' => 'Type d\'alerte'
            ])
            ->add('commentaire', null, ['label' => 'Commentaire', 'required' => false, 'empty_data' => '']);
    }
    
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Alerte::class,
            'date_format' => null
        ]);

        $resolver->setRequired('date_format');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_alerte';
    }


}
