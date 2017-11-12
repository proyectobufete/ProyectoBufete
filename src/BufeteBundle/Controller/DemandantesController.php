<?php

namespace BufeteBundle\Controller;

use BufeteBundle\Entity\Demandantes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Demandante controller.
 *
 */
class DemandantesController extends Controller
{
    /**
     * Lists all demandante entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $searchQuery = $request->get('query');
        $rol = $this->getUser()->getRole();
        $demandantes = null;
        if ($rol == "ROLE_ADMIN") {
          if (!empty($searchQuery)) {
            $repo = $em->getRepository("BufeteBundle:Demandantes");
            $query = $repo->createQueryBuilder('d')
            ->orWhere('d.nombreDemandante LIKE :param')
            ->orWhere('d.dpiDemandante LIKE :param')
            ->setParameter('param', '%'.$searchQuery.'%')
            ->getQuery();
            $demandantes = $query->getResult();
          } else {
            $demandantes = $em->getRepository('BufeteBundle:Demandantes')->findAll();
          }
        } elseif ($rol == "ROLE_SECRETARIO") {
          $ciudad = $this->getUser()->getIdBufete()->getIdCiudad();
          if (strlen($searchQuery) > 1) {
            $repo = $em->getRepository("BufeteBundle:Demandantes");
            $query = $repo->createQueryBuilder('d')
            ->orWhere('d.nombreDemandante LIKE :param')
            ->orWhere('d.dpiDemandante LIKE :param')
            ->andWhere('d.idCiudad = :ciudad')
            ->setParameter('ciudad', $ciudad)
            ->setParameter('param', '%'.$searchQuery.'%')
            ->getQuery();
            $demandantes = $query->getResult();
          } else {
            $query = $em->createQuery(
              "SELECT d FROM BufeteBundle:Demandantes d
              WHERE d.idCiudad = :id"
            )->setParameter('id', $ciudad);
            $demandantes = $query->getResult();
          }
        }

        $paginator = $this->get('knp_paginator');
        $demandantespg = $paginator->paginate(
            $demandantes,
            $request->query->getInt('page', 1), 10 );

        return $this->render('demandantes/index.html.twig', array(
            'demandantes' => $demandantespg,
        ));
    }

    /**
     * Creates a new demandante por admin entity.
     *
     */
    public function newAction(Request $request)
    {
        $demandante = new Demandantes();
        $form = $this->createForm('BufeteBundle\Form\DemandantesType', $demandante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($demandante);
            $em->flush();

            return $this->redirectToRoute('demandantes_show', array('idDemandante' => $demandante->getIddemandante()));
        }

        return $this->render('demandantes/new.html.twig', array(
            'demandante' => $demandante,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new demandante por secretario entity.
     *
     */
    public function demandantesecreAction(Request $request)
    {
        $demandante = new Demandantes();
        $ciudad = $this->getUser()->getIdBufete()->getIdCiudad()->getIdCiudad();
        $form = $this->createForm('BufeteBundle\Form\DemandantesecreType', $demandante);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            $ciudad_repo = $em->getRepository("BufeteBundle:Ciudad");
            $city = $ciudad_repo->find($ciudad);
            $demandante->setIdCiudad($city);

            $em->persist($demandante);
            $em->flush();

            return $this->redirectToRoute('demandantes_show', array('idDemandante' => $demandante->getIddemandante()));
        }

        return $this->render('demandantes/newdemandante.html.twig', array(
            'demandante' => $demandante,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a demandante entity.
     *
     */
    public function showAction(Demandantes $demandante)
    {
        $deleteForm = $this->createDeleteForm($demandante);

        return $this->render('demandantes/show.html.twig', array(
            'demandante' => $demandante,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing demandante entity.
     *
     */
    public function editAction(Request $request, Demandantes $demandante)
    {
        $deleteForm = $this->createDeleteForm($demandante);
        $editForm = $this->createForm('BufeteBundle\Form\DemandantesType', $demandante);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('demandantes_edit', array('idDemandante' => $demandante->getIddemandante()));
        }

        return $this->render('demandantes/edit.html.twig', array(
            'demandante' => $demandante,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Editar demandante con secretario entity.
     *
     */
    public function editdemandanteAction(Request $request, Demandantes $demandante)
    {
        $deleteForm = $this->createDeleteForm($demandante);
        $editForm = $this->createForm('BufeteBundle\Form\DemandantesecreType', $demandante);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('demandantesecre_edit', array('idDemandante' => $demandante->getIddemandante()));
        }

        return $this->render('demandantes/editdemandante.html.twig', array(
            'demandante' => $demandante,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a demandante entity.
     *
     */
    public function deleteAction(Request $request, Demandantes $demandante)
    {
        $form = $this->createDeleteForm($demandante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($demandante);
            $em->flush();
        }

        return $this->redirectToRoute('demandantes_index');
    }

    /**
     * Creates a form to delete a demandante entity.
     *
     * @param Demandantes $demandante The demandante entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Demandantes $demandante)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('demandantes_delete', array('idDemandante' => $demandante->getIddemandante())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
