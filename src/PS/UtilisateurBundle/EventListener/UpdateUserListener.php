<?php
// src/AppBundle/EventListener/BrochureUploadListener.php
namespace PS\UtilisateurBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use PS\UtilisateurBundle\Entity\Utilisateur;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class UpdateUserListener implements EventSubscriberInterface
{

    /**
     * @var mixed
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::POST_SUBMIT => 'onPostSubmit',
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function onPostSubmit(FormEvent $event)
    {
        $user  = $event->getData();

        $email = $user->getEmail();
        $repUser = $this->em->getRepository(Utilisateur::class);

        $repUser->updateEmailByParent($user->getId(), $email);

    }
}
