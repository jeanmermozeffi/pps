<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SearchType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('identifiant', null, ['attr' => ['placeholder' => 'Identifiant', 'autocomplete' => 'off'], 'constraints' => [new NotBlank(['message' => 'Veuillez renseigner l\'ID'])]])
            ->add('pin', null, ['attr' => ['placeholder' => 'PIN', 'autocomplete' => 'off'], 'constraints' => [new NotBlank(['message' => 'Veuillez renseigner le PIN'])]]);

        //dump($options['with_reference']);exit;

        if ($options['with_reference']) {

            $refOptions = [
                'label' => 'Référence de la consultation'
                , 'required' => $options['required_reference']
                , 'attr' => ['placeholder' => 'Référence de la consultation', 'autocomplete' => 'off']
                , 'constraints' => [new NotBlank(['message' => 'Veuillez renseigner la Référence'])]
            ];

            if (!$options['required_reference']) {
                unset($refOptions['constraints']);
            }

            $builder->add('reference', null, $refOptions);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'with_reference' => false,
            'required_reference' =>true
        ));

        $resolver->setRequired('with_reference');
        $resolver->setRequired('required_reference');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {

    }

}
