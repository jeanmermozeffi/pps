<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\GestionBundle\Entity\Urgence;
use PS\ParametreBundle\Entity\Pays;
use PS\ParametreBundle\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\CallbackTransformer;
class UrgenceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('motif', TextareaType::class, ['label' => 'Motif'])
           ->add('contact', null, [
                //'required' => false
                 'label' => 'Contact',
                //'data' => $options['data']->getContact() ?? '+225'
            ])
            ->add('pays', EntityType::class, ['class' => Pays::class,
                'choice_label'                                 => 'nom',
                'required'                                     => true]
            )
             ->add('ville', EntityType::class, ['class' => Ville::class,
                'choice_label'                                  => 'nom',
                'required'                                      => true]
            )
             ->add('localisation', TextareaType::class, ['label' => 'Localisation exacte'])
             ->add('info', TextareaType::class, ['required' => false, 'label' => 'Info complémentaire', 'attr' => ['rows' => 5]]);

         $builder->get('info')
            ->addModelTransformer(new CallbackTransformer(
                function ($dbValue) {
                    // transform the array to a string
                    return $dbValue;
                },
                function ($inputValue) {
                    // transform the string back to an array
                    return strval($inputValue);
                }
            ))
        ;
        
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Urgence::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_urgence';
    }


}
