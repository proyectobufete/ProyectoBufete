<?php

namespace BufeteBundle\Controller;

use BufeteBundle\Entity\Civiles;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Civile controller.
 *
 */
class CivilesController extends Controller
{
    /**
     * Lists all civile entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $civiles = $em->getRepository('BufeteBundle:Civiles')->findAll();

        return $this->render('civiles/index.html.twig', array(
            'civiles' => $civiles,
        ));
    }

    /**
     * Creates a new civile entity.
     *
     */
    public function newAction(Request $request)
    {
        $civile = new Civiles();
        $form = $this->createForm('BufeteBundle\Form\CivilesType', $civile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($civile);
            $em->flush();

            return $this->redirectToRoute('civiles_show', array('idCivil' => $civile->getIdcivil()));
        }

        return $this->render('civiles/new.html.twig', array(
            'civile' => $civile,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a civile entity.
     *
     */
    public function showAction(Civiles $civile)
    {
        $deleteForm = $this->createDeleteForm($civile);

        return $this->render('civiles/show.html.twig', array(
            'civile' => $civile,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing civile entity.
     *
     */
    public function editAction(Request $request, Civiles $civile)
    {
        $deleteForm = $this->createDeleteForm($civile);
        $editForm = $this->createForm('BufeteBundle\Form\CivilesType', $civile);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('civiles_edit', array('idCivil' => $civile->getIdcivil()));
        }

        return $this->render('civiles/edit.html.twig', array(
            'civile' => $civile,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a civile entity.
     *
     */
    public function deleteAction(Request $request, Civiles $civile)
    {
        $form = $this->createDeleteForm($civile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($civile);
            $em->flush();
        }

        return $this->redirectToRoute('civiles_index');
    }

    /**
     * Creates a form to delete a civile entity.
     *
     * @param Civiles $civile The civile entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Civiles $civile)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('civiles_delete', array('idCivil' => $civile->getIdcivil())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
