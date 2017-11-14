<?php

namespace BufeteBundle\Controller;

use BufeteBundle\Entity\Tipoasunto;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Tipoasunto controller.
 *
 */
class TipoasuntoController extends Controller
{
    /**
     * Lists all tipoasunto entities.
     *
     */
     public function indexAction(Request $request)
     {
         $searchQuery = $request->get('query');
         if(!empty($searchQuery))
         {
           $em = $this->getDoctrine()->getManager();
           $query = $em->createQuery(
             "SELECT t FROM BufeteBundle:Tipoasunto t WHERE t.asunto like :name");
           $query->setParameter('name', '%'.$searchQuery.'%');
           $tipoasuntos = $query->getResult();
         }
         else
         {
           $em = $this->getDoctrine()->getManager();
           $tipoasuntos = $em->getRepository('BufeteBundle:Tipoasunto')->findAll();
         }

         $paginator = $this->get('knp_paginator');
         $tipoasuntospg = $paginator->paginate(
             $tipoasuntos,
             $request->query->getInt('page', 1), 10 );

         return $this->render('tipoasunto/index.html.twig', array(
             'tipoasuntos' => $tipoasuntospg,
         ));
     }

    /**
     * Creates a new tipoasunto entity.
     *
     */
    public function newAction(Request $request)
    {
        $tipoasunto = new Tipoasunto();
        $form = $this->createForm('BufeteBundle\Form\TipoasuntoType', $tipoasunto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tipoasunto);
            $em->flush();

            return $this->redirectToRoute('tipoasunto_show', array('idTipoasunto' => $tipoasunto->getIdtipoasunto()));
        }

        return $this->render('tipoasunto/new.html.twig', array(
            'tipoasunto' => $tipoasunto,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a tipoasunto entity.
     *
     */
    public function showAction(Tipoasunto $tipoasunto)
    {
        $deleteForm = $this->createDeleteForm($tipoasunto);

        return $this->render('tipoasunto/show.html.twig', array(
            'tipoasunto' => $tipoasunto,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing tipoasunto entity.
     *
     */
    public function editAction(Request $request, Tipoasunto $tipoasunto)
    {
        $deleteForm = $this->createDeleteForm($tipoasunto);
        $editForm = $this->createForm('BufeteBundle\Form\TipoasuntoType', $tipoasunto);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tipoasunto_show', array('idTipoasunto' => $tipoasunto->getIdtipoasunto()));
        }

        return $this->render('tipoasunto/edit.html.twig', array(
            'tipoasunto' => $tipoasunto,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a tipoasunto entity.
     *
     */
    public function deleteAction(Request $request, Tipoasunto $tipoasunto)
    {
        $form = $this->createDeleteForm($tipoasunto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tipoasunto);
            $em->flush();
        }

        return $this->redirectToRoute('tipoasunto_index');
    }

    /**
     * Creates a form to delete a tipoasunto entity.
     *
     * @param Tipoasunto $tipoasunto The tipoasunto entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Tipoasunto $tipoasunto)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tipoasunto_delete', array('idTipoasunto' => $tipoasunto->getIdtipoasunto())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
