<?php

namespace PS\GestionBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

trait TransformerTrait
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ('' === $options['empty_data']) {
            $builder->addModelTransformer($this);
        }
    }

    /**
     * @param $data
     * @return mixed
     */
    public function transform($data)
    {
        return $data;
    }

    /**
     * @param $data
     */
    public function reverseTransform($data)
    {
        return empty($data) ? '' : $data;
    }
}
