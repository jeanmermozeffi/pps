<?php

namespace PS\GestionBundle\Form\Type;

use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RoleChoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new CallbackTransformer(
             function ($roleArray) {
                return current((array)$roleArray);
             },
             function ($roleString) {
                 // transform the boolean to string
                 return array($roleString);
             }
        ));
    }

    public function getParent()
    {
        return 'choice';
    }
}