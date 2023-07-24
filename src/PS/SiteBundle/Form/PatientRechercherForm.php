<?php

namespace PS\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
class PatientRechercherForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('identifiant', null, ['label' => false, "attr" => ["placeholder" => $options['label_id']]])
            ->add('pin', null, ['label' => false, "required" => false, "attr" => ["placeholder" => "home.form.pin"]])
            ->add('contact', null, ['label' => false, "attr" => ["placeholder" => "home.form.contact.contact"]])
            ->add('localisation', TextareaType::class, ['label' => false, 'required' => false, "attr" => ["placeholder" => "home.form.localisation", "rows" => 5]])
            ->add('urgence', CheckboxType::class, [
            
          
             'label' => 'Situation d\'urgence (Votre numéro de téléphone sera rappelé par un des proches de la personne concernée)',
            /*, 'constraints' => [
                new IsTrue(['message' => 'Veuillez accepter nos conditions avant de continuer']),
            ],*/
            'block_name' => 'term_conditions',
            'required' => false
        ]
        );
    }


    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'label_id' => 'home.form.id'
        ]);

        $resolver->setRequired(['label_id']);
    }

    public function getBlockPrefix()
    {
        return 'patient_recherche';
    }
}
