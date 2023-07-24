<?php
namespace PS\GestionBundle\EventListener;

// ...

use Avanzu\AdminThemeBundle\Event\NotificationListEvent;
use PS\GestionBundle\Model\NotificationModel;

class MyNotificationListListener {

    // ...

    public function onListNotifications(NotificationListEvent $event) {

        foreach($this->getNotifications() as $Notification) {
            $event->addNotification($Notification);
        }

    }

    protected function getNotifications() {
        return array(
            new NotificationModel('some notification'),
            new NotificationModel('some more notices', 'success'),
        );
    }

}