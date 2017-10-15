<?php

namespace BufeteBundle\Controller;

use BufeteBundle\Entity\Casos;
use BufeteBundle\Entity\Laborales;
use BufeteBundle\Entity\Civiles;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Twig_Autoloader;



/**
 * Caso controller.
 *
 */
class CasosController extends Controller
{

    private $session;
    public function __construct(){
      $this->session = new Session();
    }

    /**
     * Listar casos laborales.
     *
     */
    public function indexLaboralesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ciudad = $this->getUser()->getIdBufete()->getIdCiudad()->getIdCiudad();
        $rol = $this->getUser()->getRole();
        $casos = null;
        if($rol == "ROLE_SECRETARIO")
        {
            $searchQuery = $request->get('query');
            if(!empty($searchQuery)){
                $repo = $em->getRepository("BufeteBundle:Casos");
                $query = $repo->createQueryBuilder('c')
                ->innerJoin('BufeteBundle:Laborales', 'l', 'WITH', 'c.idCaso = l.idCaso')
                ->innerJoin('BufeteBundle:Demandantes', 'd', 'WITH', 'c.idDemandante = d.idDemandante')
                ->where('d.idCiudad = :ciudad')
                ->andWhere('c.estadoCaso = :opcion')
                ->setParameter('ciudad', $ciudad)
                ->setParameter('opcion', $searchQuery)
                ->orderBy('c.fechaCaso', 'DESC')
                ->getQuery();
                $casos = $query->getResult();
            } else {
                $repo = $em->getRepository("BufeteBundle:Casos");
                $query = $repo->createQueryBuilder('c')
                ->innerJoin('BufeteBundle:Laborales', 'l', 'WITH', 'c.idCaso = l.idCaso')
                ->innerJoin('BufeteBundle:Demandantes', 'd', 'WITH', 'c.idDemandante = d.idDemandante')
                ->where('d.idCiudad = :ciudad')
                ->setParameter('ciudad', $ciudad)
                ->orderBy('c.fechaCaso', 'DESC')
                ->getQuery();
                $casos = $query->getResult();
            }
        } elseif ($rol == "ROLE_ADMIN") {
            $searchQuery = $request->get('query');
            if(!empty($searchQuery)){
                $query = $em->createQuery(
                  "SELECT c FROM BufeteBundle:Casos c
                    INNER JOIN BufeteBundle:Laborales l WITH c = l.idCaso
                    WHERE c.estadoCaso = :opcion
                    ORDER BY c.fechaCaso DESC");
                $query->setParameter('opcion', $searchQuery);
                $casos = $query->getResult();
            } else {
              $query = $em->createQuery(
                "SELECT c FROM BufeteBundle:Casos c
                INNER JOIN BufeteBundle:Laborales l WITH c = l.idCaso
                ORDER BY c.fechaCaso DESC"
              );
              $casos = $query->getResult();
            }
        }

        return $this->render('casos/indexlaborales.html.twig', array(
            'casos' => $casos,
        ));
    }

