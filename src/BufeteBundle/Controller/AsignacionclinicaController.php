<?php

namespace BufeteBundle\Controller;

use BufeteBundle\Entity\Asignacionclinica;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Asignacionclinica controller.
 *
 */
class AsignacionclinicaController extends Controller
{
  
    /**
     * Lists all asignacionclinica entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $asignacionclinicas = $em->getRepository('BufeteBundle:Asignacionclinica')->findAll();

        return $this->render('asignacionclinica/index.html.twig', array(
            'asignacionclinicas' => $asignacionclinicas,
        ));
    }

    /**
     * Creates a new asignacionclinica entity.
     *
     */
    public function newAction(Request $request)
    {
        $asignacionclinica = new Asignacionclinica();
        $form = $this->createForm('BufeteBundle\Form\AsignacionclinicaType', $asignacionclinica);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($asignacionclinica);
            $em->flush();

            return $this->redirectToRoute('asignacionclinica_show', array('idAsignacion' => $asignacionclinica->getIdasignacion()));
        }

        return $this->render('asignacionclinica/new.html.twig', array(
            'asignacionclinica' => $asignacionclinica,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a asignacionclinica entity.
     *
     */
    public function showAction(Asignacionclinica $asignacionclinica)
    {
        $deleteForm = $this->createDeleteForm($asignacionclinica);

        return $this->render('asignacionclinica/show.html.twig', array(
            'asignacionclinica' => $asignacionclinica,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing asignacionclinica entity.
     *
     */
    public function editAction(Request $request, Asignacionclinica $asignacionclinica)
    {
        $deleteForm = $this->createDeleteForm($asignacionclinica);
        $editForm = $this->createForm('BufeteBundle\Form\AsignacionclinicaType', $asignacionclinica);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('asignacionclinica_edit', array('idAsignacion' => $asignacionclinica->getIdasignacion()));
        }

        return $this->render('asignacionclinica/edit.html.twig', array(
            'asignacionclinica' => $asignacionclinica,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a asignacionclinica entity.
     *
     */
    public function deleteAction(Request $request, Asignacionclinica $asignacionclinica)
    {
        $form = $this->createDeleteForm($asignacionclinica);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($asignacionclinica);
            $em->flush();
        }

        return $this->redirectToRoute('asignacionclinica_index');
    }

    /**
     * Creates a form to delete a asignacionclinica entity.
     *
     * @param Asignacionclinica $asignacionclinica The asignacionclinica entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Asignacionclinica $asignacionclinica)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('asignacionclinica_delete', array('idAsignacion' => $asignacionclinica->getIdasignacion())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
