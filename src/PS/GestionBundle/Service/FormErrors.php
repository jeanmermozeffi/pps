<?php

namespace PS\GestionBundle\Service;
use PS\GestionBundle\Service\FormErrorsSerializer;

class FormErrors
{
    /**
     * @param $form
     */
    public function all($form)
    {
        $formError = new FormErrorsSerializer();
        return array_flatten(array_values($formError->serializeFormErrors($form, true)));
    }
}
