<?php

namespace PS\GestionBundle\Form\EventListener;

use PS\ParametreBundle\Entity\Assurance;
use PS\ParametreBundle\Entity\Vaccin;
use PS\ParametreBundle\Entity\Affection;
use PS\ParametreBundle\Entity\Allergie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class AfterFileExportListener implements EventSubscriberInterface
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
            FormEvents::PRE_SUBMIT  => 'onPreSubmit',
            FormEvents::SUBMIT      => 'onSubmit',
            FormEvents::POST_SUBMIT => 'onPostSubmit',
            //FormEvents::PRE_SET_DATA => 'onPreSetData'
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function onSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

    }

    /**
     * @param FormEvent $event
     */
    public function onPostSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();
    }

    /**
     * @param FormEvent $event
     * @return null
     */
    public function onPreSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();
    }
}
