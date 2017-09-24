<?php

namespace BufeteBundle\Controller;

use BufeteBundle\Entity\Ciudad;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Ciudad controller.
 *
 */
class CiudadController extends Controller
{
    /**
     * Lists all ciudad entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $ciudads = $em->getRepository('BufeteBundle:Ciudad')->findAll();

        return $this->render('ciudad/index.html.twig', array(
            'ciudads' => $ciudads,
        ));
    }

    /**
     * Creates a new ciudad entity.
     *
     */
    public function newAction(Request $request)
    {
        $ciudad = new Ciudad();
        $form = $this->createForm('BufeteBundle\Form\CiudadType', $ciudad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ciudad);
            $em->flush();

            return $this->redirectToRoute('ciudad_show', array('idCiudad' => $ciudad->getIdciudad()));
        }

        return $this->render('ciudad/new.html.twig', array(
            'ciudad' => $ciudad,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ciudad entity.
     *
     */
    public function showAction(Ciudad $ciudad)
    {
        $deleteForm = $this->createDeleteForm($ciudad);

        return $this->render('ciudad/show.html.twig', array(
            'ciudad' => $ciudad,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ciudad entity.
     *
     */
    public function editAction(Request $request, Ciudad $ciudad)
    {
        $deleteForm = $this->createDeleteForm($ciudad);
        $editForm = $this->createForm('BufeteBundle\Form\CiudadType', $ciudad);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ciudad_edit', array('idCiudad' => $ciudad->getIdciudad()));
        }

        return $this->render('ciudad/edit.html.twig', array(
            'ciudad' => $ciudad,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ciudad entity.
     *
     */
    public function deleteAction(Request $request, Ciudad $ciudad)
    {
        $form = $this->createDeleteForm($ciudad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ciudad);
            $em->flush();
        }

        return $this->redirectToRoute('ciudad_index');
    }

    /**
     * Creates a form to delete a ciudad entity.
     *
     * @param Ciudad $ciudad The ciudad entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Ciudad $ciudad)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ciudad_delete', array('idCiudad' => $ciudad->getIdciudad())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
