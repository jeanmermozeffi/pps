<?php

namespace PS\GestionBundle\Form;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PS\GestionBundle\Entity\Consultation;
use PS\GestionBundle\Entity\Medecin;
use PS\GestionBundle\Entity\RendezVous;
use PS\ParametreBundle\Entity\Specialite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


class RendezVousType extends AbstractType
{

    /**
     * @var mixed
     */
    private $token;

    /**
     * @var mixed
     */
    private $em;

    private $auth;

    


    /**
     * @param TokenStorage $token
     */
    public function __construct(
        TokenStorageInterface $token, 
        EntityManagerInterface $em,
        AuthorizationCheckerInterface $auth
    )
    {
        $this->token = $token;
        $this->em    = $em;
         $this->auth    = $auth;
      
        
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->token->getToken()->getUser();
        $builder
            ->add('identifiant',
                TextType::class, [
                    'label' => 'rdv.form.identifiant'
                    , 'required' => true
                    , 'mapped' => false
                    , 'constraints' => [new NotBlank(['message' => 'rdv.identifiant'])]
                    , 'validation_groups' => ['new'],
                ]
            )
            ->add('pin'
                , TextType::class
                , [
                    'label' => 'rdv.form.pin'
                    , 'required' => true
                    , 'mapped' => false
                    , 'constraints' => [new NotBlank(['message' => 'rdv.pin'])]
                    , 'validation_groups' => ['new'],
                ]
            )
            ->add('specialite', EntityType::class, [
                'placeholder'  => 'rdv.form.placeholder.specialite',
                'choices' => $this->getSpecialites($user),
                //'multiple'     => true,
                'label' => 'rdv.form.specialite',
                'choice_label' => 'nom',
                'expanded'     => false,
                'class'        => Specialite::class,
            ])
            ->add('libRendezVous'
                , TextareaType::class,
                [
                    'label' => 'rdv.form.libRendezVous'
                    , 'required' => true
                    , 'constraints' => [
                        new NotBlank(['message' => 'rdv.libRendezVous.required']),
                        new Length(['max' => 255, 'maxMessage' => 'rdv.libRendezVous.max']),
                    ]
                    , 'validation_groups' => ['new', 'edit'],

                ]
            )
            ->add('statutRendezVous', CheckBoxType::class, ['label' => 'rdv.form.statutRendezVous', 'required' => false]);
        if ($options['date_format'] != 'api') {
            $builder->add(
                'dateRendezVous',
                'collot_datetime',
                [
                    'label' => 'rdv.form.dateRendezVous', 'widget' => 'single_text', 'attr' => ['autocomplete' => 'off'], 'validation_groups' => ['new', 'edit'], 'constraints' => [
                        new NotBlank(['message' => 'Veuillez renseigner la date du RDV']),
                        //new DateTimeAvailabilityConstraint()

                    ], 'pickerOptions' => [
                        'format'             => $options['date_format'] ? $options['date_format'] : 'dd/mm/yyyy hh:ii',
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
                ]
            );
        } else {
            $builder->add('dateRendezVous', DateTimeType::class, ['widget' => 'single_text']);
        }
            
        $builder->get('statutRendezVous')->addModelTransformer(new CallbackTransformer(
            function ($activeAsString) {

                if ($activeAsString == 2) {
                    $activeAsString = 0;
                }
                // transform the string to boolean
                return (bool) (int) $activeAsString;
            },
            function ($activeAsBoolean) {
                // transform the boolean to string
                return (string) (int) $activeAsBoolean;
            }
        ));

        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSubmit']);

        //$builder->addEventSubscriber(new AddFieldRendezVousSubscriber($this->token));

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($user) {

            $form = $event->getForm();
            $data = $event->getData();

            //dump($data);exit;

            if (!$user->hasRoles(['ROLE_INFIRMIER', 'ROLE_MEDECIN', 'ROLE_RECEPTION']) || (is_object($data) && $data instanceof Consultation && $data->getConsultation())) {
                $form->remove('identifiant')->remove('pin');
                $specialite = $data->getSpecialite() ? $data->getSpecialite() : null;
                $this->addElements($form, null, $specialite);
            }

            //$personne = $this->token->getToken()->getUser()->getPersonne();

            if ($this->auth->isGranted('ROLE_ASSISTANT')) {
                //$this->addElements($form, $this->getUser());
                /*$form->add('medecin', EntityType::class, [
                    'class' => Medecin::class
                    , 'mapped' => false
                    , 'choice_label' => 'personne.getNomComplet'
                    , 'query_builder' => function (EntityRepository $m) use ($user) {
                        return $m->findByMedecinByPersonne($user);
                    },

                ]
                );*/
                $medecins = [];
                $form->add('medecin', EntityType::class, [
                    'required'     => true,
                    'placeholder'  => '----------',
                    'class'        => Medecin::class,
                    'choices'      => $medecins,
                    'choice_label' => function ($medecin) {
                        return $medecin->getPersonne()->getNomComplet() . ' (' . $medecin->getPersonne()->getContact() . ')';
                    },
                ]);
            }

        });

    }



    private function getSpecialites($user)
    {
        if ($user->hasRole('ROLE_MEDECIN')) {
            $specialites = $user->getPersonne()->getMedecin()->getSpecialites() ;
        } else if ($this->auth->isGranted('ROLE_ASSISTANT')) {
            $specialites = $user->getHopital()->getSpecialites();
        } else {
          $specialites = $this->em->getRepository(Specialite::class)->findBy([], ['nom' => 'DESC']); 
        }
        return $specialites;
    }

    /**
     * @param FormEvent $event
     */
    public function onPreSubmit(FormEvent $event)
    {
        $user = $this->token->getToken()->getUser();

        
        
        $form = $event->getForm();
        $data = $event->getData();

        if (isset($data['specialite']) && !$user->hasRole('ROLE_MEDECIN')) {
            $specialite = $this->em->getRepository(Specialite::class)->find($data['specialite']);

            if (!$this->auth->isGranted('ROLE_ASSISTANT')) {
                $user = null;
            }

            $this->addElements($form, $user, $specialite);
        }

        // Search for selected City and convert it into an Entity

    }

    /**
     * @param FormInterface $form
     * @param Specialite $specialite
     */
    protected function addElements(FormInterface $form, $user = null, Specialite $specialite = null)
    {
        $medecins = [];

        if ($specialite) {
            $repMedecin = $this->em->getRepository(Medecin::class);
            $medecins   = $repMedecin->findBySpecialite($specialite, $user);
        }

        $form->add('medecin', EntityType::class, [
            'required'     => true,
            'placeholder'  => '----------',
            'class'        => Medecin::class,
            'choices'      => $medecins,
            'choice_label' => function ($medecin) {
                return $medecin->getPersonne()->getNomComplet() . ' (' . $medecin->getPersonne()->getContact() . ')';
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => RendezVous::class,
            'date_format' => null,
            'validation_groups'  => ['Default', 'edit', 'new'],
        ]);

        $resolver->setRequired('date_format');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_rdv';
    }

}
