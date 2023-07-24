<?php

namespace PS\GestionBundle\EventListener;

use Doctrine\DBAL\Event\ConnectionEventArgs;

/**
 * My initializer
 */
class  MyPdoInitializerListener
{
    public function postConnect(ConnectionEventArgs $args)
    {
       /* $args->getConnection()
            ->exec("SET time_zone = UTC");*/
    }
}