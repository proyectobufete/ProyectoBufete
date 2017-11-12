<?php

namespace BufeteBundle\Controller;

use BufeteBundle\Entity\Ciudad;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Ciudad controller.
 *
 */
class CiudadController extends Controller
{
    /**
     * Lists all ciudad entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $searchQuery = $request->get('query');
        $ciudads1 = null;
        if (!empty($searchQuery)) {
          $repo = $em->getRepository("BufeteBundle:Ciudad");
          $query = $repo->createQueryBuilder('c')
          ->innerJoin('BufeteBundle:Departamentos', 'd', 'WITH', 'd.idDepartamento = c.idDepartamento')
          ->where('c.ciudad LIKE :param')
          ->orWhere('d.departamento LIKE :param')
          ->setParameter('param', '%'.$searchQuery.'%')
          ->getQuery();
          $ciudads1 = $query->getResult();
        } else {
          $ciudads1 = $em->getRepository('BufeteBundle:Ciudad')->findAll();
        }
              $paginator = $this->get('knp_paginator');
              $ciudads = $paginator->paginate(
                  $ciudads1,
                  $request->query->getInt('page', 1), 5 );

        return $this->render('ciudad/index.html.twig', array(
            'ciudads' => $ciudads,
        ));
    }

    /**
     * Creates a new ciudad entity.
     *
     */
    public function newAction(Request $request)
    {
        $ciudad = new Ciudad();
        $form = $this->createForm('BufeteBundle\Form\CiudadType', $ciudad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ciudad);
            $em->flush();

            return $this->redirectToRoute('ciudad_show', array('idCiudad' => $ciudad->getIdciudad()));
        }

        return $this->render('ciudad/new.html.twig', array(
            'ciudad' => $ciudad,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ciudad entity.
     *
     */
    public function showAction(Ciudad $ciudad)
    {
        $deleteForm = $this->createDeleteForm($ciudad);

        return $this->render('ciudad/show.html.twig', array(
            'ciudad' => $ciudad,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ciudad entity.
     *
     */
    public function editAction(Request $request, Ciudad $ciudad)
    {
        $deleteForm = $this->createDeleteForm($ciudad);
        $editForm = $this->createForm('BufeteBundle\Form\CiudadType', $ciudad);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ciudad_edit', array('idCiudad' => $ciudad->getIdciudad()));
        }

        return $this->render('ciudad/edit.html.twig', array(
            'ciudad' => $ciudad,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ciudad entity.
     *
     */
    public function deleteAction(Request $request, Ciudad $ciudad)
    {
        $form = $this->createDeleteForm($ciudad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ciudad);
            $em->flush();
        }

        return $this->redirectToRoute('ciudad_index');
    }

    /**
     * Creates a form to delete a ciudad entity.
     *
     * @param Ciudad $ciudad The ciudad entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Ciudad $ciudad)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ciudad_delete', array('idCiudad' => $ciudad->getIdciudad())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
