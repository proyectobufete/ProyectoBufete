<?php

namespace BufeteBundle\Controller;

use BufeteBundle\Entity\Revisiones;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Revisione controller.
 *
 */
class RevisionesController extends Controller
{
    /**
     * Lists all revisione entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $revisiones = $em->getRepository('BufeteBundle:Revisiones')->findAll();

        return $this->render('revisiones/index.html.twig', array(
            'revisiones' => $revisiones,
        ));
    }

    /**
     * Creates a new revisione entity.
     *
     */
    public function newAction(Request $request)
    {
        $revisione = new Revisione();
        $form = $this->createForm('BufeteBundle\Form\RevisionesType', $revisione);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($revisione);
            $em->flush();

            return $this->redirectToRoute('revisiones_show', array('idRevision' => $revisione->getIdrevision()));
        }

        return $this->render('revisiones/new.html.twig', array(
            'revisione' => $revisione,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a revisione entity.
     *
     */
    public function showAction(Revisiones $revisione)
    {
        $deleteForm = $this->createDeleteForm($revisione);

        return $this->render('revisiones/show.html.twig', array(
            'revisione' => $revisione,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing revisione entity.
     *
     */
    public function editAction(Request $request, Revisiones $revisione)
    {
        $deleteForm = $this->createDeleteForm($revisione);
        $editForm = $this->createForm('BufeteBundle\Form\RevisionesType', $revisione);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('revisiones_edit', array('idRevision' => $revisione->getIdrevision()));
        }

        return $this->render('revisiones/edit.html.twig', array(
            'revisione' => $revisione,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a revisione entity.
     *
     */
    public function deleteAction(Request $request, Revisiones $revisione)
    {
        $form = $this->createDeleteForm($revisione);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($revisione);
            $em->flush();
        }

        return $this->redirectToRoute('revisiones_index');
    }

    /**
     * Creates a form to delete a revisione entity.
     *
     * @param Revisiones $revisione The revisione entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Revisiones $revisione)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('revisiones_delete', array('idRevision' => $revisione->getIdrevision())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
