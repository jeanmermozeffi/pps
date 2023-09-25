<?php

namespace PS\GestionBundle\Form;

use PS\GestionBundle\Entity\Medecin;
use PS\GestionBundle\Repository\MedecinRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use PS\ParametreBundle\Entity\Assurance;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class ConstanteType extends AbstractType
{
    /**
     * @var mixed
     */
    private $token;


    private $em;

    /**
     * @param TokenStorage $token
     */
    public function __construct(TokenStorage $token, EntityManagerInterface $em)
    {
        $this->token = $token;
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->token->getToken()->getUser();
        $patient = $options['patient'];

        $builder
            ->add('constantes', CollectionType::class, [
                'type'         => ConsultationConstanteType::class,
                'label'        => false,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'options'      => ['label' => false],
                'delete_empty' => function($c) {
                    return $c->getValeur() === '';
                }
            ])
            
             ->add('assurance', EntityType::class, [
                'class'       => Assurance::class,
                'property'    => 'nom',
                'empty_value' => "--- Choisir une assurance ---",
                'empty_data'  => null,
                'required' => false
                , 'query_builder' => function (EntityRepository $e) use ($patient, $user) {
                    return $e->getList($user, $patient);
                },

            ])
            ->add('medecin', EntityType::class, [
                'class' => Medecin::class, 
                'choice_label' => 'personne.getNomComplet', 
                'query_builder' => function (MedecinRepository $m) use ($user) {
                    return $m->findByMedecinByPersonne($user);
                },
                'attr' => ['class' => 'select2']

            ]
        );


        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($user) {
            $form = $event->getForm();
            $data = $event->getData();
            $medecin = $data->getMedecin() ? $data->getMedecin() : null;
            $specialite = $data->getSpecialite();
            $this->addElements($form, $medecin, $specialite);
        });


         $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($user) {
            $form = $event->getForm();
            $data = $event->getData();
            
            $this->addElements($form, $data['medecin'] ?? null);
        });
    }


    /**
     * @param FormInterface $form
     * @param Specialite $specialite
     */
    protected function addElements(FormInterface $form, $medecin = null, $specialite = null)
    {
        $specialites = [];

        if ($medecin) {
            if (is_string($medecin)) {
                $medecin = $this->em->getRepository(Medecin::class)->find($medecin);
            }
            $specialites = $medecin->getSpecialites();
        }

        $form->add('specialite', EntityType::class, [
                'class'       => 'ParametreBundle:Specialite',
                'choice_label'    => 'nom',

                'choices' =>  $specialites,
                'placeholder' => "--- Choisir une specialité ---",
                'empty_data'  => null,
                'attr' => ['class' => 'select2']
                //'data' => $specialite

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
            'validation_groups' => ['Default', 'Constante']
        ]);
        $resolver->setRequired('patient');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_constante';
    }

}
