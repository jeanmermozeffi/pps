<?php

namespace PS\GestionBundle\Service;

class FormErrorsSerializer
{

    /**
     * @param \Symfony\Component\Form\Form $form
     * @param $flat_array
     * @param false $add_form_name
     * @param false $glue_keys
     * @return mixed
     */
    public function serializeFormErrors(\Symfony\Component\Form\Form $form, $flat_array = false, $add_form_name = false, $glue_keys = '_')
    {
        $errors           = [];
        $errors['global'] = [];
        $errors['fields'] = [];

        foreach ($form->getErrors(true, true) as $error) {
            $errors['global'][] = $error->getMessage();
        }

        $errors['fields'] = $this->serialize($form);

        if ($flat_array) {
            $errors['fields'] = $this->arrayFlatten($errors['fields'],
                $glue_keys, (($add_form_name) ? $form->getName() : ''));
        }

        return $errors;
    }

    /**
     * @param \Symfony\Component\Form\Form $form
     * @return mixed
     */
    private function serialize(\Symfony\Component\Form\Form $form)
    {
        $local_errors = [];
        foreach ($form->getIterator() as $key => $child) {

            foreach ($child->getErrors() as $error) {
                $local_errors[$key] = $error->getMessage();
            }

            if (count($child->getIterator()) > 0) {
                $local_errors[$key] = $this->serialize($child);
            }
        }

        return $local_errors;
    }

    /**
     * @param $array
     * @param $separator
     * @param $flattened_key
     * @return mixed
     */
    private function arrayFlatten($array, $separator = "_", $flattened_key = '')
    {
        $flattenedArray = [];
        foreach ($array as $key => $value) {

            if (is_array($value)) {

                $flattenedArray = array_merge($flattenedArray,
                    $this->arrayFlatten($value, $separator,
                        (strlen($flattened_key) > 0 ? $flattened_key . $separator : "") . $key)
                );

            } else {
                $flattenedArray[(strlen($flattened_key) > 0 ? $flattened_key . $separator : "") . $key] = $value;
            }
        }
        return $flattenedArray;
    }

}
