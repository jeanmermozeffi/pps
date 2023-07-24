<?php

namespace PS\GestionBundle\EventListener;

use PS\GestionBundle\Entity\Medecin;
use PS\GestionBundle\Repository\MedecinRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class AddFieldRendezVousSubscriber implements EventSubscriberInterface
{
    /**
     * @var mixed
     */
    private $token;

    /**
     * @param TokenStorage $tokenStorage
     */
    public function __construct(TokenStorage $token)
    {
        $this->token = $token;
    }

    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return [FormEvents::PRE_SET_DATA => 'preSetData'];
    }

    /**
     * @param FormEvent $event
     * @return mixed
     */
    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $user = $this->token->getToken()->getUser();
        $personne = $this->token->getToken()->getUser()->getPersonne();

        if ($user->hasRole('ROLE_INFIRMIER')) {
            $form->add('medecin', EntityType::class, [
                'class' => Medecin::class
                , 'mapped' => false
                , 'choice_label' => 'personne.getNomComplet'
                , 'query_builder' => function (MedecinRepository $m) use ($user) {
                    return $m->findByMedecinByPersonne($user);
                },

            ]
            );
        }
    }
}
