<?php

namespace PS\GestionBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;

class EmailTypeEmptyDataExtension extends AbstractType implements DataTransformerInterface
{
    use TransformerTrait;

    public function getParent()
    {
        return 'email';
    }
}
