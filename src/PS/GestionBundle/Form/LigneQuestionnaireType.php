<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\GestionBundle\Entity\LigneQuestionnaire;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class LigneQuestionnaireType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $types = [
            'TextType' => 'Champ simple',
            'TextareaType' => 'Flot de texte',
            'NumberType' => 'Nombre',
            'SelectType' => 'Liste de sélection',
            'ChoiceType' => 'Choix simple',
            'CheckboxType' => 'Choix multiple'

        ];
        $builder
                    ->add('ordre', null, ['label' => 'Ordre'])
                ->add('question', TextareaType::class, ['label' => 'Libellé de la question'])
                ->add('parent', EntityType::class, [
                    'class' => LigneQuestionnaire::class,
                    'choice_label' => function ($ligne) {
                        return $ligne->getQuestion().' ('.$ligne->getOrdre().')';
                    },
                    'label' => 'Question Parente',
                    'required' => false,
                     'attr' => ['class' => 'select2'],
                    'query_builder' => function ($e) use($options) {
                        $qb = $e->createQueryBuilder('a');
                        if ($options['ligne']) {
                            $qb->andWhere('a.id <> :ligne')->setParameter('ligne', $options['ligne']);
                        }
                        $qb->andWhere('a.statut = 1');
                        return $qb;
                    }
                ])

                ->add('typeChamp', ChoiceType::class, [
                    'label' => 'Type de champ',
                    'choices' => $types,
                    'attr' => ['class' => 'select2']
                ])
                 ->add('pourcentage', NumberType::class, ['label' => 'Pourcentage de risque'])
                 ->add('valeurs', CollectionType::class, [
                   'entry_type'         => ValeurLigneQuestionnaireType::class,
                    'label'        => false,
                    'allow_add'    => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'entry_options'      => ['label' => false],
                ])
                /*->add('valeurPossible', TextareaType::class, ['label' => 'Valeur possibles (1 choix par ligne)', 'attr' => ['rows' => 10], 'required' => false])*/
                ->add('requis', CheckboxType::class, ['label' => 'Réquis', 'required' => false])

                 ->add('statut', CheckboxType::class, ['label' => 'Activer/Désactiver', 'required' => false])
                
                ->add('libAide', TextareaType::class, ['label' => 'Champ aide', 'required' => false, 'empty_data' => '', 'attr' => ['rows' => 10, 'class' => 'has-editor']])
                //->add('optionChamp')
                //->add('valeurDefaut')
                ;


        /*$builder->get('valeurPossible')->addModelTransformer(new CallbackTransformer(
            function ($valAsArray) {
                return implode(PHP_EOL, (array) $valAsArray);

            },
            function ($valAsString) {
                return preg_split('/\r\n|[\r\n]/', (string) $valAsString);
            }
        ));*/

         $builder->get('libAide')->addModelTransformer(new CallbackTransformer(
            function ($libelle) {
                return strval($libelle);

            },
            function ($libelle) {
                return strval($libelle);
            }
        ));





        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            if ($data['typeChamp'] == 'CheckboxType') {
                $form->add('multiple', CheckboxType::class);
                $data['multiple'] = true;
            }

            if (in_array($data['typeChamp'], ['ChoiceType', 'SelectType', 'CheckboxType'])) {

                if (!count($data['valeurs'] ?? [])) {
                    $statut  = 0;
                   $form->addError(new FormError('Veuillez renseigner une ou plusieurs valeurs dans le champ pour le type sélectionné'));
               }
            }  else {
                $data['valeurs'] = [];
            }

            
            $event->setData($data);
        });
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LigneQuestionnaire::class,
            'ligne' => null
        ]);

        $resolver->setRequired('ligne');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_lignequestionnaire';
    }


}
