<?php

namespace PS\UtilisateurBundle\Form;

use PS\GestionBundle\Entity\Patient;
use PS\ParametreBundle\Entity\LienParente;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\CallbackTransformer;

class CompteAssocieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('identifiant', TextType::class, ['mapped' => false, 'constraints' => [new NotBlank]])
            ->add('pin', TextType::class, ['mapped' => false, 'constraints' => [new NotBlank]])

            ->add('lien', EntityType::class, [
                'class'        => LienParente::class,
                'choice_label' => 'libLienParente',
                'empty_value'  => "compteassocie.form.lien",
                'required'     => false,
                'empty_data'   => null,
            ])
            ->add('associe', IntegerType::class,[
                'label'=> 'compteassocie.form.associe'
            ]);


        $builder->get('associe')
            ->addModelTransformer(new CallbackTransformer(
                function ($associe) {
                    // transform the array to a string
                    return $associe ? $associe->getId(): 0;
                },
                function ($id) use($options) {
                    // transform the string back to an array
                    return $options['em']->getRepository(Patient::class)->find($id);
                }
            ))
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if ($data !== null) {
                $form->get('identifiant')->setData($data->getIdentifiant());
                $form->get('pin')->setData($data->getPin());
            }

        });


        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            

        });

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'PS\UtilisateurBundle\Entity\CompteAssocie',
        ]);

        $resolver->setRequired('em');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_utilisateurbundle_compteassocie';
    }

}
