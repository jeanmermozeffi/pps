<?php

namespace PS\MobileBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller; // Utilisation de la vue de FOSRestBundle
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;


class ProfilController extends Controller
{

    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Patch("/users/{id}")
     */
    public function patchUserAction(Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user        = $userManager->findUserBy(['id' =>$request->get('id')]);
        //dump($user);exit;
        //$user->setEnabled(true);

        $form = $this->get('fos_user.profile.form.factory')->createForm([
            'csrf_protection' => false
        ]);

        $form->setData($user);

        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $event = new FormEvent($form, $request);
            $this->get('debug.event_dispatcher')->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

            $userManager->updateUser($user);
            return $user;

        }

        return $form;
    }
}
