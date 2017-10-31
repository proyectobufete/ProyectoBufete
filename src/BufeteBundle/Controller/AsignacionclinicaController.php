<?php

namespace BufeteBundle\Controller;

use BufeteBundle\Entity\Asignacionclinica;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BufeteBundle\Form\AsignarNotaClinicaType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;

/**
 * Asignacionclinica controller.
 *
 */
class AsignacionclinicaController extends Controller
{

    private $session;

    public function __construct(){
      $this->session = new Session();
    }

    /**
     * Lists all asignacionclinica entities.
     *
     */
    public function indexAction()
    {
      $em = $this->getDoctrine()->getManager();
      $rol = $this->getUser()->getRole();
      if($rol == "ROLE_ADMIN")
      {
          $asignacionclinicas = $em->getRepository('BufeteBundle:Clinicas')->findAll();
      } elseif ($rol == "ROLE_SECRETARIO") {
          $bufete = $this->getUser()->getIdBufete()->getIdBufete();
          $repo = $em->getRepository("BufeteBundle:Clinicas");
          $query = $repo->createQueryBuilder('c')
          ->innerJoin('BufeteBundle:Personas', 'p', 'WITH', 'c.idPersona = p.idPersona')
          ->where('p.idBufete = :bufete')
          ->setParameter('bufete', $bufete)
          ->getQuery();
          $asignacionclinicas = $query->getResult();
        }

        return $this->render('asignacionclinica/index.html.twig', array(
            'asignacionclinicas' => $asignacionclinicas,
        ));
    }

