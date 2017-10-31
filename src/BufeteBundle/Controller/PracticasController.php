<?php

namespace BufeteBundle\Controller;

use BufeteBundle\Entity\Practicas;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Practica controller.
 *
 */
class PracticasController extends Controller
{
    /**
     * Lists all practica entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $practicas = $em->getRepository('BufeteBundle:Practicas')->findAll();

        return $this->render('practicas/index.html.twig', array(
            'practicas' => $practicas,
        ));
    }

    /**
     * Creates a new practica entity.
     *
     */
    public function newAction(Request $request)
    {
        $practica = new Practicas();
        $form = $this->createForm('BufeteBundle\Form\PracticasType', $practica);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($practica);
            $em->flush();

            return $this->redirectToRoute('practicas_show', array('idPractica' => $practica->getIdpractica()));
        }

        return $this->render('practicas/new.html.twig', array(
            'practica' => $practica,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a practica entity.
     *
     */
    public function showAction(Practicas $practica)
    {
        $deleteForm = $this->createDeleteForm($practica);

        return $this->render('practicas/show.html.twig', array(
            'practica' => $practica,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing practica entity.
     *
     */
    public function editAction(Request $request, Practicas $practica)
    {
        $deleteForm = $this->createDeleteForm($practica);
        $editForm = $this->createForm('BufeteBundle\Form\PracticasType', $practica);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('practicas_edit', array('idPractica' => $practica->getIdpractica()));
        }

        return $this->render('practicas/edit.html.twig', array(
            'practica' => $practica,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a practica entity.
     *
     */
    public function deleteAction(Request $request, Practicas $practica)
    {
        $form = $this->createDeleteForm($practica);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($practica);
            $em->flush();
        }

        return $this->redirectToRoute('practicas_index');
    }

    /**
     * Creates a form to delete a practica entity.
     *
     * @param Practicas $practica The practica entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Practicas $practica)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('practicas_delete', array('idPractica' => $practica->getIdpractica())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
