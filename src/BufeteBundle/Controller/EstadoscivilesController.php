<?php

namespace BufeteBundle\Controller;

use BufeteBundle\Entity\Estadosciviles;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Estadoscivile controller.
 *
 */
class EstadoscivilesController extends Controller
{
    /**
     * Lists all estadoscivile entities.
     *
     */
     public function indexAction(Request $request)
     {
         $searchQuery = $request->get('query');
         if(!empty($searchQuery))
         {
           $em = $this->getDoctrine()->getManager();
           $query = $em->createQuery(
             "SELECT e FROM BufeteBundle:Estadosciviles e WHERE e.estadocivil like :name");
           $query->setParameter('name', '%'.$searchQuery.'%');
           $estadosciviles = $query->getResult();
         }
         else
         {
           $em = $this->getDoctrine()->getManager();
           $estadosciviles = $em->getRepository('BufeteBundle:Estadosciviles')->findAll();
         }

         $paginator = $this->get('knp_paginator');
         $estadoscivilespg = $paginator->paginate(
             $estadosciviles,
             $request->query->getInt('page', 1), 5 );

         return $this->render('estadosciviles/index.html.twig', array(
             'estadosciviles' => $estadoscivilespg,
         ));
     }


    /**
     * Creates a new estadoscivile entity.
     *
     */
    public function newAction(Request $request)
    {
        $estadoscivile = new Estadosciviles();
        $form = $this->createForm('BufeteBundle\Form\EstadoscivilesType', $estadoscivile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($estadoscivile);
            $em->flush();

            return $this->redirectToRoute('estadosciviles_show', array('idEstadocivil' => $estadoscivile->getIdestadocivil()));
        }

        return $this->render('estadosciviles/new.html.twig', array(
            'estadoscivile' => $estadoscivile,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a estadoscivile entity.
     *
     */
    public function showAction(Estadosciviles $estadoscivile)
    {
        $deleteForm = $this->createDeleteForm($estadoscivile);

        return $this->render('estadosciviles/show.html.twig', array(
            'estadoscivile' => $estadoscivile,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing estadoscivile entity.
     *
     */
    public function editAction(Request $request, Estadosciviles $estadoscivile)
    {
        $deleteForm = $this->createDeleteForm($estadoscivile);
        $editForm = $this->createForm('BufeteBundle\Form\EstadoscivilesType', $estadoscivile);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('estadosciviles_edit', array('idEstadocivil' => $estadoscivile->getIdestadocivil()));
        }

        return $this->render('estadosciviles/edit.html.twig', array(
            'estadoscivile' => $estadoscivile,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a estadoscivile entity.
     *
     */
    public function deleteAction(Request $request, Estadosciviles $estadoscivile)
    {
        $form = $this->createDeleteForm($estadoscivile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($estadoscivile);
            $em->flush();
        }

        return $this->redirectToRoute('estadosciviles_index');
    }

    /**
     * Creates a form to delete a estadoscivile entity.
     *
     * @param Estadosciviles $estadoscivile The estadoscivile entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Estadosciviles $estadoscivile)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('estadosciviles_delete', array('idEstadocivil' => $estadoscivile->getIdestadocivil())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
