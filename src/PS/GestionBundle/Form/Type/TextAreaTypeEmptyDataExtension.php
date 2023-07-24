<?php

namespace PS\GestionBundle\Form\Type;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Symfony\Component\Form\DataTransformerInterface;

class TextAreaTypeEmptyDataExtension extends AbstractTypeExtension implements DataTransformerInterface
{
    use TransformerTrait;

    public function getExtendedType()
    {
        return TextareaType::class;
    }
}
