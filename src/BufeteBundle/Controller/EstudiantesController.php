<?php

namespace BufeteBundle\Controller;

use BufeteBundle\Entity\Estudiantes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Estudiante controller.
 *
 */
class EstudiantesController extends Controller
{
    /**
     * Lists all estudiante entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $estudiantes = $em->getRepository('BufeteBundle:Estudiantes')->findAll();

        return $this->render('estudiantes/index.html.twig', array(
            'estudiantes' => $estudiantes,
        ));
    }

    /**
     * Creates a new estudiante entity.
     *
     */
    public function newAction(Request $request)
    {
        $estudiante = new Estudiante();
        $form = $this->createForm('BufeteBundle\Form\EstudiantesType', $estudiante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($estudiante);
            $em->flush();

            return $this->redirectToRoute('estudiantes_show', array('idEstudiante' => $estudiante->getIdestudiante()));
        }

        return $this->render('estudiantes/new.html.twig', array(
            'estudiante' => $estudiante,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a estudiante entity.
     *
     */
    public function showAction(Estudiantes $estudiante)
    {
        $deleteForm = $this->createDeleteForm($estudiante);

        return $this->render('estudiantes/show.html.twig', array(
            'estudiante' => $estudiante,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing estudiante entity.
     *
     */
    public function editAction(Request $request, Estudiantes $estudiante)
    {
        $deleteForm = $this->createDeleteForm($estudiante);
        $editForm = $this->createForm('BufeteBundle\Form\EstudiantesType', $estudiante);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('estudiantes_edit', array('idEstudiante' => $estudiante->getIdestudiante()));
        }

        return $this->render('estudiantes/edit.html.twig', array(
            'estudiante' => $estudiante,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a estudiante entity.
     *
     */
    public function deleteAction(Request $request, Estudiantes $estudiante)
    {
        $form = $this->createDeleteForm($estudiante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($estudiante);
            $em->flush();
        }

        return $this->redirectToRoute('estudiantes_index');
    }

    /**
     * Creates a form to delete a estudiante entity.
     *
     * @param Estudiantes $estudiante The estudiante entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Estudiantes $estudiante)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('estudiantes_delete', array('idEstudiante' => $estudiante->getIdestudiante())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