    /**
     * Listado de Estudiantes por Clinica
     *
     */
      public function listEstudiantesAction(Request $request)
      {
        $clin = $request->get('idAsignacion');;
        $em = $this->getDoctrine()->getManager();
        $rol = $this->getUser()->getRole();
        if($rol == "ROLE_ADMIN")
        {
            $asignacionclinicas = $em->getRepository('BufeteBundle:Asignacionclinica')->findAll();
        } elseif ($rol == "ROLE_SECRETARIO") {
            $bufete = $this->getUser()->getIdBufete()->getIdBufete();
            $repo = $em->getRepository("BufeteBundle:Asignacionclinica");
            $query = $repo->createQueryBuilder('a')
            ->innerJoin('BufeteBundle:Clinicas', 'c', 'WITH', 'c.idClinica = a.idClinica')
            ->innerJoin('BufeteBundle:Personas', 'p', 'WITH', 'c.idPersona = p.idPersona')
            ->where('p.idBufete = :bufete')
            ->andWhere('c.idClinica = :cli')
            ->setParameter('bufete', $bufete)
            ->setParameter('cli', $clin)
            ->getQuery();
            $asignacionclinicas = $query->getResult();
          }

        return $this->render('asignacionclinica/listEstudiantes.html.twig', array(
            'asignacionclinicas' => $asignacionclinicas,
            'idAsignacion'=>$clin,
        ));

      }
      public function printnotaEstudiantesAction(Request $request)
      {
        $clin = $request->get('idAsignacion');
        $em = $this->getDoctrine()->getManager();
        $rol = $this->getUser()->getRole();
        if($rol == "ROLE_ADMIN")
        {
            $asignacionclinicas = $em->getRepository('BufeteBundle:Asignacionclinica')->findAll();
        } elseif ($rol == "ROLE_SECRETARIO") {
            $bufete = $this->getUser()->getIdBufete()->getIdBufete();
            $repo = $em->getRepository("BufeteBundle:Asignacionclinica");
            $query = $repo->createQueryBuilder('a')
            ->innerJoin('BufeteBundle:Clinicas', 'c', 'WITH', 'c.idClinica = a.idClinica')
            ->innerJoin('BufeteBundle:Personas', 'p', 'WITH', 'c.idPersona = p.idPersona')
            ->where('p.idBufete = :bufete')
            ->andWhere('c.idClinica = :cli')
            ->setParameter('bufete', $bufete)
            ->setParameter('cli', $clin)
            ->orderBy('a.idEstudiante', 'ASC')
            ->getQuery();
            $asignacionclinicas = $query->getResult();
          }
        $snappy = $this->get('knp_snappy.pdf');
        $snappy->setOption('no-outline', false);
        $snappy->setOption('encoding', 'UTF-8');
        $snappy->setOption('page-size','LEGAL');
        $snappy->setOption('footer-right','Página [page] de [topage]');
        $snappy->setOption('footer-font-size','10');
            $html = $this->renderView('asignacionclinica/printnotaestudiantes.html.twig', array('asignacionclinicas' => $asignacionclinicas));
            $filename = 'Listado'.$asignacionclinicas.get;
            return new Response(
                $snappy->getOutputFromHtml($html),
                200,
                array(
                    'Content-Type'          => 'application/pdf',
                    'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'));


      }
      public function printlistaEstudiantesAction(Request $request)
      {
        $clin = $request->get('idAsignacion');
        $em = $this->getDoctrine()->getManager();
        $rol = $this->getUser()->getRole();
        if($rol == "ROLE_ADMIN")
        {
            $asignacionclinicas = $em->getRepository('BufeteBundle:Asignacionclinica')->findAll();
        } elseif ($rol == "ROLE_SECRETARIO") {
            $bufete = $this->getUser()->getIdBufete()->getIdBufete();
            $repo = $em->getRepository("BufeteBundle:Asignacionclinica");
            $query = $repo->createQueryBuilder('a')
            ->innerJoin('BufeteBundle:Clinicas', 'c', 'WITH', 'c.idClinica = a.idClinica')
            ->innerJoin('BufeteBundle:Personas', 'p', 'WITH', 'c.idPersona = p.idPersona')
            ->where('p.idBufete = :bufete')
            ->andWhere('c.idClinica = :cli')
            ->setParameter('bufete', $bufete)
            ->setParameter('cli', $clin)
            ->orderBy('a.idEstudiante', 'ASC')
            ->getQuery();
            $asignacionclinicas = $query->getResult();
          }
        $snappy = $this->get('knp_snappy.pdf');
        $snappy->setOption('no-outline', false);
        $snappy->setOption('encoding', 'UTF-8');
        $snappy->setOption('page-size','LEGAL');
        $snappy->setOption('footer-right','Página [page] de [topage]');
        $snappy->setOption('footer-font-size','10');
            $html = $this->renderView('asignacionclinica/printlistaestudiantes.html.twig', array('asignacionclinicas' => $asignacionclinicas));
            $filename = 'CasoPDF';
            return new Response(
                $snappy->getOutputFromHtml($html),
                200,
                array(
                    'Content-Type'          => 'application/pdf',
                    'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'));


      }
      /**
       * Listado de Estudiantes por Clinica para vista asesor
       *
       */
        public function ListClinicasEstAsesorAction(Request $request)
        {
          $clin = $request->get('idAsignacion');;
          $em = $this->getDoctrine()->getManager();
          $rol = $this->getUser()->getRole();
          $per = $this->getUser()->getIdPersona();
          if ($rol == "ROLE_ASESOR") {
              $repo = $em->getRepository("BufeteBundle:Asignacionclinica");
              $query = $repo->createQueryBuilder('a')
              ->innerJoin('BufeteBundle:Clinicas', 'c', 'WITH', 'c.idClinica = a.idClinica')
              ->innerJoin('BufeteBundle:Personas', 'p', 'WITH', 'c.idPersona = p.idPersona')
              ->Where('c.idClinica = :cli')
              ->setParameter('cli', $clin)
              ->getQuery();
              $asignacionclinicas = $query->getResult();
          }

          return $this->render('asignacionclinica/ListClinicasEstAsesor.html.twig', array(
              'asignacionclinicas' => $asignacionclinicas,
              'per' => $per,
          ));
        }

    /**
     * Lists all asignacionclinica entities.
     *
     */
    public function clinicasAsesorAction()
    {
        $em = $this->getDoctrine()->getManager();
        $idpersona = $this->getUser()->getIdPersona();
        $repo = $em->getRepository("BufeteBundle:Clinicas");
        $query = $repo->createQueryBuilder('c')
        ->where('c.idPersona = :id')
        ->setParameter('id', $idpersona)
        ->getQuery();
        $clinicas = $query->getResult();

        return $this->render('asignacionclinica/clinicasasesor.html.twig', array(
            'clinicas' => $clinicas,
        ));
    }

    /**
     * Creates a new asignacionclinica entity.
     *
     */
    public function newAction(Request $request)
    {
        $asignacionclinica = new Asignacionclinica();
        $bufete = $this->getUser()->getIdBufete()->getIdBufete();
        $form = $this->createForm('BufeteBundle\Form\AsignacionclinicaType', $asignacionclinica, array('bufete'=> $bufete));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($asignacionclinica);
            $em->flush();

            return $this->redirectToRoute('asignacionclinica_show', array('idAsignacion' => $asignacionclinica->getIdasignacion()));
        }

