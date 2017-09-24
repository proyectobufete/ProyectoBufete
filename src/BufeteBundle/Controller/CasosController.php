<?php

namespace BufeteBundle\Controller;

use BufeteBundle\Entity\Casos;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Caso controller.
 *
 */
class CasosController extends Controller
{
    /**
     * Lists all caso entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $casos = $em->getRepository('BufeteBundle:Casos')->findAll();

        return $this->render('casos/index.html.twig', array(
            'casos' => $casos,
        ));
    }

    /**
     * Creates a new caso entity.
     *
     */
    public function newAction(Request $request)
    {
        $caso = new Casos();
        $form = $this->createForm('BufeteBundle\Form\CasosType', $caso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($caso);
            $em->flush();

            return $this->redirectToRoute('casos_show', array('idCaso' => $caso->getIdcaso()));
        }

        return $this->render('casos/new.html.twig', array(
            'caso' => $caso,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a caso entity.
     *
     */
    public function showAction(Casos $caso)
    {
        $deleteForm = $this->createDeleteForm($caso);

        return $this->render('casos/show.html.twig', array(
            'caso' => $caso,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing caso entity.
     *
     */
    public function editAction(Request $request, Casos $caso)
    {
        $deleteForm = $this->createDeleteForm($caso);
        $editForm = $this->createForm('BufeteBundle\Form\CasosType', $caso);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('casos_edit', array('idCaso' => $caso->getIdcaso()));
        }

        return $this->render('casos/edit.html.twig', array(
            'caso' => $caso,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a caso entity.
     *
     */
    public function deleteAction(Request $request, Casos $caso)
    {
        $form = $this->createDeleteForm($caso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($caso);
            $em->flush();
        }

        return $this->redirectToRoute('casos_index');
    }

    /**
     * Creates a form to delete a caso entity.
     *
     * @param Casos $caso The caso entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Casos $caso)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('casos_delete', array('idCaso' => $caso->getIdcaso())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