    /**
     * Listar casos civiles.
     *
     */
    public function indexCivilesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ciudad = $this->getUser()->getIdBufete()->getIdCiudad()->getIdCiudad();
        $rol = $this->getUser()->getRole();
        $casos = null;
        if($rol == "ROLE_SECRETARIO")
        {
            $searchQuery = $request->get('query');
            if(!empty($searchQuery))
            {
                $repo = $em->getRepository("BufeteBundle:Casos");
                $query = $repo->createQueryBuilder('c')
                ->innerJoin('BufeteBundle:Civiles', 'ci', 'WITH', 'c.idCaso = ci.idCaso')
                ->innerJoin('BufeteBundle:Demandantes', 'd', 'WITH', 'c.idDemandante = d.idDemandante')
                ->where('d.idCiudad = :ciudad')
                ->andWhere('c.estadoCaso = :opcion')
                ->setParameter('ciudad', $ciudad)
                ->setParameter('opcion', $searchQuery)
                ->orderBy('c.fechaCaso', 'DESC')
                ->getQuery();
                $casos = $query->getResult();
            } else{
                $repo = $em->getRepository("BufeteBundle:Casos");
                $query = $repo->createQueryBuilder('c')
                ->innerJoin('BufeteBundle:Civiles', 'ci', 'WITH', 'c.idCaso = ci.idCaso')
                ->innerJoin('BufeteBundle:Demandantes', 'd', 'WITH', 'c.idDemandante = d.idDemandante')
                ->where('d.idCiudad = :ciudad')
                ->setParameter('ciudad', $ciudad)
                ->orderBy('c.fechaCaso', 'DESC')
                ->getQuery();
                $casos = $query->getResult();
            }
        } elseif ($rol == "ROLE_ADMIN") {
            $searchQuery = $request->get('query');
            if(!empty($searchQuery)){
              $query = $em->createQuery(
                "SELECT c FROM BufeteBundle:Casos c
                  INNER JOIN BufeteBundle:Civiles ci WITH c = ci.idCaso
                  WHERE c.estadoCaso = :opcion
                  ORDER BY c.fechaCaso DESC");
              $query->setParameter('opcion', $searchQuery);
              $casos = $query->getResult();
            } else {
              $query = $em->createQuery(
                "SELECT c FROM BufeteBundle:Casos c
                INNER JOIN BufeteBundle:Civiles ci WITH c = ci.idCaso
                ORDER BY c.fechaCaso DESC"
              );
              $casos = $query->getResult();
            }
        }

        return $this->render('casos/indexciviles.html.twig', array(
            'casos' => $casos,
        ));
    }

    /**
     * Listar los casos laborales segun el estudiante logueado
     *
     */
    public function laboralesEstudianteAction()
    {
        $idEstudiante = $this->getUser()->getEstudiantes()->getIdEstudiante();
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
          "SELECT c FROM BufeteBundle:Casos c
          INNER JOIN BufeteBundle:Laborales l WITH c = l.idCaso
          WHERE c.idEstudiante = :id"
        )->setParameter('id', $idEstudiante);
        $casos = $query->getResult();

