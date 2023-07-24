<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PS\UtilisateurBundle\Controller;

use FOS\UserBundle\Controller\ProfileController as BaseController;
use PS\UtilisateurBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use PS\UtilisateurBundle\Form\ProfileType;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use PS\UtilisateurBundle\Form\ProfileFormType;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller managing the user profile
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class ProfileController extends BaseController
{
    private $profileController;

    private $eventDispatcher;
    private $formFactory;
    private $userManager;
    protected $container;

    public function __construct(
       
         EventDispatcherInterface $eventDispatcher
        , FactoryInterface $formFactory
        , UserManagerInterface $userManager
    )
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->formFactory = $formFactory;
        $this->userManager = $userManager;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }


    

    /**
     * Show the user
     */
    public function showAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $role = "";

        $map = ['ROLE_CUSTOMER' => 'Patient',
            'ROLE_INFIRMIER'        => 'Infirmier',
            'ROLE_MEDECIN'          => 'Medecin',
            'ROLE_ADMIN'            => 'Administrateur',
            'ROLE_ADMIN_CORPORATE'  => 'Administrateur groupe médical',
            'ROLE_ADMIN_LOCAL'      => 'Administrateur local',
            'ROLE_PHARMACIE'        => 'Pharmacien',
            'ROLE_SUPER_ADMIN'      => 'Super Administrateur',
            'ROLE_RECEPTION'        => 'Réceptionniste'];

        foreach ($map as $_role => $role) {
            if ($user->hasRole($_role)) {
                break; 
            }
        }

        return $this->render('@FOSUser/Profile/show.html.twig', array(
            'user' => $user,
            'role' => $role
        ));

    }


     /**
     * Edit the user.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();
       
       
        $user->setEncoder('new');
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $event = new GetResponseUserEvent($user, $request);
        $this->eventDispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $errors = [];

        $form = $this->createForm(ProfileFormType::class, $user, [
            'action' => $this->generateUrl('utilisateur_admin_profile_utilisateur_edit'),
            'method'           => 'POST',
            'validation_groups' => ['Default', 'Profile'],
            //'entity_manager'   => $em,
            'passwordRequired' => false,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($user->hasRoles(['ROLE_INFIRMIER', 'ROLE_MEDECIN', 'ROLE_ASSISTANT', 'ROLE_PHARMACIE'])) {
                
                $email       = $form->get('email')->getData();
                if ($this->userManager->findUserByEmail($email)) {
                    $form->addError(new FormError('Cette adresse mail est déjà existante, veuillez en choisir une autre'));
                }
            }

            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $this->eventDispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

                $this->userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->generateUrl('utilisateur_admin_profile_utilisateur_show');
                    $response = new RedirectResponse($url);
                }

                $this->eventDispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }

            $errors = $this->get('app.form_error')->all($form);
        }

        return $this->render('@FOSUser/Profile/edit.html.twig', array(
            'form' => $form->createView()
            , 'errors' => $errors
            , 'user' => $user
        ));
    }

    /**
     * Edit the user
     */
    /*public function editAction(Request $request)
    {
        $em     = $this->getDoctrine()->getManager();
        $errors = [];

        
        $utilisateur = $this->getUser();
       

        $form = $this->createForm(ProfileType::class, $utilisateur, [
            'action'           => $this->generateUrl('admin_config_utilisateur_edit_profil', ['id' => $utilisateur->getId()]),
            'method'           => 'POST',
            'entity_manager'   => $em,
            'passwordRequired' => false,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $userManager = $this->get('fos_user.user_manager');
            if ($utilisateur->hasRoles(['ROLE_INFIRMIER', 'ROLE_MEDECIN', 'ROLE_ASSISTANT', 'ROLE_PHARMACIE'])) {
                
                $email       = $form->get('email')->getData();
                if ($userManager->findUserByEmail($email)) {
                    $form->addError(new FormError('Cette adresse mail est déjà existante, veuillez en choisir une autre'));
                }
            }



            if ($form->isValid()) {
                $userManager->updateUser($utilisateur, false);
                $em->flush();

                return $this->redirectToRoute('fos_user_profile_show');
            }

            $errors = $this->container->get('app.form_error')->all($form);

        }

        return $this->container->get('templating')->renderResponse(
            'FOSUserBundle:Profile:edit.html.' . $this->container->getParameter('fos_user.template.engine'),
            ['user' => $utilisateur, 'form' => $form->createView(), 'errors' => $errors]
        );
    }*/

   
}
