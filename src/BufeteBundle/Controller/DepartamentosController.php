<?php

namespace BufeteBundle\Controller;

use BufeteBundle\Entity\Departamentos;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Departamento controller.
 *
 */
class DepartamentosController extends Controller
{
    /**
     * Lists all departamento entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $departamentos = $em->getRepository('BufeteBundle:Departamentos')->findAll();

        return $this->render('departamentos/index.html.twig', array(
            'departamentos' => $departamentos,
        ));
    }

    /**
     * Creates a new departamento entity.
     *
     */
    public function newAction(Request $request)
    {
        $departamento = new Departamentos();
        $form = $this->createForm('BufeteBundle\Form\DepartamentosType', $departamento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($departamento);
            $em->flush();

            return $this->redirectToRoute('departamentos_show', array('idDepartamento' => $departamento->getIddepartamento()));
        }

        return $this->render('departamentos/new.html.twig', array(
            'departamento' => $departamento,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a departamento entity.
     *
     */
    public function showAction(Departamentos $departamento)
    {
        $deleteForm = $this->createDeleteForm($departamento);

        return $this->render('departamentos/show.html.twig', array(
            'departamento' => $departamento,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing departamento entity.
     *
     */
    public function editAction(Request $request, Departamentos $departamento)
    {
        $deleteForm = $this->createDeleteForm($departamento);
        $editForm = $this->createForm('BufeteBundle\Form\DepartamentosType', $departamento);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('departamentos_edit', array('idDepartamento' => $departamento->getIddepartamento()));
        }

        return $this->render('departamentos/edit.html.twig', array(
            'departamento' => $departamento,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a departamento entity.
     *
     */
    public function deleteAction(Request $request, Departamentos $departamento)
    {
        $form = $this->createDeleteForm($departamento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($departamento);
            $em->flush();
        }

        return $this->redirectToRoute('departamentos_index');
    }

    /**
     * Creates a form to delete a departamento entity.
     *
     * @param Departamentos $departamento The departamento entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Departamentos $departamento)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('departamentos_delete', array('idDepartamento' => $departamento->getIddepartamento())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