        return $this->render('asignacionclinica/new.html.twig', array(
            'asignacionclinica' => $asignacionclinica,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a asignacionclinica entity.
     *
     */
    public function showAction(Asignacionclinica $asignacionclinica)
    {
        $deleteForm = $this->createDeleteForm($asignacionclinica);

        return $this->render('asignacionclinica/show.html.twig', array(
            'asignacionclinica' => $asignacionclinica,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing asignacionclinica entity.
     *
     */
    public function editAction(Request $request, Asignacionclinica $asignacionclinica)
    {
        $deleteForm = $this->createDeleteForm($asignacionclinica);
        $bufete = $this->getUser()->getIdBufete()->getIdBufete();
        $editForm = $this->createForm('BufeteBundle\Form\AsignacionclinicaType', $asignacionclinica, array('bufete'=> $bufete));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('asignacionclinica_edit', array('idAsignacion' => $asignacionclinica->getIdasignacion()));
        }

        return $this->render('asignacionclinica/edit.html.twig', array(
            'asignacionclinica' => $asignacionclinica,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing asignacionclinica entity.
     *
     */
    public function editNotaAction(Request $request, Asignacionclinica $asignacionclinica)
    {
      $em = $this->getDoctrine()->getManager();
      $nota = $request->get('nota');
//      $obs = $request->get('obs');
      $id = $request->get('id');
      $asignacion_repo = $em->getRepository("BufeteBundle:Asignacionclinica");
      $asignacionclinica = $asignacion_repo->findOneBy(array('idAsignacion' => $id));
      if($nota > 100 || $nota < 0)
      {
        $mensaje = "Debe asignar una nota entre 0 y 100";
        $this->session->getFlashBag()->add("status", $mensaje);
      } else {
        $editForm = $this->createForm('BufeteBundle\Form\AsignarNotaClinicaType', $asignacionclinica);
        $editForm->handleRequest($request);
        $asignacionclinica->setNotaClinica($nota);
        $this->getDoctrine()->getManager()->flush();
      }

  //    $asignacionclinica->setObservacionesClinica($obs);

//        if ($editForm->isSubmitted() && $editForm->isValid()) {
//            $this->getDoctrine()->getManager()->flush();
//
            //return $this->redirectToRoute('asignacionclinica_listClinicasEstAsesor', array('idAsignacion' => $asignacionclinica->getIdClinica()->getIdClinica()));
  //      }
    return $this->redirectToRoute('asignacionclinica_listClinicasEstAsesor', array('idAsignacion' => $asignacionclinica->getIdClinica()->getIdClinica()));

    //  return $this->render('asignacionclinica/editNota.html.twig', array(
  //      'asignacionclinica' => $asignacionclinica,
    //        'edit_form' => $editForm->createView(),
      //  ));
    }


    /**
     * Displays a form to edit an existing asignacionclinica entity.
     *
     */
/*    public function editNotaAction(Request $request, Asignacionclinica $asignacionclinica)
    {

      $editForm = $this->createForm('BufeteBundle\Form\AsignarNotaClinicaType', $asignacionclinica);
      $editForm->handleRequest($request);
      $hola = $asignacionclinica->getIdClinica()->getIdClinica();
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('asignacionclinica_listClinicasEstAsesor', array('idAsignacion' => $hola));
        }

        return $this->render('asignacionclinica/editNota.html.twig', array(
            'asignacionclinica' => $asignacionclinica,
            'edit_form' => $editForm->createView(),
        ));
    }*/

    /**
     * Deletes a asignacionclinica entity.
     *
     */
    public function deleteAction(Request $request, Asignacionclinica $asignacionclinica)
    {
        $form = $this->createDeleteForm($asignacionclinica);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($asignacionclinica);
            $em->flush();
        }

        return $this->redirectToRoute('asignacionclinica_index');
    }

    /**
     * Creates a form to delete a asignacionclinica entity.
     *
     * @param Asignacionclinica $asignacionclinica The asignacionclinica entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Asignacionclinica $asignacionclinica)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('asignacionclinica_delete', array('idAsignacion' => $asignacionclinica->getIdasignacion())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

}
