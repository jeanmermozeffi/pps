<?php

namespace PS\SpecialiteBundle\Form;

use PS\SpecialiteBundle\Entity\Champ;
use PS\SpecialiteBundle\Entity\Etape;
use PS\SpecialiteBundle\Entity\TypeChamp;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityRepository;

class ChampType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $specialite = $options['specialite'];
        $builder->add('libChamp', null, ['label' => 'Libellé', 'required' => false])

            ->add('etape'
                , EntityType::class, [
                    'class' => Etape::class
                    , 'property' => 'libEtape'
                    , 'empty_value' => 'Veuillez sélectionner une étape',
                    'query_builder' => function (EntityRepository $em) use($specialite) {
                        $qb = $em->createQueryBuilder('u');
                        return $qb->andWhere('u.specialite = :specialite')->setParameter('specialite', $specialite);
                    }
                ]
            )

            ->add('typeChamp', EntityType::class, ['class' => TypeChamp::class, 'property' => 'libTypeChamp', 'empty_value' => 'Veuillez sélectionner le type'])
        /*->add('propChamp',  TextareaType::class, ['label' => 'Propriétés du champ', 'attr' => ['rows' => 8], 'required' => false])*/
        //->add('propChamp', PropChampType::class)
            ->add('valeurAutoriseeChamp', TextareaType::class, ['label' => 'Valeurs autorisées', 'attr' => ['rows' => 8], 'required' => false])
            ->add('champParent', EntityType::class,
                [
                    'class' => Champ::class
                    , 'property' => 'libChamp'
                    , 'empty_value' => 'Veuillez sélectionner le champ parent'
                    , 'required' => false
                    , 'choice_label' => function (Champ $champ) {
                        $libChamp = $champ->getLibChamp().'/'.$champ->getSpecialite()->getNom();
                        if ($champ->getChampParent()) {
                            $libChamp .= ' ('.$champ->getChampParent()->getLibChamp().')';
                        }

                        return $libChamp;
                    }
                    , 'attr' => ['class' => 'parent-field']
            ])
            ->add('valeurChampParent', null, ['label' => 'Valeur du champ parent', 'attr' => ['class' => 'parent-field-value']])
            ->add('valeurChampDefaut', TextareaType::class, ['label' => 'Valeur par défaut', 'required' => false])
           
            ->add('champRequis', CheckboxType::class, ['label' => 'Champ Requis', 'required' => false, 'empty_data' => true, 'data' => true])
            ->add('validationGroup', ChoiceType::class, ['required' => false, 'choices' => ['' => '', 'unique_choice' =>'Choix unique'], 'label' => 'Groupe de validation']);

        $builder->get('valeurAutoriseeChamp')->addModelTransformer(new CallbackTransformer(
            function ($valAsArray) {
                return implode(PHP_EOL, (array) $valAsArray);

            },
            function ($valAsString) {
                return preg_split('/\r\n|[\r\n]/', (string) $valAsString);
            }
        ));

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            $formName = $form->getName();

            if (!isset($data['champRequis'])) {
                $form->add('champRequis', NumberType::class, ['data' => 0, 'empty_data' => 0]);
                $data['champRequis'] = 0;
            }

            $event->setData($data);
        });

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'PS\SpecialiteBundle\Entity\Champ',
            'specialite' => null
        ]);

        $resolver->setRequired('specialite');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_specialitebundle_champ';
    }

}
