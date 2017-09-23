<?php

namespace BufeteBundle\Controller;

use BufeteBundle\Entity\Trabajos;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Trabajo controller.
 *
 */
class TrabajosController extends Controller
{
    /**
     * Lists all trabajo entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $trabajos = $em->getRepository('BufeteBundle:Trabajos')->findAll();

        return $this->render('trabajos/index.html.twig', array(
            'trabajos' => $trabajos,
        ));
    }

    /**
     * Creates a new trabajo entity.
     *
     */
    public function newAction(Request $request)
    {
        $trabajo = new Trabajos();
        $form = $this->createForm('BufeteBundle\Form\TrabajosType', $trabajo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($trabajo);
            $em->flush();

            return $this->redirectToRoute('trabajos_show', array('idTrabajo' => $trabajo->getIdtrabajo()));
        }

        return $this->render('trabajos/new.html.twig', array(
            'trabajo' => $trabajo,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a trabajo entity.
     *
     */
    public function showAction(Trabajos $trabajo)
    {
        $deleteForm = $this->createDeleteForm($trabajo);

        return $this->render('trabajos/show.html.twig', array(
            'trabajo' => $trabajo,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing trabajo entity.
     *
     */
    public function editAction(Request $request, Trabajos $trabajo)
    {
        $deleteForm = $this->createDeleteForm($trabajo);
        $editForm = $this->createForm('BufeteBundle\Form\TrabajosType', $trabajo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('trabajos_edit', array('idTrabajo' => $trabajo->getIdtrabajo()));
        }

        return $this->render('trabajos/edit.html.twig', array(
            'trabajo' => $trabajo,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a trabajo entity.
     *
     */
    public function deleteAction(Request $request, Trabajos $trabajo)
    {
        $form = $this->createDeleteForm($trabajo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($trabajo);
            $em->flush();
        }

        return $this->redirectToRoute('trabajos_index');
    }

    /**
     * Creates a form to delete a trabajo entity.
     *
     * @param Trabajos $trabajo The trabajo entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Trabajos $trabajo)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('trabajos_delete', array('idTrabajo' => $trabajo->getIdtrabajo())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
