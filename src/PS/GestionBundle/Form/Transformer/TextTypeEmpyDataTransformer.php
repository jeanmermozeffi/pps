<?php

namespace PS\GestionBundle\Form\Transformer;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;

class TextTypeEmptyDataExtension extends AbstractTypeExtension implements DataTransformerInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ('' === $options['empty_data']) {
            $builder->addModelTransformer($this);
        }
    }

    public function getExtendedType()
    {
        return TextType::class;
    }

    public function transform($data)
    {
        return $data;
    }

    public function reverseTransform($data)
    {
        return is_null($data) ? '' : $data;
    }
}