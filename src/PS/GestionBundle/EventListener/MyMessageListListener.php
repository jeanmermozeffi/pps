<?php
namespace PS\GestionBundle\EventListener;

// ...

use Avanzu\AdminThemeBundle\Event\MessageListEvent;
use PS\GestionBundle\Model\MessageModel;
use PS\GestionBundle\Model\UserModel;
//use FOS\UserBundle\Model\User;

class MyMessageListListener {

    // ...

    public function onListMessages(MessageListEvent $event) {

        foreach($this->getMessages() as $message) {
            $event->addMessage($message);
        }

    }

    protected function getMessages() {
        return array(
            new MessageModel(new UserModel('Karl kettenkit'),'Dude! do something!', new \DateTime('-3 days')),
            new MessageModel(new UserModel('Jack Trockendoc'),'This is some subject', new \DateTime('-10 month')),
        );
    }

}