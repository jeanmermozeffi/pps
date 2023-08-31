<?php

namespace PS\UtilisateurBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use PS\GestionBundle\Entity\Pharmacie;
use PS\GestionBundle\Form\Type\RoleChoiceType;
use PS\ParametreBundle\Entity\Hopital;
use PS\UtilisateurBundle\Entity\Personne;
use PS\GestionBundle\Entity\Corporate;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use PS\ParametreBundle\Entity\Assurance;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\AuthorizationCheckerInterface;


class UtilisateurSubscriber implements EventSubscriberInterface
{
    /**
     * @var mixed
     */
    private $session;
    /**
     * @var mixed
     */
    private $request;

    /**
     * @var mixed
     */
    private $em;

    /**
     * @var mixed
     */
    private $token;

    /**
     * @param TokenStorage $tokenStorage
     */
    public function __construct(
        SessionInterface $session,
        RequestStack $request,
        EntityManagerInterface $em,
        TokenStorageInterface $token
    ) {
        $this->session = $session;
        $this->request = $request;
        $this->em      = $em;
        $this->token   = $token;
    }

    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return [FormEvents::PRE_SET_DATA => 'preSetData', FormEvents::POST_SET_DATA => 'postSetData'];
    }

    /**
     * @param $roles
     */
    public function roleModelTransformer()
    {
        return new CallbackTransformer(
            function ($roleArray) {
                return current((array) $roleArray);
            },
            function ($roleString) {
                // transform the boolean to string
                return [$roleString];
            }
        );
    }

    /**
     * @param FormEvent $event
     * @return mixed
     */
    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();



        $currentRequest = $this->request->getCurrentRequest();

        $idUser = $data->getId();

        $roles = [
            'ROLE_CUSTOMER'        => 'Patient',
            'ROLE_INFIRMIER'       => 'Infirmier',
            'ROLE_MEDECIN'         => 'Medecin',
            'ROLE_ADMIN_SUP'       => 'AGENT ENROLEUR',
            'ROLE_ADMIN'           => 'ADMINISTRATION DSI',
            'ROLE_ADMIN_CORPORATE' => 'Administrateur groupe médical',
            'ROLE_ADMIN_LOCAL'     => 'Administrateur local',
            'ROLE_PHARMACIE'       => 'Pharmacien',
            'ROLE_SUPER_ADMIN'     => 'Super Administrateur',
            'ROLE_RECEPTION'       => 'Réceptionniste',
        ];

        $user = $this->token->getToken()->getUser();

        if ($user->hasRole('ROLE_INFIRMIER') || $user->hasRole('ROLE_RECEPTION')) {
            $form->remove('roles');
            $form->remove('hopital');
            $form->remove('pharmacie');
        }

        if ($user->hasRole('ROLE_ADMIN') || $user->hasRole('ROLE_SUPER_ADMIN') || $user->hasRole('ROLE_ADMIN_SUP')) {
            $roles = array_merge($roles, [
                'ROLE_PHARMACIE' => 'Pharmacien',
                'ROLE_RECEPTION' => 'Réceptionniste',
                'ROLE_INFIRMIER' => 'Infirmier',
                'ROLE_MEDECIN'   => 'Medecin',
                'ROLE_URGENTISTE' => 'Urgentiste'
            ]);

            if ($user->hasRole('ROLE_ADMIN') || $user->hasRole('ROLE_ADMIN_SUP')) {
                unset($roles['ROLE_SUPER_ADMIN']);
            } else if($user->hasRole('ROLE_SUPER_ADMIN') && $idUser) {

            }
            $form->add('roles', RoleChoiceType::class, [
                'choices'           => array_flip($roles),
                'choices_as_values' => true,
                'expanded'          => false,
                'multiple'          => false, 'attr' => ['class' => 'list-roles'],
            ]);
        }

        if (
            $user->hasRole('ROLE_ADMIN_CORPORATE') ||
            $user->hasRole('ROLE_INFIRMIER') ||
            $user->hasRole('ROLE_RECEPTION') ||
            $user->hasRole('ROLE_ADMIN_LOCAL')
        ) {
            $form->get('personne')->remove('corporate');
        }

        if ($user->hasRole('ROLE_ADMIN_LOCAL')) {
            $form->remove('hopital');
            $form->remove('assurance');


            if (in_array('ROLE_ADMIN_LOCAL', $data->getRoles())) {
                $form->remove('roles');
            } else {
                unset($roles['ROLE_SUPER_ADMIN'], $roles['ROLE_PHARMACIE'], $roles['ROLE_ADMIN_LOCAL'], $roles['ROLE_ADMIN'], $roles['ROLE_ADMIN_CORPORATE'], $roles['ROLE_ADMIN_SUP']);

                if ($user->getAssurance()) {
                    $roles = ['ROLE_MEDECIN' => 'Medecin'];
                } else {
                    $roles = [
                        'ROLE_RECEPTION' => 'Réceptionniste',
                        'ROLE_PHARMACIE' => 'Pharmacien',
                        'ROLE_INFIRMIER' => 'Infirmier',
                        'ROLE_MEDECIN'   => 'Medecin',
                        'ROLE_SF' => 'Sage-femme'
                    ];
                }




                $form->add('roles', RoleChoiceType::class, [
                    'choices'           => array_flip($roles),
                    'choices_as_values' => true,
                    'expanded'          => false,
                    'multiple'          => false, 'attr' => ['class' => 'list-roles'],
                ]);
            }
        }

        if ($user->hasRole('ROLE_ADMIN_CORPORATE')) {

            $currentId = $currentRequest->attributes->get('id');

            if ($currentId && ($currentId == $idUser)) {
                $form->remove('roles');
            } else {
                $form->add('roles', RoleChoiceType::class, [
                    'choices'           => array_flip(['ROLE_ADMIN_LOCAL' => 'Admin Local']),
                    'choices_as_values' => true,
                    'expanded'          => false,
                    'multiple'          => false, 'attr' => ['class' => 'list-roles'],
                ]);
            }
        }


        if ($user->hasRoles(['ROLE_SUPER_ADMIN', 'ROLE_ADMIN'])) {
            $form->add(
                'assurance',
                EntityType::class,
                [
                    'class' => Assurance::class,
                    'property'                                      => 'nom',
                    'empty_value'                                   => "--- Choisir votre assurance ---",
                    'required'                                      => false,
                    'empty_data'                                    => null,
                    'attr' => ['class' => 'select2 select2-assurance'],
                ]
            );
        }

        $idUser = $data->getId();

        $name     = $form->getName();
        $oldInput = $this->session->getFlashBag()->get('__old_input');

        foreach ($oldInput as $row) {

            $fields = $row[$name];

            foreach ($fields as $field => $value) {
                if ($form->has($field)) {
                    if (property_exists($data, $field)) {

                        if ($field == 'personne') {
                            if (is_null($data->getPersonne())) {
                                $personne = new Personne();
                            } else {
                                $personne = $data->getPersonne();
                            }

                            foreach ($value as $_field => $_value) {
                                $method = 'set' . ucfirst($_field);
                                if ($_field == 'corporate' && !$_value) {
                                    $_value = null;
                                }
                                $personne->{$method}($_value);
                            }

                            $data->setPersonne($personne);
                        } elseif ($field == 'specialites') {
                            if ($data->getPersonne()->getMedecin()) {
                                $data->setSpecialites($data->getPersonne()->getMedecin()->getSpecialites());
                            }
                        } else {
                            if ($field == 'roles') {
                                $value = (array) $value;
                            }

                            if ($field == 'hopital') {
                                $rep   = $this->em->getRepository(Hopital::class);
                                $value = $rep->find($value);
                            }
                            if ($field == 'pharmacie') {
                                $rep   = $this->em->getRepository(Pharmacie::class);
                                $value = $rep->find($value);
                            }

                            if ($field == 'assurance') {
                                $rep = $this->em->getRepository(Assurance::class);
                                $value = $rep->find($value);
                            }

                            $method = 'set' . ucfirst($field);
                            $data->{$method}($value);
                        }
                    } else {
                        $form->get($field)->setData($value);
                    }
                }
            }
        }

        $event->setData($data);
    }

    /**
     * @param FormEvent $event
     * @return mixed
     */
    public function postSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        //dump($data);exit;

        $idUser = $data->getId();
        $name   = $form->getName();

        $oldInput = (array) $this->session->get('__old_input');

        $fields         = isset($oldInput[$name]) ? $oldInput[$name] : [];
        $totalSet       = 0;
        $currentRequest = $this->request->getCurrentRequest();

        if ($idUser && $idUser == $currentRequest->attributes->get('id')) {
            foreach ($fields as $field => $value) {

                if ($form->has($field)) {
                    if ($field == 'personne') {
                        $personne = $data->getPersonne();


                        foreach ($value as $_field => $_value) {
                            $method = 'set' . ucfirst($_field);
                            if ($_field == 'corporate') {

                                if (!$_value && $personne) {
                                    $_value = $personne->getCorporate();
                                } else {
                                    $_value = $this->em->getRepository(Corporate::class)->find($_value);
                                }
                            }

                            if (method_exists($personne, $method)) {
                                $personne->{$method}($_value);
                            }
                        }
                        $value = $personne;
                    }

                    if ($field == 'enabled') {
                        $value = (bool) $value;
                    }

                    if ($field == 'hopital') {
                        $rep   = $this->em->getRepository(Hopital::class);
                        $value = $rep->find($value);
                    }

                    if ($field == 'pharmacie') {
                        $rep   = $this->em->getRepository(Pharmacie::class);
                        $value = $rep->find($value);
                    }


                    if ($field == 'assurance') {
                        $rep = $this->em->getRepository(Assurance::class);
                        $value = $rep->find($value);
                    }

                    if ($field == 'specialites') {
                        if ($data->getPersonne()->getMedecin()) {
                            $value = $data->getPersonne()->getMedecin()->getSpecialites();
                        } else {
                            $value = [];
                        }
                    }
                    $form->get($field)->setData($value);
                    $totalSet += 1;
                }
            }

            if ($totalSet > 0) {
                $this->session->remove('__old_input');
            }
        }
    }
}
