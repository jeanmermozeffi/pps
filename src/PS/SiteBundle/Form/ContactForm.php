<?php

namespace PS\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;



class ContactForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$domain = isset($options['domain']) ? $options['domain'] : ''; 
        //dump($options);exit;
        $builder->add('nom',TextType::class,array('label'=>'home.form.contact.name'));
        $builder->add('email', EmailType::class, array('label' => 'home.form.contact.email'));

        $choices = [
                    'home.form.contact.dropdown_categories.technical',
                    'home.form.contact.dropdown_categories.info',
                    'home.form.contact.dropdown_categories.convention',
                    'home.form.contact.dropdown_categories.franchise',
                    'home.form.contact.dropdown_categories.other',
                    
                ];
        
        $builder->add('categorie', ChoiceType::class, [
                'choices'           => $choices,

                'label' => 'home.form.contact.categorie',
                'choice_translation_domain' => true,
                'empty_data' => 1,
                'placeholder' => '----',
                //'choices_as_values' => true,
                'expanded'          => false,
                'multiple'          => false
        ]);
        
        
        $builder->add('sujet',TextType::class,array('label'=>'home.form.contact.subject'));
        $builder->add('message', TextareaType::class,array('label' => 'home.form.contact.message', 'attr' => array('rows' => 6)));
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null
        ]);

        $resolver->setDefined(['domain']);
    }
}