<?php

namespace PS\UtilisateurBundle\Form;

use PS\GestionBundle\Entity\Corporate;
use PS\ParametreBundle\Form\ImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use PS\ParametreBundle\Entity\Pays;
use PS\GestionBundle\Entity\Site;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use PS\UtilisateurBundle\Entity\Personne;

class PersonneType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $corporate = $options['corporate'];

        //$builder
           

         /*$builder->add('site'
                , EntityType::class
                , [
                    
                    'class' => Site::class
                    , 'choice_label' => 'libelle'
                    , 'label' => 'Site'
                    , 'required' => false
                    , 'placeholder' => '',
                    'attr'  => [
                        'class' => 'select2 select2-site',
                    ],
                ]);*/
            

        if (!$corporate) {
            $builder->add('corporate'
                , EntityType::class
                , [
                    
                    'class' => Corporate::class
                    , 'choice_label' => 'raison_sociale'
                    , 'label' => 'personne.form.corporate'
                    , 'required' => false
                    , 'placeholder' => '',
                    'attr'  => [
                        'class' => 'select2 select2-corporate',
                    ],
                ]);
        } else {

        }

        $builder->add('nom', null, ['label' => 'personne.form.nom'])
            ->add('prenom', null, [ 'label' => 'personne.form.prenom'])
            //->add('datenaissance', BirthdayType::class, ['widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'label' => 'Date de naissance (01/01/2016)', 'required' => true])
           ->add('photo', ImageType::class, ['label' => false, 'required' => false])
            ->add('signature', FileType::class, [
                'required' => false
                , 'data_class' => null, 
                'label' => 'personne.form.signature'
                , 'constraints' => [
                    new File([
                        'maxSize' => '2M'
                        , 'mimeTypes' => ['image/jpeg', 'image/png'],
                    ]),
                ],
            ])
            ->add('contact', null, [
                'required' => false
                , 'label' => 'personne.form.contact',
                //'data' => $options['data']->getContact() ?? '+225'
            ]);
        $dateFormat = $options['date_format'];

        if ($dateFormat == 'api') {
            $props = ['datenaissance', DateTimeType::class, ['widget' => 'single_text', 'format' => 'MM/dd/yyyy',]];
        } else {
            $props = ['datenaissance', BirthdayType::class, [
                'widget' => 'single_text', 'format' => 'MM/dd/yyyy', 'label' => 'personne.form.datenaissance', 'required' => true, 'invalid_message' => 'Date de naissance invalide. Format attendu (01/01/1900)'
            ]];
        }

        $builder->add(...$props);

        $builder->get('datenaissance')->addModelTransformer(new CallbackTransformer(
            function ($dateObj) {

                if ($dateObj && $dateObj->format('Y') == '-0001') {
                    return null;
                }

                return $dateObj;
            },
            function ($dateString) {
                return $dateString;
            })
        );

        /*$builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $personne = $event->getData();
            $form     = $event->getForm();

            if (!$personne) {
                return;
            }

            if (isset($personne['datenaissance'])) {
                 $datenaissance = $personne['datenaissance'];
                //$associes = $personne['associes'];

                

                preg_match('#^(\d{1,2})([/-])(\d{1,2})\2(\d{2}){1,2}$#', $datenaissance, $matches);

                if (count($matches) == 5) {
                    [, $day, , $month, $year]  = $matches;
                    $day                       = str_pad($day, 2, 0, STR_PAD_LEFT);
                    $month                     = str_pad($month, 2, 0, STR_PAD_LEFT);
                    $year                      = str_pad($month, 4, 19, STR_PAD_LEFT);
                    $personne['datenaissance'] = $day . '/' . $month . '/' . $year;
                }
            }

           

            $event->setData($personne);

        });*/
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Personne::class,
            'corporate'  => null,
            'date_format' => 'MM/dd/yyyy'
            //'cascade_validation' => true
        ]);

        $resolver->setRequired('corporate');
        $resolver->setRequired('date_format');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_utilisateurbundle_personne';
    }

}
