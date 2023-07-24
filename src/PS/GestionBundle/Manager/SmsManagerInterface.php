<?php

namespace PS\GestionBundle\Manager;


interface SmsManagerInterface 
{
     public function send($message, $numbers);
     
     /**
     * Retourne les messages d'erreurs
     * @return array
     */
    public function errors();

    /**
     * Retourne les messages d'erreurs
     * @return array
     */
    public function messages();
}