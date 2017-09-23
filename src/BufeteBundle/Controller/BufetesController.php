<?php

namespace BufeteBundle\Controller;

use BufeteBundle\Entity\Bufetes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Bufete controller.
 *
 */
class BufetesController extends Controller
{
    /**
     * Lists all bufete entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $bufetes = $em->getRepository('BufeteBundle:Bufetes')->findAll();

        return $this->render('bufetes/index.html.twig', array(
            'bufetes' => $bufetes,
        ));
    }

    /**
     * Creates a new bufete entity.
     *
     */
    public function newAction(Request $request)
    {
        $bufete = new Bufetes();
        $form = $this->createForm('BufeteBundle\Form\BufetesType', $bufete);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($bufete);
            $em->flush();

            return $this->redirectToRoute('bufetes_show', array('idBufete' => $bufete->getIdbufete()));
        }

        return $this->render('bufetes/new.html.twig', array(
            'bufete' => $bufete,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a bufete entity.
     *
     */
    public function showAction(Bufetes $bufete)
    {
        $deleteForm = $this->createDeleteForm($bufete);

        return $this->render('bufetes/show.html.twig', array(
            'bufete' => $bufete,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing bufete entity.
     *
     */
    public function editAction(Request $request, Bufetes $bufete)
    {
        $deleteForm = $this->createDeleteForm($bufete);
        $editForm = $this->createForm('BufeteBundle\Form\BufetesType', $bufete);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('bufetes_edit', array('idBufete' => $bufete->getIdbufete()));
        }

        return $this->render('bufetes/edit.html.twig', array(
            'bufete' => $bufete,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a bufete entity.
     *
     */
    public function deleteAction(Request $request, Bufetes $bufete)
    {
        $form = $this->createDeleteForm($bufete);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($bufete);
            $em->flush();
        }

        return $this->redirectToRoute('bufetes_index');
    }

    /**
     * Creates a form to delete a bufete entity.
     *
     * @param Bufetes $bufete The bufete entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Bufetes $bufete)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('bufetes_delete', array('idBufete' => $bufete->getIdbufete())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
