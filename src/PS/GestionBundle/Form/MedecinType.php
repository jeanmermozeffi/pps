<?php

namespace PS\GestionBundle\Form;

use PS\ParametreBundle\Entity\Hopital;
use PS\ParametreBundle\Entity\Specialite;
use PS\UtilisateurBundle\Form\PersonneType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class MedecinType extends AbstractType
{

     /**
     * @param SessionInterface $session
     * @param RequestStack $request
     * @param EntityManager $em
     * @param TokenStorage $token
     */
    public function __construct(TokenStorage $token)
    {
        $this->token   = $token;
    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $user = $this->token->getToken()->getUser();
        $builder
            ->add('personne', PersonneType::class, ['label' => false])
            //->add('utilisateur', new UtilisateurType(), array('label' => false));
             ->add('hopital'
                , EntityType::class
                , [
                    'class' => Hopital::class
                    , 'choice_label' => 'nom',
                    'query_builder' => function (EntityRepository $e) use($user) {
                        if ($user->hasRole('ROLE_ADMIN_CORPORATE')) {
                            return $e->findByCorporates($user->getPersonne()->getCorporate());
                        } elseif ($user->hasRole('ROLE_ADMIN_LOCAL')) {
                            return $e->createQueryBuilder('a')
                                      ->andWhere('a.id = :hopital')
                                      ->setParameter('hopital', $user->getHopital()->getId());
                        }
                    }
                    //, 'mapped' => false
                    //, 'placeholder' => ''
                    , 'attr' => ['class' => 'select2 select2-hopital'],
                    //'data' => $this->em->getReference(Hopital::class, 3)

                ]
            )
            ->add('specialites'
                , EntityType::class, [
                    'multiple'     => true,
                    'choice_label' => 'nom',
                    'expanded'     => false,
                    'required' => false,
                    'class'        => Specialite::class,
                ]);


            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($user) {
                $form = $event->getForm();
                if ($user->hasRole('ROLE_ADMIN_LOCAL') || $user->hasRole('ROLE_ADMIN_CORPORATE')) {
                    $form->get('personne')->remove('corporate');
                }
            });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'PS\GestionBundle\Entity\Medecin',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_medecin';
    }

}
