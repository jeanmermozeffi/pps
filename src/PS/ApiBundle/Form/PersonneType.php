<?php

namespace PS\ApiBundle\Form;

use PS\ApiBundle\Form\AvatarType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonneType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('photo',new AvatarType(),array('label'=>false))
            ->add('nom')
            ->add('prenom')
            ->add('datenaissance', 'birthday', array('widget' => 'single_text', 'format' => 'dd/MM/yyyy','label'=>'Date naissance (01/01/2016)', 'invalid_message' => 'Veuillez choisir votre date de naissance'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PS\UtilisateurBundle\Entity\Personne',
            'csrf_protection' => false,
        ));
    }


}