        return $this->render('casos/laboralesestudiante.html.twig', array(
            'casos' => $casos,
        ));
    }

    /**
     * Listar los casos civiles segun el estudiante logueado
     *
     */
    public function civilesEstudianteAction()
    {
        $idEstudiante = $this->getUser()->getEstudiantes()->getIdEstudiante();
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
          "SELECT c FROM BufeteBundle:Casos c
          INNER JOIN BufeteBundle:Civiles l WITH c = l.idCaso
          WHERE c.idEstudiante = :id"
        )->setParameter('id', $idEstudiante);
        $casos = $query->getResult();

        return $this->render('casos/civilesestudiante.html.twig', array(
            'casos' => $casos,
        ));
    }


    /**
     * Crear nuevo caso laboral.
     *
     */
    public function newLaboralAction(Request $request)
    {
        $caso = new Casos();
        $laboral = new Laborales();
        $em = $this->getDoctrine()->getManager();
        $caso->setLaborales($laboral);

        $idciudad = $this->getUser()->getIdBufete()->getIdCiudad()->getIdCiudad();
        $idasignatario = $this->getUser()->getIdPersona();

        $tipopractica = null; $flush = null; $mensaje = null; $confirm = false;

        $form = $this->createForm('BufeteBundle\Form\CasosType', $caso, array('idciudad'=> $idciudad));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $tipocaso_repo = $em->getRepository("BufeteBundle:Tipocaso");
            $tipo = $tipocaso_repo->find(2);
            $caso->setIdTipo($tipo);
            $caso->setAsignatarioCaso($idasignatario);

            $tipopractica = $form->get('idEstudiante')->getData();
            if($tipopractica == null)
            {
              $em->persist($caso);
              $flush = $em->flush();
              if($flush == false){
                 $mensaje = "Se registro correctamente el caso";
                 $confirm = true;
              } else{
                 $mensaje = "No se pudo registrar correctamente el caso";
              }
            } else {
                 $tipopractica = $form->get('idEstudiante')->getData()->getidtipopracticante()->getidtipopracticante();
                 if($tipopractica != 3)
                 {
                     $em->persist($caso);
                     $flush = $em->flush();
                     if($flush == false){
                        $mensaje = "Se registro correctamente el caso";
                        $confirm = true;
                     } else{
                        $mensaje = "No se pudo registrar correctamente el caso";
                     }
                 } else {
                   $mensaje = "El estudiante esta realizando practicas externas";
                 }
            }

            if ($confirm) {
              return $this->redirectToRoute('casos_verlaboral', array('idCaso' => $caso->getIdCaso()));
            }else {
              $this->session->getFlashBag()->add("status", $mensaje);
            }
        }

        return $this->render('casos/newlaboral.html.twig', array(
            'caso' => $caso,
            'form' => $form->createView(),
        ));
    }


    /**
    * Crear casos civiles
    */
    public function newCivilAction(Request $request)
    {
        $caso = new Casos();
        $civil = new Civiles();
        $caso->setCiviles($civil);
        $idciudad = $this->getUser()->getIdBufete()->getIdCiudad()->getIdCiudad();
        $idasignatario = $this->getUser()->getIdPersona();

        $tipopractica = null; $flush = null; $mensaje = null; $confirm = false;

        $form = $this->createForm('BufeteBundle\Form\CasocivilType', $caso, array('idciudad'=> $idciudad));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $tipocaso_repo = $em->getRepository("BufeteBundle:Tipocaso");
            $tipo = $tipocaso_repo->find(1);
            $caso->setIdTipo($tipo);
            $caso->setAsignatarioCaso($idasignatario);


            $tipopractica = $form->get('idEstudiante')->getData();
            if($tipopractica == null)
            {
              $em->persist($caso);
              $flush = $em->flush();
              if($flush == false){
                $mensaje = "Se registro correctamente el caso";
                $confirm = true;
              } else{
                $mensaje = "No se pudo registrar correctamente el caso";
              }
            } else {
                $tipopractica = $form->get('idEstudiante')->getData()->getidtipopracticante()->getidtipopracticante();
                if($tipopractica != 3)
                {
                      $em->persist($caso);
                      $flush = $em->flush();
                      if($flush == false){
                        $mensaje = "Se registro correctamente el caso";
                        $confirm = true;
                      } else{
                        $mensaje = "No se pudo registrar correctamente el caso";
                      }
                } else {
                  $mensaje = "El estudiante esta realizando practicas externas";
                }
            }

            if ($confirm) {
              return $this->redirectToRoute('casos_vercivil', array('idCaso' => $caso->getIdcaso()));
            }else {
              $this->session->getFlashBag()->add("status", $mensaje);
            }
        }

        return $this->render('casos/newcivil.html.twig', array(
            'caso' => $caso,
            'form' => $form->createView(),
            'tipo' => $tipopractica,
        ));
    }

    /**
     * Detalle caso laboral
     *
     */
    public function showLaboralAction(Request $request, Casos $caso)
    {
        $deleteForm = $this->createDeleteForm($caso);
        return $this->render('casos/showlaboral.html.twig', array(
            'caso' => $caso,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Detalle caso laboral
     *
     */
    public function mostrarLaboralAction(Request $request)
    {
        $var=$request->request->get("id");
        $em = $this->getDoctrine()->getManager();
        $caso = $em->getRepository('BufeteBundle:Casos')->findOneBy(array('idCaso' => $var));
        return $this->render('casos/showlaboral.html.twig', array(
            'caso' => $caso,
        ));
    }

    /**
     * Detalle caso civil
     *
     */
    public function mostrarCivilAction(Request $request)
    {
        $var=$request->request->get("id");
        $em = $this->getDoctrine()->getManager();
        $caso = $em->getRepository('BufeteBundle:Casos')->findOneBy(array('idCaso' => $var));
        return $this->render('casos/showcivil.html.twig', array(
            'caso' => $caso,
        ));
    }

    /**
     * Detalle caso civil
     *
     */
    public function showCivilAction(Casos $caso)
    {
        $deleteForm = $this->createDeleteForm($caso);

        return $this->render('casos/showcivil.html.twig', array(
            'caso' => $caso,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function printCivilAction(Casos $caso)
    {
      $nombre = $caso->getNoCaso();
      $nombre = $nombre.$caso->getIdPersona()->getNombrePersona();
      $nombre = $nombre.$caso->getIdEstudiante()->getIdPersona()->getNombrePersona();
      $snappy = $this->get('knp_snappy.pdf');
          $snappy->setOption('no-outline', true);
          $snappy->setOption('encoding', 'UTF-8');
          $snappy->setOption('page-size','LEGAL');
          $snappy->setOption('footer-right','Página [page] de [topage]');
          $snappy->setOption('footer-font-size','10');
          $html = $this->renderView('casos/printcivil.html.twig', array('caso' => $caso));
          $filename = 'CasoPDF '.$nombre;
          return new Response(
              $snappy->getOutputFromHtml($html),
              200,
              array(
                  'Content-Type'          => 'application/pdf',
                  'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'));
    }

    public function printLaboralAction(Casos $caso)
    {
      $snappy = $this->get('knp_snappy.pdf');
      $snappy->setOption('no-outline', false);
      $snappy->setOption('encoding', 'UTF-8');
      $snappy->setOption('page-size','LEGAL');
      $snappy->setOption('footer-right','Página [page] de [topage]');
      $snappy->setOption('footer-font-size','10');
          $html = $this->renderView('casos/printlaboral.html.twig', array('caso' => $caso));
          $filename = 'CasoPDF';
          return new Response(
              $snappy->getOutputFromHtml($html),
              200,
              array(
                  'Content-Type'          => 'application/pdf',
                  'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'));
    }
    /**
     * Editar caso laboral
     *
     */
    public function editLaboralAction(Request $request, Casos $caso)
    {
        $idciudad = $caso->getIdDemandante()->getIdCiudad()->getIdCiudad();
        $editForm = $this->createForm('BufeteBundle\Form\CasosType', $caso, array('idciudad'=> $idciudad));
        $editForm->handleRequest($request);

        $tipopractica = null; $mensaje = null; $confirm = false;

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $tipopractica = $editForm->get('idEstudiante')->getData();
            if($tipopractica == null)
            {
                $this->getDoctrine()->getManager()->flush();
                $confirm = true;
            } else {
                 $tipopractica = $editForm->get('idEstudiante')->getData()->getidtipopracticante()->getidtipopracticante();
                 if($tipopractica != 3)
                 {
                     $this->getDoctrine()->getManager()->flush();
                     $confirm = true;
                 } else {
                   $mensaje = "El estudiante esta realizando practicas externas";
                 }
            }
            if ($confirm) {
              return $this->redirectToRoute('casos_verlaboral', array('idCaso' => $caso->getIdcaso()));
            }else {
              $this->session->getFlashBag()->add("status", $mensaje);
            }

        }

        return $this->render('casos/editlaboral.html.twig', array(
            'caso' => $caso,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Editar caso civil
     *
     */
    public function editCivilAction(Request $request, Casos $caso)
    {
        $idciudad = $caso->getIdDemandante()->getIdCiudad()->getIdCiudad();
        $editForm = $this->createForm('BufeteBundle\Form\CasocivilType', $caso, array('idciudad'=> $idciudad));
        $editForm->handleRequest($request);

        $tipopractica = null; $mensaje = null; $confirm = false;

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $tipopractica = $editForm->get('idEstudiante')->getData();
            if($tipopractica == null)
            {
                $this->getDoctrine()->getManager()->flush();
                $confirm = true;
            } else {
                 $tipopractica = $editForm->get('idEstudiante')->getData()->getidtipopracticante()->getidtipopracticante();
                 if($tipopractica != 3)
                 {
                     $this->getDoctrine()->getManager()->flush();
                     $confirm = true;
                 } else {
                   $mensaje = "El estudiante esta realizando practicas externas";
                 }
            }
            if ($confirm) {
              return $this->redirectToRoute('casos_vercivil', array('idCaso' => $caso->getIdcaso()));
            }else {
              $this->session->getFlashBag()->add("status", $mensaje);
            }

        }

        return $this->render('casos/editcivil.html.twig', array(
            'caso' => $caso,
            'edit_form' => $editForm->createView(),
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

* @param User $entity
*
* @Route("/{id}/entity-remove", requirements={"id" = "\d+"}, name="print_route_name")
* @return RedirectResponse
*
*/
   public function printcivil(User $entity)
   {
     $snappy = $this->get('knp_snappy.pdf');
     $html= $this->renderView('civiles/show.html.twig');
      $filename = 'myFirstSnappyPDF';
      return new Response(
          $snappy->getOutputFromHtml($html),
          200,
          array(
              'Content-Type'          => 'application/pdf',
              'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'
          )
      );
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
