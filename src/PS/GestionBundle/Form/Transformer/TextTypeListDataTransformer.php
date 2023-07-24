<?php

namespace PS\GestionBundle\Form\Transformer;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;

class TextTypeListDataTransformer extends AbstractTypeExtension implements DataTransformerInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this);
    }

    public function getExtendedType()
    {
        return TextType::class;
    }

    public function transform($data)
    {
        //dump($data);exit;
        return implode('/', $data);
    }

    public function reverseTransform($data)
    {
        return explode('/', $data);
    }
}