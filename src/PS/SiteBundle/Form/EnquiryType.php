<?php

namespace PS\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EnquiryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name',"text",array('label'=>'Nom et Prénom'));
        $builder->add('email', 'email');
        $builder->add('subject',"text",array('label'=>'Objet'));
        $builder->add('body', 'textarea',array('label'=>'Message'));
    }

    public function getName()
    {
        return 'contact';
    }
}