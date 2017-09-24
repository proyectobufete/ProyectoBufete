<?php

namespace BufeteBundle\Controller;

use BufeteBundle\Entity\Clinicas;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Clinica controller.
 *
 */
class ClinicasController extends Controller
{
    /**
     * Lists all clinica entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $clinicas = $em->getRepository('BufeteBundle:Clinicas')->findAll();

        return $this->render('clinicas/index.html.twig', array(
            'clinicas' => $clinicas,
        ));
    }

    /**
     * Creates a new clinica entity.
     *
     */
    public function newAction(Request $request)
    {
        $clinica = new Clinicas();
        $form = $this->createForm('BufeteBundle\Form\ClinicasType', $clinica);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($clinica);
            $em->flush();

            return $this->redirectToRoute('clinicas_show', array('idClinica' => $clinica->getIdclinica()));
        }

        return $this->render('clinicas/new.html.twig', array(
            'clinica' => $clinica,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a clinica entity.
     *
     */
    public function showAction(Clinicas $clinica)
    {
        $deleteForm = $this->createDeleteForm($clinica);

        return $this->render('clinicas/show.html.twig', array(
            'clinica' => $clinica,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing clinica entity.
     *
     */
    public function editAction(Request $request, Clinicas $clinica)
    {
        $deleteForm = $this->createDeleteForm($clinica);
        $editForm = $this->createForm('BufeteBundle\Form\ClinicasType', $clinica);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('clinicas_edit', array('idClinica' => $clinica->getIdclinica()));
        }

        return $this->render('clinicas/edit.html.twig', array(
            'clinica' => $clinica,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a clinica entity.
     *
     */
    public function deleteAction(Request $request, Clinicas $clinica)
    {
        $form = $this->createDeleteForm($clinica);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($clinica);
            $em->flush();
        }

        return $this->redirectToRoute('clinicas_index');
    }

    /**
     * Creates a form to delete a clinica entity.
     *
     * @param Clinicas $clinica The clinica entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Clinicas $clinica)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('clinicas_delete', array('idClinica' => $clinica->getIdclinica())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
