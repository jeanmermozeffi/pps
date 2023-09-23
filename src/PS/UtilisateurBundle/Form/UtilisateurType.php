<?php

namespace PS\UtilisateurBundle\Form;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PS\GestionBundle\Entity\Pharmacie;
use PS\GestionBundle\Form\Type\RoleChoiceType;
use PS\ParametreBundle\Entity\Hopital;
use PS\ParametreBundle\Entity\Specialite;
use PS\UtilisateurBundle\EventListener\UtilisateurSubscriber;
use PS\UtilisateurBundle\Form\PersonneType;
use PS\UtilisateurBundle\Validator\Constraints\Alphanumeric;
use PS\UtilisateurBundle\Validator\Constraints\PasswordStrength;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UtilisateurType extends AbstractType
{
    /**
     * @param SessionInterface $session
     * @param RequestStack $request
     * @param EntityManager $em
     * @param TokenStorage $token
     */
    public function __construct(SessionInterface $session, RequestStack $request, EntityManagerInterface $em, TokenStorageInterface $token)
    {
        $this->session = $session;
        $this->request = $request;
        $this->em      = $em;
        $this->token   = $token;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $user = $this->token->getToken()->getUser();
        $builder
            ->add('hopital'
                , EntityType::class
                , [
                    'class'         => Hopital::class,'choice_label' => 'nom',
                    'query_builder' => function (EntityRepository $e) use ($user) {
                        if ($user->hasRole('ROLE_ADMIN_CORPORATE')) {
                            return $e->findByCorporates($user->getPersonne()->getCorporate());
                        }
                    }
                    //, 'mapped' => false
                    //, 'placeholder' => ''
                    , 'required'     => false
                    , 'attr' => ['class' => 'select2 select2-hopital'],
                    //'data' => $this->em->getReference(Hopital::class, 3)

                ]
            )
            ->add('specialites', EntityType::class, [
                    'class' => Specialite::class
                    , 'choice_label' => 'nom'
                    /*,'query_builder' => function (EntityRepository $e) use($user) {
                        return $e->createQueryBuilder('a');
                    }*/
                    //, 'mapped' => false
                    , 'placeholder' => 'utilisateur.form.specialites'
                    , 'attr' => ['class' => 'select2 select2-specialite hide'],
                    'expanded'          => false,
                    'multiple'          => true,
                    'required' => false,
                    'mapped' => false

                    //'data' => $this->em->getReference(Hopital::class, 3)

                ])

            ->add('pharmacie'
                , EntityType::class
                , [
                    'class' => Pharmacie::class
                    , 'choice_label' => 'libPharmacie'
                    //, 'mapped' => false
                    //, 'placeholder' => ''
                    , 'required' => false
                    , 'attr' => ['class' => 'select2 select2-pharmacie'],
                    //'data' => $this->em->getReference(Hopital::class, 3)

                ]
            )
            ->add('personne', PersonneType::class, ['label' => false])
            ->add('username'
                , null
                , [
                    'label' => 'form.username'
                    , 'translation_domain' => 'FOSUserBundle'
                    , 'constraints' => [
                        new Alphanumeric(),
                    ],
                    'attr'  => [
                        'class' => 'validate-username',
                    ],
                ]
            )
            ->add('email', null,[
                'label'=>'utilisateur.form.email'
            ])
            // ->add('plainPassword',
            //     RepeatedType::class, [
            //         'type'            => PasswordType::class,
            //         'invalid_message' => 'utilisateur.form.invalid_message',
            //         'required'        => $options['passwordRequired'],
            //         'first_options'   => [
            //             'label' => 'utilisateur.form.first_options'
            //             , 'constraints' => [
            //                 new PasswordStrength(),
            //             ],
            //             'attr'  => [
            //                 'class' => 'validate-password',
            //             ],
            //         ],
            //         'second_options'  => ['label' => 'Répétez'],
            //     ]
            // )
            ->add('roles', RoleChoiceType::class, [
                'choices'           => array_flip([
                    'ROLE_CUSTOMER'        => 'Patient',
                    'ROLE_INFIRMIER'       => 'Infirmier',
                    'ROLE_MEDECIN'         => 'Medecin',
                    'ROLE_ADMIN'           => 'ADMINISTRATION DSI',
                    'ROLE_ADMIN_CORPORATE' => 'Administrateur groupe médical',
                    'ROLE_ADMIN_LOCAL'     => 'Administrateur local',
                    'ROLE_ADMIN_SUP' => 'Administrateur suppléant',
                    //'ROLE_PHARMACIE'       => 'Pharmacien',
                    'ROLE_SUPER_ADMIN'     => 'Super Administrateur',
                    //'ROLE_RECEPTION'       => 'Réceptionniste',
                ]),
                'choices_as_values' => true,
                'expanded'          => false,
                'multiple'          => false
                , 'attr' => ['class' => 'list-roles'],
            ])

            /*->add('roles', 'collection', [
        'type'         => 'choice',
        'allow_add'    => false,
        'allow_delete' => true,
        'by_reference' => false,
        'options'      => [
        'label'    => false,
        'required' => false,
        'multiple' => true,
        'choices'  => [
        'ROLE_CUSTOMER'    => 'Patient',
        'ROLE_INFIRMIER'   => 'Infirmier',
        'ROLE_MEDECIN'     => 'Medecin',
        'ROLE_ADMIN'       => 'Administrateur',
        'ROLE_PHARMACIE' => 'Pharmacien',
        'ROLE_SUPER_ADMIN' => 'Super Administrateur',
        ],
        ]])*/

            ->add('enabled', CheckboxType::class, [
                'label'    => 'utilisateur.form.enabled',
                'required' => false,
            ])
            /*->add('locked', CheckboxType::class, [
                'label'    => 'Vérrouiller',
                'required' => false,
            ])*/;

        $builder->addEventSubscriber(new UtilisateurSubscriber(
            $this->session
            , $this->request
            , $this->em
            , $this->token
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'       => 'PS\UtilisateurBundle\Entity\Utilisateur',
            'passwordRequired' => true,
            //'lockedRequired' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_utilisateurbundle_utilisateur';
    }

}
