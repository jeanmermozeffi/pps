<?php

namespace PS\UtilisateurBundle\Controller;

use PS\UtilisateurBundle\Entity\Role;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Role controller.
 *
 */
class RoleController extends Controller
{
    /**
     * Lists all role entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $liste_roles = $em->getRepository('UtilisateurBundle:Role')->findAll();

        $roles  = $this->get('knp_paginator')->paginate(
            $liste_roles,
            $request->query->get('page', 1)/*page number*/,10/*limit per page*/
        );

        return $this->render('role/index.html.twig', array(
            'roles' => $roles,
        ));
    }

    /**
     * Creates a new role entity.
     *
     */
    public function newAction(Request $request)
    {
        $role = new Role();
        $form = $this->createForm('PS\UtilisateurBundle\Form\RoleType', $role, array(
                'action' => $this->generateUrl('admin_config_role_new'),
                'method' => 'POST'
            ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($role);
            $em->flush();

            return $this->redirectToRoute('admin_config_role_index');
        }

        return $this->render('role/new.html.twig', array(
            'role' => $role,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a role entity.
     *
     */
    public function showAction(Role $role)
    {
        $showForm = $this->createForm('PS\UtilisateurBundle\Form\RoleType', $role);

        $deleteForm = $this->createDeleteForm($role);

        return $this->render('role/show.html.twig', array(
            'role' => $role,
            'show_form' => $showForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing role entity.
     *
     */
    public function editAction(Request $request, Role $role)
    {
        $deleteForm = $this->createDeleteForm($role);
        $editForm = $this->createForm('PS\UtilisateurBundle\Form\RoleType', $role, array(
                'action' => $this->generateUrl('admin_config_role_edit', array('id' => $role->getId())),
                'method' => 'POST'
            ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_config_role_index');
        }

        return $this->render('role/edit.html.twig', array(
            'role' => $role,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a role entity.
     *
     */
    public function deleteAction(Request $request, Role $role)
    {
        $form = $this->createDeleteForm($role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($role);
            $em->flush();
        }

        return $this->redirectToRoute('admin_config_role_index');
    }

    /**
     * Creates a form to delete a role entity.
     *
     * @param Role $role The role entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Role $role)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_config_role_delete', array('id' => $role->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
