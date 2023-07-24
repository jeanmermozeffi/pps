<?php

namespace PS\SpecialiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\ParametreBundle\Entity\Specialite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class FicheType extends AbstractType
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
        

        ->add('specialite', EntityType::class
                , [
                    'label' => 'Specialité'
                    , 'class' => Specialite::class
                    , 'property' => 'nom'
                    , 'empty_value' => '--------'
                    , 'query_builder' => function (EntityRepository $e) use($user) {
                        return $e->getForMedecin($user->getPersonne()->getMedecin());
                    }
                   
                ])
        ->add('distanceHopitalRef', null, ['label' => 'Distance patient de l\'hopital de référence'])
        ->add('accordPatient', CheckboxType::class, ['label' => 'Accord du patient', 'required' => true, 'empty_data' => true]);

         $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            $formName = $form->getName();

            if (!isset($data['accordPatient'])) {
                $form->add('accordPatient', NumberType::class, ['data' => 0, 'empty_data' => 0]);
                $data['accordPatient'] = 0;
            }

            $event->setData($data);
        });
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PS\SpecialiteBundle\Entity\Fiche'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_specialitebundle_fiche';
    }


}
