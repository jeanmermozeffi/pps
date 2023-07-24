<?php

namespace PS\ApiBundle\Form\Handler;

use FOS\UserBundle\Form\Handler\RegistrationFormHandler as BaseHandler;
use FOS\UserBundle\Model\UserInterface;

class RegistrationFormHandler extends BaseHandler
{
    
    public function process($confirmation = false, $rest = false, &$data = null)
    {
        $user = $this->createUser();
        $this->form->setData($user);

        if ('POST' === $this->request->getMethod()) {

            $data = $this->request->request->all();

            if (!$rest) {
                $this->form->handleRequest($this->request);
            } else {
                $this->form->submit($this->request->request->all());
            }
            
           
            if ($this->form->isValid()) {

                parent::onSuccess($user, $confirmation);

                return true;
            }
        }

        return false;

    }
}