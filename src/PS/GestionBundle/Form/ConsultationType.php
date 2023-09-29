<?php

namespace PS\GestionBundle\Form;

use PS\GestionBundle\Repository\MedecinRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use PS\ParametreBundle\Entity\Assurance;
use PS\GestionBundle\Entity\Patient;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PS\GestionBundle\Entity\Medecin;
use PS\ParametreBundle\Entity\Fichier;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Form\FormEvent;
use PS\ParametreBundle\Entity\ListeAntecedent;
use PS\ParametreBundle\Form\FichierType;
use Symfony\Component\Form\FormEvents;
class ConsultationType extends AbstractType
{
   /* @var mixed
     */
    /**
     * @var mixed
     */
    private $token;

    private $em;


    /**
     * @param TokenStorage $token
     */
    public function __construct(TokenStorageInterface $token, EntityManagerInterface $em)
    {
        $this->token = $token;
        $this->em = $em;
       
    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $patient = $options['patient'];        
        
        $user = $this->token->getToken()->getUser();
        $specialites = $user->getPersonne()->getMedecin()->getSpecialites();

        $builder
            ->add('motif', TextareaType::class, [
                'label' => 'Motif de Consultation'
            ])
            ->add('histoire', TextareaType::class, ['attr' => ['rows' => 8], 'label' => 'consultation.form.histoire', 'empty_data' => ''])
            ->add('diagnostique', TextareaType::class, [
                'label' => 'consultation.form.diagnostique',
                'empty_data' => ''
            ])
            ->add('diagnostiqueFinal', TextareaType::class, ['empty_data'  => '', 'label' => 'consultation.form.diagnostiqueFinal', 'required' => false])
            ->add('poids',null,[
                'label'=> 'consultation.form.poids'
            ])
            ->add('temperature', null, ['label' => 'consultation.form.temperature'])
            ->add('tension',null,[
                'label'=> 'consultation.form.tension'
            ])
            ->add('observation', Textareatype::class, ['empty_data'  => '', 'label' => 'consultation.form.observation', 'required' => false])
            ->add('specialite', EntityType::class, [
                'class'       => 'ParametreBundle:Specialite',
                'choice_label'    => 'nom',
                'label' => 'consultation.form.specialite.label',
                'empty_value' => "consultation.form.specialite.empty_value",
                'empty_data'  => null,
                'choices' => $specialites ?? []
               
            ])
            ->add('fichiers', CollectionType::class,
            [
                'label'         => false,
                'entry_type'    => FichierType::class,
                //'label'         => false,
                'allow_add'     => true,
                'allow_delete'  => true,
                'by_reference'  => false,
                'entry_options' => ['label' => false, 'doc_options' => $options['doc_options']],
                'delete_empty' => function(Fichier $fichier) {
                    //return null === $fichier->getFile();
                }

            ])
            ->add('assurance', EntityType::class, [
                'class'       => Assurance::class,
                'choice_label'    => 'nom',
                'empty_value' => "",
                 'placeholder' => '',
                'required' => false
                , 'query_builder' => function (EntityRepository $e) use ($patient, $user) {
                    return $e->getList($user, $patient);
                },

            ])  
           ->add('hospitalisation', TextareaType::class, [
                'label' => 'consultation.form.hospitalisation', 
                'empty_data' => '',
                'required' => false
            ])
            ->add('fonctionnels', CollectionType::class, [
                'entry_type'         => ConsultationSigneType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'mapped' => false,
                'entry_options'      => ['label' => false, 'type' => 'fonctionnel'],
            ])
             ->add('physiques', CollectionType::class, [
                'entry_type'         => ConsultationSigneType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'mapped' => false,
                'entry_options'      => ['label' => false, 'type' => 'physique'],
            ])

            ->add('affections', CollectionType::class, [
                'type'         => ConsultationAffectionsType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'options'      => ['label' => false],
            ])
            ->add('analyses', CollectionType::class, [
                'type'         => ConsultationAnalysesType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'options'      => ['label' => false],
            ])
            ->add('medicaments', CollectionType::class, [
                'type'         => ConsultationTraitementsType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'options'      => ['label' => false]])
            ->add('personnels', CollectionType::class, [
                'entry_type'         => ConsultationAntecedentType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'mapped' => false,
                'entry_options'      => ['label' => false, 'type' => 'personnel'],
            ])
             ->add('familiaux', CollectionType::class, [
                'entry_type'         => ConsultationAntecedentType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'mapped' => false,
                'entry_options'      => ['label' => false, 'type' => 'familial'],
            ])
               ->add('constantes', CollectionType::class, [
                'type'         => ConsultationConstanteType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'options'      => ['label' => false],
            ])

             ->add('rdv', RendezVousConsultationType::class, ['required' => false, 'label' => false]);
            //->add('withrendezvous', CheckboxType::class, ['mapped' => false, 'label' => 'Rendez-vous'])
           // ->add('rdv', RendezVousType::class, ['required' => false, 'label' => false]);
        if ($user->hasRole('ROLE_INFIRMIER')) {
            $builder->add('medecin', EntityType::class, [
                'class' => Medecin::class
                , 'choice_label' => 'personne.getNomComplet'
                , 'query_builder' => function (MedecinRepository $m) use ($user) {
                    return $m->findByMedecinByPersonne($user);
                },

            ]
            );
        }


       $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use($user) {
            $form = $event->getForm();
            $data = $event->getData();


        });


        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use($user) {
            $form = $event->getForm();
            $data = $event->getData();

            $repAntecedent = $this->em->getRepository(ListeAntecedent::class);

            $antecedentPersonnels = $data['personnels'] ?? [];
            $antecedentFamiliaux = $data['familiaux'] ?? [];

            foreach ($antecedentPersonnels as &$antecedent) {
                if (!($_antecedent = $repAntecedent->find($antecedent['antecedent']))) {
                    $_antecedent = new ListeAntecedent();
                    $_antecedent->setLibelle($antecedent['antecedent']);
                    //$_antecedent->setGroupe($antecedent['groupe']);
                    $_antecedent->setTypes(['personnel']);
                    $this->em->persist($_antecedent);
                    $this->em->flush();
                    $id = $_antecedent->getId();
                } else {
                    $id = $_antecedent->getId();
                }

                $antecedent['antecedent'] = $id;
            }

            $data['personnels'] = $antecedentPersonnels;


             foreach ($antecedentFamiliaux as &$antecedent) {
                if (!($_antecedent = $repAntecedent->find($antecedent['antecedent']))) {
                    $_antecedent = new ListeAntecedent();
                    $_antecedent->setLibelle($antecedent['antecedent']);
                    //$_antecedent->setGroupe($antecedent['groupe']);
                    $_antecedent->setTypes(['familial']);
                    $this->em->persist($_antecedent);
                    $this->em->flush();
                    $id = $_antecedent->getId();
                } else {
                    $id = $_antecedent->getId();
                }

                $antecedent['antecedent'] = $id;
            }


            $data['familiaux'] = $antecedentFamiliaux;



            /*$data['antecedentFamiliaux'] = $antecedentFamiliaux;
             $data['antecedentPersonnels'] = $antecedentPersonnels;*/


            $event->setData($data);

        });

    }


     /**
     * @param FormInterface $form
     * @param Specialite $specialite
     */
    protected function addElements(FormInterface $form, $user = null, Medecin $medecin = null)
    {
        $specialites = [];

        if ($medecin) {
            $specialites   = $medecin->getSpecialites();
        }


        $form->add('specialite', EntityType::class, [
            'class'       => 'ParametreBundle:Specialite',
            'choice_label'    => 'nom',
            'choices' => $specialites,
            'empty_value' => "--- Choisir une specialité ---",
            'empty_data'  => null,       
        ]);

       
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'PS\GestionBundle\Entity\Consultation',
            'patient' => null,
          
            //'validation_groups' => ['Default', 'Consultation'],
            'edit' => true,
            'doc_options' => [],
            'validation_groups' => function (FormInterface $form) {
                $data = $form->getData();
                if ($data->getFichiers()) {
                   return ['Default'];
                }
                return['Default', 'Consultation'];
            }
        ]);

    
        $resolver->setRequired('patient');
        $resolver->setRequired('edit');
        $resolver->setRequired('doc_options');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_consultation';
    }

}
