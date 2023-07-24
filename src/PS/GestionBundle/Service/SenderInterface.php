<?php

namespace PS\GestionBundle\Service;

interface SenderInterface
{
    public function send($id, $message);
}