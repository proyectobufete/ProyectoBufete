<?php

namespace BufeteBundle\Controller;

use BufeteBundle\Entity\Paises;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Paise controller.
 *
 */
class PaisesController extends Controller
{
    /**
     * Lists all paise entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $paises = $em->getRepository('BufeteBundle:Paises')->findAll();

        return $this->render('paises/index.html.twig', array(
            'paises' => $paises,
        ));
    }

    /**
     * Creates a new paise entity.
     *
     */
    public function newAction(Request $request)
    {
        $paise = new Paises();
        $form = $this->createForm('BufeteBundle\Form\PaisesType', $paise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($paise);
            $em->flush();

            return $this->redirectToRoute('paises_show', array('idPais' => $paise->getIdpais()));
        }

        return $this->render('paises/new.html.twig', array(
            'paise' => $paise,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a paise entity.
     *
     */
    public function showAction(Paises $paise)
    {
        $deleteForm = $this->createDeleteForm($paise);

        return $this->render('paises/show.html.twig', array(
            'paise' => $paise,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing paise entity.
     *
     */
    public function editAction(Request $request, Paises $paise)
    {
        $deleteForm = $this->createDeleteForm($paise);
        $editForm = $this->createForm('BufeteBundle\Form\PaisesType', $paise);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('paises_edit', array('idPais' => $paise->getIdpais()));
        }

        return $this->render('paises/edit.html.twig', array(
            'paise' => $paise,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a paise entity.
     *
     */
    public function deleteAction(Request $request, Paises $paise)
    {
        $form = $this->createDeleteForm($paise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($paise);
            $em->flush();
        }

        return $this->redirectToRoute('paises_index');
    }

    /**
     * Creates a form to delete a paise entity.
     *
     * @param Paises $paise The paise entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Paises $paise)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('paises_delete', array('idPais' => $paise->getIdpais())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
