<?php

namespace BufeteBundle\Controller;

use BufeteBundle\Entity\Tipoparcticante;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Tipoparcticante controller.
 *
 */
class TipoparcticanteController extends Controller
{
    /**
     * Lists all tipoparcticante entities.
     *
     */
     public function indexAction(Request $request)
     {
         $searchQuery = $request->get('query');
         if(!empty($searchQuery))
         {
           $em = $this->getDoctrine()->getManager();
           $query = $em->createQuery(
             "SELECT t FROM BufeteBundle:Tipoparcticante t WHERE t.tipopracticante like :name");
           $query->setParameter('name', '%'.$searchQuery.'%');
           $tipoparcticantes = $query->getResult();
         }
         else
         {
           $em = $this->getDoctrine()->getManager();
           $tipoparcticantes = $em->getRepository('BufeteBundle:Tipoparcticante')->findAll();
         }

         return $this->render('tipoparcticante/index.html.twig', array(
             'tipoparcticantes' => $tipoparcticantes,
         ));
     }


    /**
     * Creates a new tipoparcticante entity.
     *
     */
    public function newAction(Request $request)
    {
        $tipoparcticante = new Tipoparcticante();
        $form = $this->createForm('BufeteBundle\Form\TipoparcticanteType', $tipoparcticante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tipoparcticante);
            $em->flush();

            return $this->redirectToRoute('tipoparcticante_show', array('idtipopracticante' => $tipoparcticante->getIdtipopracticante()));
        }

        return $this->render('tipoparcticante/new.html.twig', array(
            'tipoparcticante' => $tipoparcticante,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a tipoparcticante entity.
     *
     */
    public function showAction(Tipoparcticante $tipoparcticante)
    {
        $deleteForm = $this->createDeleteForm($tipoparcticante);

        return $this->render('tipoparcticante/show.html.twig', array(
            'tipoparcticante' => $tipoparcticante,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing tipoparcticante entity.
     *
     */
    public function editAction(Request $request, Tipoparcticante $tipoparcticante)
    {
        $deleteForm = $this->createDeleteForm($tipoparcticante);
        $editForm = $this->createForm('BufeteBundle\Form\TipoparcticanteType', $tipoparcticante);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tipoparcticante_show', array('idtipopracticante' => $tipoparcticante->getIdtipopracticante()));
        }

        return $this->render('tipoparcticante/edit.html.twig', array(
            'tipoparcticante' => $tipoparcticante,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a tipoparcticante entity.
     *
     */
    public function deleteAction(Request $request, Tipoparcticante $tipoparcticante)
    {
        $form = $this->createDeleteForm($tipoparcticante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tipoparcticante);
            $em->flush();
        }

        return $this->redirectToRoute('tipoparcticante_index');
    }

    /**
     * Creates a form to delete a tipoparcticante entity.
     *
     * @param Tipoparcticante $tipoparcticante The tipoparcticante entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Tipoparcticante $tipoparcticante)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tipoparcticante_delete', array('idtipopracticante' => $tipoparcticante->getIdtipopracticante())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
