<?php

namespace PS\GestionBundle\Form\Type;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\DataTransformerInterface;

class TextTypeEmptyDataExtension extends AbstractTypeExtension implements DataTransformerInterface
{
    use TransformerTrait;

    public function getExtendedType()
    {
        return TextType::class;
    }
}