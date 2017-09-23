<?php

namespace BufeteBundle\Controller;

use BufeteBundle\Entity\Laborales;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Laborale controller.
 *
 */
class LaboralesController extends Controller
{
    /**
     * Lists all laborale entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $laborales = $em->getRepository('BufeteBundle:Laborales')->findAll();

        return $this->render('laborales/index.html.twig', array(
            'laborales' => $laborales,
        ));
    }

    /**
     * Creates a new laborale entity.
     *
     */
    public function newAction(Request $request)
    {
        $laborale = new Laborales();
        $form = $this->createForm('BufeteBundle\Form\LaboralesType', $laborale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($laborale);
            $em->flush();

            return $this->redirectToRoute('laborales_show', array('idTipolaboral' => $laborale->getIdtipolaboral()));
        }

        return $this->render('laborales/new.html.twig', array(
            'laborale' => $laborale,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a laborale entity.
     *
     */
    public function showAction(Laborales $laborale)
    {
        $deleteForm = $this->createDeleteForm($laborale);

        return $this->render('laborales/show.html.twig', array(
            'laborale' => $laborale,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing laborale entity.
     *
     */
    public function editAction(Request $request, Laborales $laborale)
    {
        $deleteForm = $this->createDeleteForm($laborale);
        $editForm = $this->createForm('BufeteBundle\Form\LaboralesType', $laborale);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('laborales_edit', array('idTipolaboral' => $laborale->getIdtipolaboral()));
        }

        return $this->render('laborales/edit.html.twig', array(
            'laborale' => $laborale,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a laborale entity.
     *
     */
    public function deleteAction(Request $request, Laborales $laborale)
    {
        $form = $this->createDeleteForm($laborale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($laborale);
            $em->flush();
        }

        return $this->redirectToRoute('laborales_index');
    }

    /**
     * Creates a form to delete a laborale entity.
     *
     * @param Laborales $laborale The laborale entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Laborales $laborale)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('laborales_delete', array('idTipolaboral' => $laborale->getIdtipolaboral())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
