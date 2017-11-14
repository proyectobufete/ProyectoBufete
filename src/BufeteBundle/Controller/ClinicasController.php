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
    public function indexAction(Request $request)
    {

      $searchQuery = $request->get('query');
        $em = $this->getDoctrine()->getManager();
        $rol = $this->getUser()->getRole();

        $searchQuery = $request->get('query');
        $clinicas = null;
        if($rol == "ROLE_ADMIN" || $rol == "ROLE_DIRECTOR")
        {
            if (!empty($searchQuery)) {
              $repo = $em->getRepository("BufeteBundle:Clinicas");
              $query = $repo->createQueryBuilder('c')
              ->innerJoin('BufeteBundle:Personas', 'p', 'WITH', 'c.idPersona = p.idPersona')
              ->Where('c.nombreClinica LIKE :param')
              ->orWhere('c.fechaAsignacion LIKE :param')
              ->orWhere('p.nombrePersona LIKE :param')
              ->setParameter('param', '%'.$searchQuery.'%')
              ->getQuery();
              $clinicas = $query->getResult();
            } else {
              $clinicas = $em->getRepository('BufeteBundle:Clinicas')->findAll();
            }
        } elseif ($rol == "ROLE_SECRETARIO") {
          $bufete = $this->getUser()->getIdBufete()->getIdBufete();
            if (!empty($searchQuery)) {
              $repo = $em->getRepository("BufeteBundle:Clinicas");
              $query = $repo->createQueryBuilder('c')
              ->innerJoin('BufeteBundle:Personas', 'p', 'WITH', 'c.idPersona = p.idPersona')
              ->Where('c.nombreClinica LIKE :param')
              ->orWhere('c.fechaAsignacion LIKE :param')
              ->orWhere('p.nombrePersona LIKE :param')
              ->andWhere('p.idBufete = :bufete')
              ->setParameter('bufete', $bufete)
              ->setParameter('param', '%'.$searchQuery.'%')
              ->getQuery();
              $clinicas = $query->getResult();
            } else {
              $repo = $em->getRepository("BufeteBundle:Clinicas");
              $query = $repo->createQueryBuilder('c')
              ->innerJoin('BufeteBundle:Personas', 'p', 'WITH', 'c.idPersona = p.idPersona')
              ->where('p.idBufete = :bufete')
              ->setParameter('bufete', $bufete)
              ->getQuery();
              $clinicas = $query->getResult();
            }

          }

          $paginator = $this->get('knp_paginator');
          $clinicaspg = $paginator->paginate(
              $clinicas,
              $request->query->getInt('page', 1), 10 );

        return $this->render('clinicas/index.html.twig', array(
            'clinicas' => $clinicaspg,
        ));
    }

    /**
     * Creates a new clinica entity.
     *
     */
    public function newAction(Request $request)
    {
        $clinica = new Clinicas();
        $idciudad = $this->getUser()->getIdBufete()->getIdCiudad()->getIdCiudad();
        $form = $this->createForm('BufeteBundle\Form\ClinicasType', $clinica, array('idciudad'=> $idciudad));
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
        $idciudad = $this->getUser()->getIdBufete()->getIdCiudad()->getIdCiudad();
        $editForm = $this->createForm('BufeteBundle\Form\ClinicasType', $clinica, array('idciudad'=> $idciudad));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('clinicas_show', array('idClinica' => $clinica->getIdclinica()));
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
