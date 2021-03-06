<?php

namespace BufeteBundle\Controller;

use BufeteBundle\Entity\Personas;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Session\Session;
use BufeteBundle\Entity\Estudiantes;
use BufeteBundle\Form\PersonasAsesorType;
use BufeteBundle\Form\PersonasEstudianteType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use BufeteBundle\Entity\Post;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;


use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Persona controller.
 *
 */
class PersonasController extends Controller
{

  private $session;

  public function __construct(){
    $this->session = new Session();
  }

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                /*                                                           *
                 *   LISTA DE TODOS LOS ASEOSRES QUE ESTAN EN EL SISTEMA     *
                 *                                                           */
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function indexAsesoresAction(Request $request)
  {
      $em = $this->getDoctrine()->getManager();
      $searchQuery = $request->get('query');
      $asesores = null;
      $rol = $this->getUser()->getRole();

      if ($rol == "ROLE_ADMIN") {
        if (!empty($searchQuery)) {
          $repo = $em->getRepository("BufeteBundle:Personas");
          $query = $repo->createQueryBuilder('p')
          ->where('p.nombrePersona LIKE :param')
          ->andWhere('p.role = :rol')
          ->setParameter('rol', 'ROLE_ASESOR')
          ->setParameter('param', '%'.$searchQuery.'%')
          ->getQuery();
          $asesores = $query->getResult();
        } else {
          $query = $em->CreateQuery(
              "SELECT p FROM BufeteBundle:Personas p
              WHERE p.role LIKE 'ROLE_ASESOR'"
            );
          $asesores = $query->getResult();
        }
      } elseif ($rol == "ROLE_SECRETARIO") {
        $bufete = $this->getUser()->getIdBufete();
        if (!empty($searchQuery)) {
          $repo = $em->getRepository("BufeteBundle:Personas");
          $query = $repo->createQueryBuilder('p')
          ->where('p.nombrePersona LIKE :param')
          ->andWhere('p.idBufete = :id')
          ->andWhere('p.role = :rol')
          ->setParameter('rol', 'ROLE_ASESOR')
          ->setParameter('id', $bufete)
          ->setParameter('param', '%'.$searchQuery.'%')
          ->getQuery();
          $asesores = $query->getResult();
        } else {
          $query = $em->CreateQuery(
              "SELECT p FROM BufeteBundle:Personas p
              WHERE p.role LIKE 'ROLE_ASESOR' and p.idBufete = :id"
            )->setParameter('id', $bufete);
          $asesores = $query->getResult();
        }
      }

      $paginator = $this->get('knp_paginator');
      $asesorespg = $paginator->paginate(
          $asesores,
          $request->query->getInt('page', 1), 10 );

        return $this->render('personas/indexAsesores.html.twig', array(
          'asesores' => $asesorespg,
        ));
  }


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              /*                                                           *
               *   LISTA DE CASOS LABORALES SEGÚN EL ASESOR LOGUEADO       *
               *                                                           */
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function laboralesAsesorAction(Request $request)
  {
      $em = $this->getDoctrine()->getManager();
      $idAsesor = $this->getUser()->getIdPersona();

      $searchQuery = $request->get('query');
      $casos = null;

      if (!empty($searchQuery)) {
        $repo = $em->getRepository("BufeteBundle:Casos");
        $query = $repo->createQueryBuilder('c')
        ->innerJoin('BufeteBundle:Laborales', 'l', 'WITH', 'c.idCaso = l.idCaso')
        ->innerJoin('BufeteBundle:Demandantes', 'd', 'WITH', 'c.idDemandante = d.idDemandante')
        ->innerJoin('BufeteBundle:Estudiantes', 'e', 'WITH', 'e.idEstudiante = c.idEstudiante')
        ->orWhere('c.noCaso LIKE :param')
        ->orWhere('d.nombreDemandante LIKE :param')
        ->orWhere('c.nombreDemandado LIKE :param')
        ->orWhere('e.carneEstudiante LIKE :param')
        ->andWhere('c.idPersona = :id')
        ->setParameter('id', $idAsesor)
        ->setParameter('param', '%'.$searchQuery.'%')
        ->orderBy('c.fechaCaso', 'DESC')
        ->getQuery();
        $casos = $query->getResult();
      } else {
        $query = $em->createQuery(
          "SELECT c FROM BufeteBundle:Casos c
          INNER JOIN BufeteBundle:Laborales l WITH c = l.idCaso
          WHERE c.idPersona = :id
          ORDER BY c.fechaCaso DESC"
        )->setParameter('id', $idAsesor);
        $casos = $query->getResult();
      }

      $paginator = $this->get('knp_paginator');
      $casospg = $paginator->paginate(
          $casos,
          $request->query->getInt('page', 1), 10 );

      return $this->render('casos/laboralesestudiante.html.twig', array(
          'casos' => $casospg,
      ));
  }


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              /*                                                           *
               *   LISTA DE CASOS CIVILES SEGÚN EL ASESOR LOGUEADO         *
               *                                                           */
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function civilesAsesorAction(Request $request)
  {
      $idAsesor = $this->getUser()->getIdPersona();
      $em = $this->getDoctrine()->getManager();

      $searchQuery = $request->get('query');
      $casos = null;

      if (!empty($searchQuery)) {
        $repo = $em->getRepository("BufeteBundle:Casos");
        $query = $repo->createQueryBuilder('c')
        ->innerJoin('BufeteBundle:Civiles', 'ci', 'WITH', 'c.idCaso = ci.idCaso')
        ->innerJoin('BufeteBundle:Demandantes', 'd', 'WITH', 'c.idDemandante = d.idDemandante')
        ->innerJoin('BufeteBundle:Estudiantes', 'e', 'WITH', 'e.idEstudiante = c.idEstudiante')
        ->orWhere('c.noCaso LIKE :param')
        ->orWhere('d.nombreDemandante LIKE :param')
        ->orWhere('c.nombreDemandado LIKE :param')
        ->orWhere('e.carneEstudiante LIKE :param')
        ->andWhere('c.idPersona = :id')
        ->setParameter('id', $idAsesor)
        ->setParameter('param', '%'.$searchQuery.'%')
        ->orderBy('c.fechaCaso', 'DESC')
        ->getQuery();
        $casos = $query->getResult();
      } else {
        $query = $em->createQuery(
          "SELECT c FROM BufeteBundle:Casos c
          INNER JOIN BufeteBundle:Civiles ci WITH c = ci.idCaso
          WHERE c.idPersona = :id
          ORDER BY c.fechaCaso DESC"
        )->setParameter('id', $idAsesor);
        $casos = $query->getResult();
      }

      $paginator = $this->get('knp_paginator');
      $casospg = $paginator->paginate(
          $casos,
          $request->query->getInt('page', 1), 10 );

      $paginator = $this->get('knp_paginator');
      $casospg = $paginator->paginate(
          $casos,
          $request->query->getInt('page', 1), 10 );

      return $this->render('casos/civilesestudiante.html.twig', array(
          'casos' => $casospg,
      ));
  }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                /*                                                           *
                 *        LISTA DE TODOS LOS ESTUDIANTES         *
                 *                                                           */
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function indexEstudiantesAction(Request $request)
  {
    $searchQuery = $request->get('query');
    if(!empty($searchQuery))
    {
      $em = $this->getDoctrine()->getManager();
      $query = $em->CreateQuery(
         "SELECT p FROM BufeteBundle:Personas p
          INNER JOIN BufeteBundle:Estudiantes e
          WITH p=e.idPersona WHERE (p.nombrePersona like :name OR e.carneEstudiante like :name)"
        );
        $query->setParameter('name', '%'.$searchQuery.'%');
        $estudiantes = $query->getResult();
    }
    else
    {
      $em = $this->getDoctrine()->getManager();
      $query = $em->CreateQuery(
         "SELECT p FROM BufeteBundle:Personas p
          INNER JOIN BufeteBundle:Estudiantes e
          WITH p=e.idPersona"
        );
        $estudiantes = $query->getResult();
    }

    $paginator = $this->get('knp_paginator');
    $estudiantespg = $paginator->paginate(
        $estudiantes,
        $request->query->getInt('page', 1), 8 );

    return $this->render('personas/indexEstudiantes.html.twig', array(
        'estudiantes' => $estudiantespg,
    ));
  }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              /*                                                                                       *
               *        MOSTRAR EL PERFIL DE LA PERSONA O ESTUDIANTE EN EL LADO DEL SECRETARIO         *
               *                                                                                       */
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function perfilAction(Request $request)
  {


////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////

/*
$post = Request::createFromGlobals();
$var = $post;
dump($var);
die();
*/
    $var=$request->request->get("idPersona");



    $post = Request::createFromGlobals();
    $var2 = $post->request->get('idPersona5');

    $post = Request::createFromGlobals();
    $var3 = $post->query->get('var');

    if($var == null)
    {
      $post = Request::createFromGlobals();
      $var = $post->request->get('idPersona2');
    }
    if(isset($var2)){
      $post = Request::createFromGlobals();
      $var = $post->request->get('idPersona5');
    }
    if(isset($var3))
    {
      $var = $var3;
    }



    $em = $this->getDoctrine()->getManager();
    $persona = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
                 'idPersona' => $var
    ));




    if($persona == null)
    {
      return $this->redirectToRoute('errores_notfound');
    }

      $rolPersona = $persona->getRole();


      if($rolPersona == "ROLE_ESTUDIANTE")
      {
        return $this->render('personas/perfilEstudiante.html.twig', array(
            'persona' => $persona,
        ));
      }
      else if($rolPersona == "ROLE_ASESOR")
      {
        $rolEnvio = 'Asesor';
        return $this->render('personas/perfilPersonal.html.twig', array(
            'persona' => $persona,
            'rolEnvio' => $rolEnvio,
        ));
      }
      else if($rolPersona == "ROLE_SECRETARIO")
      {
        $rolEnvio = 'Secretario';
        return $this->render('personas/perfilPersonal.html.twig', array(
            'persona' => $persona,
            'rolEnvio' => $rolEnvio,
            //'delete_form' => $deleteForm->createView(),
        ));
      }
      else if($rolPersona == "ROLE_ADMIN")
      {
        $rolEnvio = 'Administrador';
        return $this->render('personas/perfilPersonal.html.twig', array(
            'persona' => $persona,
            'rolEnvio' => $rolEnvio,
        ));
      }
      else if($rolPersona == "ROLE_DIRECTOR")
      {
        $rolEnvio = 'Director';
        return $this->render('personas/perfilPersonal.html.twig', array(
            'persona' => $persona,
            'rolEnvio' => $rolEnvio,
            //'delete_form' => $deleteForm->createView(),
        ));
      }
      // **DESCOMENTAR PARA MOSTRAR PAGINA DE ERRORES
      return $this->redirectToRoute('errores_notfound');
  }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              /*                                                           *
               *        MOSTRAR DETALLES DEL PERFIL DEL ESTUDIANTE         *
               *                                                           */
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function studentProfileAction(Request $request)
  {
    $var=$request->request->get("idPersona");

    if($var == null)
    {
      $post = Request::createFromGlobals();
      $var = $post->request->get('idPersona2');
    }

    if($var == null)
    {
      $post = Request::createFromGlobals();
      $var = $post->request->get('idPersona5');
    }

    $em = $this->getDoctrine()->getManager();
    $persona = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
                 'idPersona' => $var
    ));

      //$deleteForm = $this->createDeleteForm($persona);

      $rolPersona = $persona->getRole();

      if($rolPersona == "ROLE_ESTUDIANTE")
      {
        return $this->render('personas/detStudentProfile.html.twig', array(
            'persona' => $persona,
            //'delete_form' => $deleteForm->createView(),
        ));
      }
      else if($rolPersona == "ROLE_ASESOR")
      {
        $rolEnvio = 'Asesor';
        return $this->render('personas/perfilPersonal.html.twig', array(
            'persona' => $persona,
            'rolEnvio' => $rolEnvio,
            //'delete_form' => $deleteForm->createView(),
        ));
      }

  }


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /*                                                        *
     *        MOSTRAR DETALLES DEL PERFIL DEL PERSONAL        *
     *                                                        */
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function staffProfileAction(Request $request)
  {

    $var=$request->request->get("idPersona");

    /*
    $post = Request::createFromGlobals();
    $var = $post->request->get('idPersona2');
    dump($var);
    die();
    */

    if($var == null)
    {
      $post = Request::createFromGlobals();
      $var = $post->request->get('idPersona2');

    }
    if($var == null)
    {
      $post = Request::createFromGlobals();
      $var = $post->request->get('idPersona5');
    }


    $em = $this->getDoctrine()->getManager();
    $persona = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
                 'idPersona' => $var
    ));

      //$deleteForm = $this->createDeleteForm($persona);

      $rolPersona = $persona->getRole();
      if($rolPersona == "ROLE_ESTUDIANTE")
      {
        $rolEnvio = 'Estudiante';
        return $this->render('personas/detStaffProfile.html.twig', array(
            'persona' => $persona,
            'rolEnvio' => $rolEnvio,
            //'delete_form' => $deleteForm->createView(),
        ));
      }
      else if($rolPersona == "ROLE_ASESOR")
      {
        $rolEnvio = 'Asesor';
        return $this->render('personas/detStaffProfile.html.twig', array(
            'persona' => $persona,
            'rolEnvio' => $rolEnvio,
            //'delete_form' => $deleteForm->createView(),
        ));
      }
      else if($rolPersona == "ROLE_SECRETARIO")
      {
        $rolEnvio = 'Secretario';
        return $this->render('personas/detStaffProfile.html.twig', array(
            'persona' => $persona,
            'rolEnvio' => $rolEnvio,
            //'delete_form' => $deleteForm->createView(),
        ));
      }
      else if($rolPersona == "ROLE_ADMIN")
      {
        $rolEnvio = 'Administrador';
        return $this->render('personas/detStaffProfile.html.twig', array(
            'persona' => $persona,
            'rolEnvio' => $rolEnvio,
            //'delete_form' => $deleteForm->createView(),
        ));
      }
      else if($rolPersona == "ROLE_DIRECTOR")
      {
        $rolEnvio = 'Director';
        return $this->render('personas/detStaffProfile.html.twig', array(
            'persona' => $persona,
            'rolEnvio' => $rolEnvio,
            //'delete_form' => $deleteForm->createView(),
        ));
      }
      // **DESCOMENTAR PARA MOSTRAR PAGINA DE ERRORES
      //return $this->redirectToRoute('errores_notfound');
  }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              /*                                                           *
               *        PARA EL HISTORIAL DEL ESTUDIANTE                   *
               *                                                           */
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   public function HistorialEstudianteAction(Request $request, Personas $persona)
   {
     $carne=  $request->get('id');
     //$carne = $persona->getestudiantes()->getcarneEstudiante();
     $em = $this->getDoctrine()->getManager();
     $query = $em->createQuery(
       "SELECT c FROM BufeteBundle:Casos c
       INNER JOIN BufeteBundle:Civiles l WITH c = l.idCaso
       WHERE c.idEstudiante = :id");
     $query->setParameter('id', $carne);
     $civiles = $query->getResult();
     $query2 = $em->createQuery(
       "SELECT c FROM BufeteBundle:Casos c
       INNER JOIN BufeteBundle:Laborales l WITH c = l.idCaso
       WHERE c.idEstudiante = :id");
     $query2->setParameter('id', $carne);
     $laborales = $query2->getResult();
     $query3 = $em->createQuery(
       "SELECT a FROM BufeteBundle:Asignacionclinica a WHERE a.idEstudiante = :id");
     $query3->setParameter('id', $carne);
     $clinicas = $query3->getResult();
     return $this->render('personas/HistorialEstudiante.html.twig', array(
         'civiles' => $civiles,
         'laborales' => $laborales,
         'clinicas' => $clinicas,
     ));
   }


   //////////////////////////////////////////////////////////////////////////////////
    /*QUERY PARA EL RECORD DE UN ESTUDIANTE SEGUN CASOS CIVILES*/
    public function HistorialEstudianteCivilAction(Request $request, Personas $persona)
    {
      $carne=  $request->get('id');
      //$carne = $persona->getestudiantes()->getcarneEstudiante();
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery(
        "SELECT c FROM BufeteBundle:Casos c
        INNER JOIN BufeteBundle:Civiles l WITH c = l.idCaso
        WHERE c.idEstudiante = :id");
      $query->setParameter('id', $carne);
      $civiles = $query->getResult();
      $snappy = $this->get('knp_snappy.pdf');
      $snappy->setOption('no-outline', false);
      $snappy->setOption('encoding', 'UTF-8');
      $snappy->setOption('orientation', 'Landscape');
      $snappy->setOption('page-size','LEGAL');
      $snappy->setOption('footer-right','Página [page] de [topage]');
      $snappy->setOption('footer-font-size','10');
          $html = $this->renderView('personas/HistorialEstudianteCivil.html.twig', array('civiles' => $civiles));
          $filename = 'HistorialCivil';
          return new Response(
              $snappy->getOutputFromHtml($html),
              200,
              array(
                  'Content-Type'          => 'application/pdf',
                  'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'));
    }
 ////////////////////////////////////////////////////////////////////////////////////
 public function HistorialEstudianteClinicasAction(Request $request, Personas $persona)
 {
   $carne=  $request->get('id');
   //$carne = $persona->getestudiantes()->getcarneEstudiante();
   $em = $this->getDoctrine()->getManager();
   $query3 = $em->createQuery(
     "SELECT a FROM BufeteBundle:Asignacionclinica a WHERE a.idEstudiante = :id");
   $query3->setParameter('id', $carne);
   $clinicas = $query3->getResult();
   $snappy = $this->get('knp_snappy.pdf');
   $snappy->setOption('no-outline', false);
   $snappy->setOption('encoding', 'UTF-8');
   $snappy->setOption('orientation', 'Landscape');
   $snappy->setOption('page-size','LEGAL');
   $snappy->setOption('footer-right','Página [page] de [topage]');
   $snappy->setOption('footer-font-size','10');
       $html = $this->renderView('personas/HistorialEstudianteClinicas.html.twig', array('clinicas' => $clinicas));
       $filename = 'Historialclinicas'.$carne;
       return new Response(
           $snappy->getOutputFromHtml($html),
           200,
           array(
               'Content-Type'          => 'application/pdf',
               'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'));
 }
 ////////////////////////////////////////////////////////////////////////////////////
 /*  AGREGAR ESTUDIANTE */
 public function HistorialEstudianteLaboralAction(Request $request, Personas $persona)
 {
   $carne=  $request->get('id');
   //$carne = $persona->getestudiantes()->getcarneEstudiante();
   $em = $this->getDoctrine()->getManager();
   $query2 = $em->createQuery(
     "SELECT c FROM BufeteBundle:Casos c
     INNER JOIN BufeteBundle:Laborales l WITH c = l.idCaso
     WHERE c.idEstudiante = :id");
   $query2->setParameter('id', $carne);
   $laborales = $query2->getResult();
   $snappy = $this->get('knp_snappy.pdf');
   $snappy->setOption('no-outline', false);
   $snappy->setOption('encoding', 'UTF-8');
   $snappy->setOption('orientation', 'Landscape');
   $snappy->setOption('page-size','LEGAL');
   $snappy->setOption('footer-right','Página [page] de [topage]');
   $snappy->setOption('footer-font-size','10');
       $html = $this->renderView('personas/HistorialEstudianteLaboral.html.twig', array('laborales' => $laborales));
       $filename = 'HistorialLaboral'.$carne;
       return new Response(
           $snappy->getOutputFromHtml($html),
           200,
           array(
               'Content-Type'          => 'application/pdf',
               'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'));
 }
 ////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


  /**
   * Lists all persona entities.
   *
   */
  public function indexUsuariosAction(Request $request)
  {
      $em = $this->getDoctrine()->getManager();
      $personas = null;
      $searchQuery = $request->get('query');

      if (!empty($searchQuery)) {
        $repo = $em->getRepository("BufeteBundle:Personas");
        $query = $repo->createQueryBuilder('p')
        ->orWhere('p.role = :asesor')
        ->orWhere('p.role = :admin')
        ->orWhere('p.role = :secretario')
        ->orWhere('p.role = :director')
        ->andWhere('p.nombrePersona LIKE :param')
        ->setParameter('asesor', 'ROLE_ASESOR')
        ->setParameter('admin', 'ROLE_ADMIN')
        ->setParameter('director', 'ROLE_DIRECTOR')
        ->setParameter('secretario', 'ROLE_SECRETARIO')
        ->setParameter('param', '%'.$searchQuery.'%')
        ->getQuery();
        $personas = $query->getResult();
      } else {
        $query = $em->createQuery(
          "SELECT p FROM BufeteBundle:Personas p
          WHERE p.role LIKE :asesor OR p.role LIKE :admin OR p.role LIKE :director OR p.role LIKE :secretario
          ORDER BY p.role"
        )
        ->setParameter('asesor', 'ROLE_ASESOR')
        ->setParameter('admin', 'ROLE_ADMIN')
        ->setParameter('director', 'ROLE_DIRECTOR')
        ->setParameter('secretario', 'ROLE_SECRETARIO');
        $personas = $query->getResult();
      }

      $paginator = $this->get('knp_paginator');
      $personaspg = $paginator->paginate(
          $personas,
          $request->query->getInt('page', 1), 8 );

      return $this->render('personas/index.html.twig', array(
          'personas' => $personaspg,
      ));
  }


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              /*                                                           *
               *                AGREGAR UN ESTUDIANTE AL SISTEMA           *
               *                                                           */
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function registroAction(Request $request)
  {
          $persona = new Personas();
          $estudiantes = new Estudiantes();
          $persona->setEstudiantes($estudiantes);

          //GENERAR CONTRASEÑA
          $autocont = $this->get("app.autocont");
          $pass = $autocont->obtener();

            //recibir del get el CARNE
            $var=$request->query->get("carne");
            $nuevavar = (int)$var;
            $carne=$var;

          //CONSULTAR EL SERVICIO DE LA CUNOC
              if(isset($carne))
              {
                  $datos1 = $this->get("app.registrocunoc");
                  $datos = $datos1->consultar($carne);
              }

          $nomComp =""; $carrera =""; $telefono=""; $correo=""; $direccion=""; $muni_dep="";

          $permiso = false;
          if(isset($datos))
          {
            if($datos == 1)
            {
              $status = "ERROR DE CONEXIÓN";
              $this->session->getFlashBag()->add("status", $status);
            }
            else if($datos==6)
            {
              $status = "CARNÉ INVALIDO";
              $this->session->getFlashBag()->add("status", $status);
            }
            else if($datos==16)
            {
              $status = "EL ESTUDIANTE NO SE ENCUENTRA INSCRITO";
              $this->session->getFlashBag()->add("status", $status);
            }
            else if($datos==10)
            {
              $status = "EL ESTUDIANTE NO ESTA INSCRITO EN LA DIVISIÓN DE CIENCIAS JURÍDICAS Y SOCIALES";
              $this->session->getFlashBag()->add("status", $status);
            }
            else if(isset($datos->STATUS,$datos->DATOS[0]->CARNET,$datos->DATOS[0]->NOM1))
            {
              if($datos->STATUS==1 )
              {
                $carne = $datos->DATOS[0]->CARNET;
                $nomComp =$datos->DATOS[0]->NOM1." ".$datos->DATOS[0]->NOM2." ".$datos->DATOS[0]->NOM3." ".$datos->DATOS[0]->APE1." ".$datos->DATOS[0]->APE2;
                $telefono=$datos->DATOS->TELEFONO;
                $correo=$datos->DATOS->CORREO;
                $direccion=$datos->DATOS->DIRECCION;
                $muni_dep=$datos->DATOS->MUNICIPIO." ".$datos->DATOS->DEPARTAMENTO;
              }
            }
            else {
              $status = "NO ENCONTRADO";
            }
          }

          $form = $this->createForm('BufeteBundle\Form\PersonasEstudianteType', $persona,
                  array(
                    'carneEnvio' => $carne,
                    'nombreEnvio'=> $nomComp,
                    'telefonoEnvio'=>$telefono,
                    'direccionEnvio'=>$direccion,
                    'correoEnvio'=>$correo,
                    'passEnvio' =>$pass,
                  ));

          $form->handleRequest($request);
          $confirm = null;
          $status=null;
          $unavariable="";
          if ($form->isSubmitted()){
              if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $estudiante_repo = $em->getRepository("BufeteBundle:Estudiantes");
                $est = $estudiante_repo->findOneBy(array('carneEstudiante' => $carne));
                $persona_repo = $em->getRepository("BufeteBundle:Personas");
                $pe = $persona_repo->findOneBy(array('usuarioPersona' => $form->get("usuarioPersona")->getData()));

                if(count($est) == 0){
                    if(count($pe) == 0){
                        $factory = $this->get("security.encoder_factory");
                        $encoder = $factory->getEncoder($persona);
                        $password = $encoder->encodePassword($form->get("passPersona")->getData(), $persona->getSalt());
                        $persona->setPassPersona($password);
                        $persona->setIdBufete($this->getUser()->getIdBufete());

                        $file = $persona->getFoto();

                        if(($file instanceof UploadedFile) && ($file->getError() == '0'))
                        {
                          $validator = $this->get('validator');
                          $errors = $validator->validate($persona);
                          if (count($errors) > 0)
                          {
                            $errorsString = (string) $errors;
                            return new Response($errorsString);
                          }

                          $fileName = md5(uniqid()).'.'.$file->guessExtension();
                          $cvDir = $this->container->getparameter('kernel.root_dir').'/../web/uploads/profile';
                          $file->move($cvDir, $fileName);
                          $persona->setFoto($fileName);
                        }

                        $em->persist($persona);
                        $flush = $em->flush();
                        if ($flush == null) {
                            $status = "El usuario se ha creado correctamente";
                            $confirm = true;
                        } else {
                          $status = "El usuario no se pudo registrar";
                        }
                    }else {
                        $status = "el nombre de usuario ya existe";
                    }
                }else {
                  $status = "El carne ya esta registrado";
                }
              }
              if ($confirm) {

                $var=$persona->getIdPersona();

                $em = $this->getDoctrine()->getManager();
                $persona = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
                             'idPersona' => $var
                ));
                  return $this->redirectToRoute('personas_perfil',
                   [
                     'var' => $persona->getIdPersona()
                   ], 307);
              }else {
                $this->session->getFlashBag()->add("status", $status);
              }
          }

          return $this->render('personas/registro.html.twig', array(
              'persona' => $persona,
              'form' => $form->createView(),
              'carne' => $carne,
              'nombre' => $nomComp,
              'carrera' => $carrera,
              'telefono' => $telefono,
              'correo' => $correo,
              'direccion' => $direccion." ".$muni_dep,
              ));
    }


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              /*                                                           *
               *        LISTA DE ASESORES PARA LA VISTA DEL DIRECTOR       *
               *                                                           */
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     public function indexAsesoresDirAction(Request $request)
     {
         $em = $this->getDoctrine()->getManager();
         $searchQuery = $request->get('query');
         $searchBufete = $request->get('sbufete');
         $asesores = null;
         $rol = $this->getUser()->getRole();

         if ($rol == "ROLE_DIRECTOR") {
           if (!empty($searchBufete)) {
             $repo = $em->getRepository("BufeteBundle:Personas");
             $query = $repo->createQueryBuilder('p')
             ->Where('p.idBufete = :searchbufete')
             ->andWhere('p.role = :rol')
             ->setParameter('rol', 'ROLE_ASESOR')
             ->setParameter('searchbufete', $searchBufete)
             ->getQuery();
             $asesores = $query->getResult();
             $bufetes = $em->getRepository('BufeteBundle:Bufetes')->findAll();
           } elseif (strlen($searchQuery) > 1) {
             $repo = $em->getRepository("BufeteBundle:Personas");
             $query = $repo->createQueryBuilder('p')
             ->where('p.nombrePersona LIKE :param')
             ->andWhere('p.role = :rol')
             ->setParameter('rol', 'ROLE_ASESOR')
             ->setParameter('param', '%'.$searchQuery.'%')
             ->getQuery();
             $asesores = $query->getResult();
             $bufetes = $em->getRepository('BufeteBundle:Bufetes')->findAll();
           } else {
             $query = $em->CreateQuery(
                 "SELECT p FROM BufeteBundle:Personas p
                 WHERE p.role LIKE 'ROLE_ASESOR'"
               );
             $asesores = $query->getResult();
             $bufetes = $em->getRepository('BufeteBundle:Bufetes')->findAll();
           }
         }

         $paginator = $this->get('knp_paginator');
         $asesorespg = $paginator->paginate(
             $asesores,
             $request->query->getInt('page', 1), 8 );

           return $this->render('personas/indexAsesorDir.html.twig', array(
             'asesores' => $asesorespg,
             'bufetes' => $bufetes,
           ));
     }



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              /*                                                           *
               *              PARA EL HISTORIA DEL ASESOR                  *
               *                                                           */
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      public function HistorialAsesorAction(Request $request, Personas $persona)
      {
        $idas=  $request->get('id');
        //$carne = $persona->getestudiantes()->getcarneEstudiante();
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
          "SELECT c FROM BufeteBundle:Casos c
          INNER JOIN BufeteBundle:Civiles l WITH c = l.idCaso
          WHERE c.idPersona = :id");
        $query->setParameter('id', $idas);
        $civiles = $query->getResult();
        $query2 = $em->createQuery(
          "SELECT c FROM BufeteBundle:Casos c
          INNER JOIN BufeteBundle:Laborales l WITH c = l.idCaso
          WHERE c.idPersona = :id");
        $query2->setParameter('id', $idas);
        $laborales = $query2->getResult();
        $query3 = $em->createQuery(
          "SELECT c FROM BufeteBundle:Clinicas c WHERE c.idPersona = :id");
        $query3->setParameter('id', $idas);
        $clinicas = $query3->getResult();
        return $this->render('personas/HistorialAsesor.html.twig', array(
            'civiles' => $civiles,
            'laborales' => $laborales,
            'clinicas' => $clinicas,
        ));
      }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              /*                                                                     *
               *        EDITAR NOMBRES DE USUARIO DEL PERSONA Y ESTUDIANTES          *
               *                                                                     */
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function editnUsuarioAction(Request $request)
    {
      $var=$request->request->get("idPersona");

      if(isset($var))
      {
        $em = $this->getDoctrine()->getManager();
        $persona = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
                     'idPersona' => $var
        ));
      }
      else
      {
          $var2=$request->request->get("idPersona2");
          $em = $this->getDoctrine()->getManager();
          $persona = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
                       'idPersona' => $var2
          ));
      }

        $pass = $persona->getpassPersona();

        $edit_form = $this->createForm('BufeteBundle\Form\PersonasEditUserType', $persona, array(
                 'PassPersona' => $pass,
            ));

        $edit_form->handleRequest($request);

        $confirm = null;
        $status=null;
        if ($edit_form->isSubmitted() )
        {
        //if($edit_form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $persona_repo = $em->getRepository("BufeteBundle:Personas");
            $pe = $persona_repo->findOneBy(array('usuarioPersona' => $edit_form->get("usuarioPersona")->getData()));
            if(count($pe) == 0)
            {
              $em->persist($persona);
              $flush = $em->flush();
              if ($flush == null) {
                  $this->session->getFlashBag()->add("status", $status);
                  $status = "El usuario se ha creado correctamente";
                  $confirm = true;
              } else {
                $status = "El usuario no se pudo registrar";
              }
            }else {
              $status = "el nombre de usuario ya existe";
            }
        }
          if ($confirm)
          {

            $rol = $persona->getRole();
            $a = "ROLE_ASESOR";
            $b = "ROLE_ADMIN";
            $c = "ROLE_DIRECTOR";
            $d = "ROLE_SECRETARIO";
            $e = "ROLE_ESTUDIANTE";

              if($rol == $a || $rol == $b || $rol == $c || $rol == $d)
              {
                return $this->redirectToRoute('personas_staffProfile',
                   [
                     'var' => $persona
                   ], 307);
              }
              else if($rol == $e)
              {
                return $this->redirectToRoute('personas_studentProfile',
                   [
                     'var' => $persona
                   ], 307);
              }


               $confirm=null;
          }else {
            $this->session->getFlashBag()->add("status", $status);
          }
        }

        return $this->render('personas/edituserpersonal.html.twig', array(
            'persona' => $persona,
            'PassPersona' => $pass,
            'edit_form' => $edit_form->createView(),
        ));
    }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * Deletes a persona entity.
 *
 */
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function deleteAction(Request $request, Personas $persona)
    {
      $form = $this->createDeleteForm($persona);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($persona);
        $em->flush();
      }

      return $this->redirectToRoute('personas_index');
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * Creates a form to delete a persona entity.
 *
 * @param Personas $persona The persona entity
 *
 * @return \Symfony\Component\Form\Form The form
 */
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    private function createDeleteForm(Personas $persona)
    {
      return $this->createFormBuilder()
          ->setAction($this->generateUrl('personas_delete', array('idPersona' => $persona->getIdpersona())))
          ->setMethod('DELETE')
          ->getForm();
    }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              /*                                                           *
               *        EDITAR CONTRASEÑA DEL PERSONA Y ESTUDIANTES        *
               *                                                           */
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function editcUsuarioAction(Request $request, Request $request2)
    {

      $var=$request2->request->get("idPersona");

      $em = $this->getDoctrine()->getManager();
      $persona = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
                   'idPersona' => $var
      ));

      if($var!=null)
      {
        //GENERAR CONTRASEÑA
        $autocont = $this->get("app.autocont");
        $pass = $autocont->obtener();
        $usu = $persona->getusuarioPersona();

        $persona->setpassPersona($pass);

        $edit_form = $this->createForm('BufeteBundle\Form\PersonalPassType', $persona, array(
               'PassPersona' => $pass,
          ));
        }
        else {

          //dump($request->request);
          //dump($request->request->get('bufetebundle_personas') );

          $post = Request::createFromGlobals();
          //dump($post->request->get('bufetebundle_personas')['passPersona']);
          $pass = $post->request->get('bufetebundle_personas')['passPersona'];


          $var2=$request2->request->get("idPersona2");

          $em = $this->getDoctrine()->getManager();
          $persona = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
                       'idPersona' => $var2
          ));

          $edit_form = $this->createForm('BufeteBundle\Form\PersonalPassType', $persona, array(
                 'PassPersona' => $pass,
            ));
            $edit_form->handleRequest($request);
        }

       //if ($edit_form->isSubmitted() && $edit_form->isValid())
       if ($edit_form->isSubmitted())
       {

         $var2=$request2->request->get("idPersona2");

         $em = $this->getDoctrine()->getManager();
         $persona = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
                      'idPersona' => $var2
         ));


           $factory = $this->get("security.encoder_factory");
           $encoder = $factory->getEncoder($persona);
           $password = $encoder->encodePassword($edit_form->get("passPersona")->getData(), $persona->getSalt());

           $persona->setPassPersona($password);


           $em->persist($persona);
           $flush = $em->flush();

           //return $this->redirectToRoute('personas_indexEstudiantes', array('idPersona' => $persona->getIdpersona()));
           $rol = $persona->getRole();
           $a = "ROLE_ASESOR";
           $b = "ROLE_ADMIN";
           $c = "ROLE_DIRECTOR";
           $d = "ROLE_SECRETARIO";
           $e = "ROLE_ESTUDIANTE";

             if($rol == $a || $rol == $b || $rol == $c || $rol == $d)
             {
               return $this->redirectToRoute('personas_staffProfile',
                  [
                    'var' => $persona
                  ], 307);
             }
             else if($rol == $e)
             {
               return $this->redirectToRoute('personas_studentProfile',
                  [
                    'var' => $persona
                  ], 307);
             }

       }

       return $this->render('personas/editpasspersonal.html.twig', array(
           'persona' => $persona,
           'PassPersona' => $pass,
           'edit_form' => $edit_form->createView(),
       ));
   }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * Finds and displays a persona entity.
 *
 */
public function showPersonasAction(Personas $persona)
{
    $deleteForm = $this->createDeleteForm($persona);

    return $this->render('personas/showPersonas.html.twig', array(
        'persona' => $persona,
        'delete_form' => $deleteForm->createView(),
    ));
}

/*
Iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii
Iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii
Iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii
Iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii
Iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii
Iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              /*                                                           *
               *   EDITAR CONTRASEÑA PARA EL ADMINISTRADOR O SECRETARIO    *
               *                                                           */
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function edituserAdminAction(Request $request, Request $request2)
    {
      $var=$request->request->get("idPersona");

      if(isset($var))
      {
        $em = $this->getDoctrine()->getManager();
        $persona = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
                     'idPersona' => $var
        ));
      }
      else
      {
          $var2=$request->request->get("idPersona2");
          $em = $this->getDoctrine()->getManager();
          $persona = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
                       'idPersona' => $var2
          ));
      }

        $pass = $persona->getpassPersona();

        $edit_form = $this->createForm('BufeteBundle\Form\PersonasEditUserType', $persona, array(
                 'PassPersona' => $pass,
            ));

        $edit_form->handleRequest($request);

        $confirm = null;
        $status=null;
        if ($edit_form->isSubmitted() )
        {
        //if($edit_form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $persona_repo = $em->getRepository("BufeteBundle:Personas");
            $pe = $persona_repo->findOneBy(array('usuarioPersona' => $edit_form->get("usuarioPersona")->getData()));
            if(count($pe) == 0)
            {
              $em->persist($persona);
              $flush = $em->flush();
              if ($flush == null) {
                  $this->session->getFlashBag()->add("status", $status);
                  $status = "El usuario se ha creado correctamente";
                  $confirm = true;
              } else {
                $status = "El usuario no se pudo registrar";
              }
            }else {
              $status = "el nombre de usuario ya existe";
            }
        }
          if ($confirm)
          {

            $rol = $persona->getRole();
            $a = "ROLE_ASESOR";
            $b = "ROLE_ADMIN";
            $c = "ROLE_DIRECTOR";
            $d = "ROLE_SECRETARIO";
            $e = "ROLE_ESTUDIANTE";


                return $this->redirectToRoute('personas_perfil',
                   [
                     'var' => $persona
                   ], 307);

               $confirm=null;
          }else {
            $this->session->getFlashBag()->add("status", $status);
          }
        }

        return $this->render('personas/editpassAdmin.html.twig', array(
            'persona' => $persona,
            'PassPersona' => $pass,
            'edit_form' => $edit_form->createView(),
        ));
    }


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              /*                                                           *
               *            EDITAR CONTRASEÑA DEL ESTUDIANTE               *
               *                                                           */
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

     public function editpassestudianteAction(Request $request, Request $request2)
     {
       $var=$request2->request->get("idPersona");

       $em = $this->getDoctrine()->getManager();
       $persona = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
                    'idPersona' => $var
       ));

       if($var!=null)
       {
         //GENERAR CONTRASEÑA
         $autocont = $this->get("app.autocont");
         $pass = $autocont->obtener();
         $usu = $persona->getusuarioPersona();

         $persona->setpassPersona($pass);

         $edit_form = $this->createForm('BufeteBundle\Form\EstudiantePassType', $persona, array(
                'PassPersona' => $pass,
           ));
         }
         else {

           //dump($request->request);
           //dump($request->request->get('bufetebundle_personas') );

           $post = Request::createFromGlobals();
           //dump($post->request->get('bufetebundle_personas')['passPersona']);
           $pass = $post->request->get('bufetebundle_personas')['passPersona'];


           $var2=$request2->request->get("idPersona2");

           $em = $this->getDoctrine()->getManager();
           $persona = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
                        'idPersona' => $var2
           ));

           $edit_form = $this->createForm('BufeteBundle\Form\EstudiantePassType', $persona, array(
                  'PassPersona' => $pass,
             ));
             $edit_form->handleRequest($request);
         }

          //$deleteForm = $this->createDeleteForm($persona);

         //if ($edit_form->isSubmitted() && $edit_form->isValid())
         if ($edit_form->isSubmitted())
         {

           $var2=$request2->request->get("idPersona2");

           $em = $this->getDoctrine()->getManager();
           $persona = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
                        'idPersona' => $var2
           ));


             $factory = $this->get("security.encoder_factory");
             $encoder = $factory->getEncoder($persona);
             $password = $encoder->encodePassword($edit_form->get("passPersona")->getData(), $persona->getSalt());

             $persona->setPassPersona($password);


             //$this->getDoctrine()->getManager()->flush();

             $em->persist($persona);
             $flush = $em->flush();

             //return $this->redirectToRoute('personas_indexEstudiantes', array('idPersona' => $persona->getIdpersona()));
             return $this->redirectToRoute('personas_perfil',
                [
                  'var' => $persona
                ], 307);
         }

         return $this->render('personas/editpassestudiante.html.twig', array(
             'persona' => $persona,
             'PassPersona' => $pass,
             'edit_form' => $edit_form->createView(),
             //'delete_form' => $deleteForm->createView(),
         ));
     }


     //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


     ////////////////////////////////////////////////////////////////////////////////////
     /*  EDITAR CONTRASEÑA DE CUALQUIER TIPO DE PERSONA POR EL ADMINISTRADOR */

          public function editpasspersonaAction(Request $request, Personas $persona)
          {
              //GENERAR CONTRASEÑA
              $autocont = $this->get("app.autocont");
              $pass = $autocont->obtener();

              $deleteForm = $this->createDeleteForm($persona);
              die();
              $editForm = $this->createForm('BufeteBundle\Form\EstudiantePassType', $persona, array(
                'passEnvio' =>$pass,
              ));

              $editForm->handleRequest($request);

              if ($editForm->isSubmitted() && $editForm->isValid()) {
                  $factory = $this->get("security.encoder_factory");
                  $encoder = $factory->getEncoder($persona);
                  $password = $encoder->encodePassword($editForm->get("passPersona")->getData(), $persona->getSalt());
                  $persona->setPassPersona($password);

                  $this->getDoctrine()->getManager()->flush();

                  return $this->redirectToRoute('personas_detalle', array('idPersona' => $persona->getIdpersona()));
              }

              return $this->render('personas/editpasspersona.html.twig', array(
                  'persona' => $persona,
                  'edit_form' => $editForm->createView(),
                  'delete_form' => $deleteForm->createView(),

              ));
          }



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

          ////////////////////////////////////////////////////////////////////////////////////
          /*  EDITAR USUARIO DEL ESTUDIANTE */

          /*
               public function edituserestudianteAction(Request $request)
               {

                 $var=$request->request->get("idPersona");

                 if(isset($var))
                 {
                   $em = $this->getDoctrine()->getManager();
                   $persona = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
                                'idPersona' => $var
                   ));
                 }
                 else
                 {
                     $var2=$request->request->get("idPersona2");
                     $em = $this->getDoctrine()->getManager();
                     $persona = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
                                  'idPersona' => $var2
                     ));
                 }

                   $pass = $persona->getpassPersona();
                   $edit_form = $this->createForm('BufeteBundle\Form\EstudianteUserType', $persona, array(
                            'PassPersona' => $pass,
                       ));

                   $edit_form->handleRequest($request);
                   $confirm = null;
                   $status=null;

                   if ($edit_form->isSubmitted() )
                   {
                     //if($edit_form->isValid())
                     {
                       //$this->getDoctrine()->getManager()->flush();
                       $em = $this->getDoctrine()->getManager();

                       $persona_repo = $em->getRepository("BufeteBundle:Personas");
                       $pe = $persona_repo->findOneBy(array('usuarioPersona' => $edit_form->get("usuarioPersona")->getData()));



                           if(count($pe) == 0)
                           {
                               $em->persist($persona);
                               $flush = $em->flush();

                               if ($flush == null)
                               {
                                   $this->session->getFlashBag()->add("status", $status);
                                   $status = "El usuario se ha creado correctamente";
                                   $confirm = true;
                               } else {
                                 $status = "El usuario no se pudo registrar";
                               }
                           }
                           else{
                               $status = "el nombre de usuario ya existe";
                           }

                         }

                           if ($confirm)
                           {
                             return $this->redirectToRoute('personas_studentProfile',
                                [
                                  'var' => $persona
                                ], 307);
                                //$confirm=null;
                           }
                           else
                           {
                             $this->session->getFlashBag()->add("status", $status);
                           }

                   }

                   return $this->render('personas/edituserestudiante.html.twig', array(
                       'persona' => $persona,
                       'PassPersona' => $pass,
                       'edit_form' => $edit_form->createView(),
                       //'delete_form' => $deleteForm->createView(),
                   ));
               }

              */

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////////
/*  EDITAR DATOS DEL ESTUDIANTE */
 public function editEstudianteAction(Request $request)
 {
   $var=$request->request->get("idPersona");
   $em = $this->getDoctrine()->getManager();
   $persona = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
                'idPersona' => $var
   ));

   //$deleteForm = $this->createDeleteForm($persona);

   $carne= $persona->getestudiantes()->getcarneEstudiante();

   $edit_form = $this->createForm('BufeteBundle\Form\editarestudianteType', $persona, array(
      'carneEnvio' => $carne,
    ));

   $edit_form->handleRequest($request);
   $confirm = null;
   $nom = null;

   if ($edit_form->get('save')->isClicked()) {
     $post=$request->get("idPersona2");

     $em = $this->getDoctrine()->getManager();
     $persona = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
                  'idPersona' => $post
     ));

    // return $this->redirectToRoute('personas_editpassestudiante');

     return $this->redirectToRoute('personas_editpassestudiante',
        [
          'id' => $persona
        ], 307);

     $usu = $edit_form->get("usuarioPersona")->getData();
     //GENERAR CONTRASEÑA
     $autocont = $this->get("app.autocont");
     $pass = $autocont->obtener();

     $edit_form = $this->createForm('BufeteBundle\Form\EstudiantePassType', $persona, array(
            'PassPersona' => $pass,
            'UsuPersona' => $usu,
       ));

     return $this->render('personas/editpassestudiante.html.twig', array(
          'persona' => $persona,
         'PassPersona' => $pass,
         'UsuPersona' => $usu,
         'edit_form' => $edit_form->createView(),
     ));
   }

   if ($edit_form->isSubmitted()){
        //($edit_form->isValid()) {
         $post=$request->get("idPersona2");
         $em = $this->getDoctrine()->getManager();
         $persona = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
                      'idPersona' => $post
         ));

         $em2 = $this->getDoctrine()->getManager();
         $estudiante = $em2->getRepository('BufeteBundle:Estudiantes')->findOneBy(array(
                      'idPersona' => $post
         ));

         $persona->setTelefonoPersona($edit_form->get("telefonoPersona")->getData());
         $persona->setTel2Persona($edit_form->get("tel2Persona")->getData());
         $persona->setDpiPersona($edit_form->get("dpiPersona")->getData());
         $persona->setEmailPersona($edit_form->get("emailPersona")->getData());
         $persona->setDireccionPersona($edit_form->get("direccionPersona")->getData());
         $persona->setEstadoPersona($edit_form->get("estadoPersona")->getData());

         $estudiante->setIdTipoPracticante($edit_form->get("estudiantes")->get("idtipopracticante")->getData());
         $estudiante->setCierrePensum($edit_form->get("estudiantes")->get("cierrePensum")->getData());


         $em = $this->getDoctrine()->getManager();
         $persona_repo = $em->getRepository("BufeteBundle:Personas");
         $pe = $persona_repo->findOneBy(array('usuarioPersona' => $persona->getusuarioPersona()));


         if(count($pe) == 0)
         {
             $em->persist($persona);
             $flush = $em->flush();
             if ($flush == null) {
                 $status = "El usuario se ha creado correctamente";
                 $confirm = true;
             } else {
                 $status = "El usuario no se pudo registrar";
             }
         }else {
             $status = "El nombre de usuario ya existe";
         }

       if ($confirm)
       {
         return $this->redirectToRoute('personas_perfil',
            [
              'request' => $persona
            ], 307);
       }
       else {
         $this->session->getFlashBag()->add("status", $status);
       }


    }

    return $this->render('personas/editEstudiante.html.twig', array(
        'persona' => $persona,
        'edit_form' => $edit_form->createView(),
    ));
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////
/*  EDITAR DATOS DE PERSONALLLL  */

  public function editPersonalAction(Request $request)
  {
       $var=$request->request->get("idPersona");

       $em = $this->getDoctrine()->getManager();
       $persona = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
                    'idPersona' => $var
       ));

         //$deleteForm = $this->createDeleteForm($persona);

         $edit_form = $this->createForm('BufeteBundle\Form\editpersonalType', $persona);


         $edit_form->handleRequest($request);


         $confirm = null;
         $nom = null;


         if ($edit_form->get('save')->isClicked()) {
           $post=$request->get("idPersona2");

           $em = $this->getDoctrine()->getManager();
           $persona = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
                        'idPersona' => $post
           ));

          // return $this->redirectToRoute('personas_editpassestudiante');

           return $this->redirectToRoute('personas_editpassestudiante',
              [
                'id' => $persona
              ], 307);

           $usu = $edit_form->get("usuarioPersona")->getData();
           //GENERAR CONTRASEÑA
           $autocont = $this->get("app.autocont");
           $pass = $autocont->obtener();

           $edit_form = $this->createForm('BufeteBundle\Form\PersonalPassType', $persona, array(
                  'PassPersona' => $pass,
                  'UsuPersona' => $usu,
             ));

           return $this->render('personas/editpasspersonal.html.twig', array(
                'persona' => $persona,
               'PassPersona' => $pass,
               'UsuPersona' => $usu,
               'edit_form' => $edit_form->createView(),
           ));
         }



         if ($edit_form->isSubmitted())
         {
         //&& $edit_form->isValid()) {
               $post=$request->get("idPersona2");
               $em = $this->getDoctrine()->getManager();
               $persona = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
                            'idPersona' => $post
               ));

               $persona->setTelefonoPersona($edit_form->get("telefonoPersona")->getData());
               $persona->setTel2Persona($edit_form->get("tel2Persona")->getData());
               $persona->setDpiPersona($edit_form->get("dpiPersona")->getData());
               $persona->setEmailPersona($edit_form->get("emailPersona")->getData());
               $persona->setDireccionPersona($edit_form->get("direccionPersona")->getData());
               $persona->setEstadoPersona($edit_form->get("estadoPersona")->getData());


               $em->persist($persona);
               $flush = $em->flush();

               return $this->redirectToRoute('personas_perfil',
                  [
                    'request' => $persona
                  ], 307);
     }

     return $this->render('personas/editPersonal.html.twig', array(
         'persona' => $persona,
         'edit_form' => $edit_form->createView(),
         //'delete_form' => $deleteForm->createView(),
     ));

}



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




////////////////////////////////////////////////////////////////////////////////////
/*  AGREGAR CUALQUIER TIPO DE PERSONA POR EL ADMINISTRADOR */

 public function newAction(Request $request)
 {
     $persona = new Personas();
     //GENERAR CONTRASEÑA
     $autocont = $this->get("app.autocont");
     $pass = $autocont->obtener();
     $form = $this->createForm('BufeteBundle\Form\PersonasPersonaType', $persona, array(
         'passEnvio' =>$pass,
     ));
     $confirm = false;
     $form->handleRequest($request);
     if ($form->isSubmitted()){
       if ($form->isValid()) {
           $em = $this->getDoctrine()->getManager();
           $persona_repo = $em->getRepository("BufeteBundle:Personas");
           $pe = $persona_repo->findOneBy(array('usuarioPersona' => $form->get("usuarioPersona")->getData()));
           if (count($pe)==0) {
             $factory = $this->get("security.encoder_factory");
             $encoder = $factory->getEncoder($persona);
             $password = $encoder->encodePassword($form->get("passPersona")->getData(), $persona->getSalt());
             $persona->setPassPersona($password);
             //$em = $this->getDoctrine()->getManager();

             $file = $persona->getFoto();
               if(($file instanceof UploadedFile) && ($file->getError() == '0'))
               {
                 $validator = $this->get('validator');
                 $errors = $validator->validate($persona);
                 if (count($errors) > 0)
                 {
                   $errorsString = (string) $errors;
                   return new Response($errorsString);
                 }

                 $fileName = md5(uniqid()).'.'.$file->guessExtension();
                 $cvDir = $this->container->getparameter('kernel.root_dir').'/../web/uploads/profile';
                 $file->move($cvDir, $fileName);
                 $persona->setFoto($fileName);
               }

             $em->persist($persona);
             $flush = $em->flush();
             if ($flush == null) {
                 $status = "El usuario se ha creado correctamente";
                 $confirm = true;
             } else {
               $status = "El usuario no se pudo registrar";
             }
         } else {
               $status = "El usuario ya existe";
         }
       } else {
           $status = "El formulario no es valido";
       }
       if ($confirm) {

         $var=$persona->getIdPersona();



         $em = $this->getDoctrine()->getManager();
         $persona = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
                      'idPersona' => $var
         ));

         return $this->redirectToRoute('personas_perfil',
            [
              'var' => $var
            ], 307);
       }else {
         $this->session->getFlashBag()->add("status", $status);
       }
     }
     return $this->render('personas/new.html.twig', array(
         'persona' => $persona,
         'form' => $form->createView(),
     ));
 }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////
/*  EDITAR DATOS DE CUALQUIER TIPO DE PERSONA POR EL ADMINISTRADOR */

 public function editPersonaAction(Request $request)
 {

   $post = Request::createFromGlobals();

   $var=$request->request->get("idPersona");
   if( ($var == null) && isset( $post->request->get('editpersona')['idPersona'] ))
   {
     $post = Request::createFromGlobals();
     $var= $post->request->get('editpersona')['idPersona'];
   }


   if($var==null)
   {
     $var = $post->request->get("idPersona");
     die();
   }

   $em = $this->getDoctrine()->getManager();
   $persona = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
                'idPersona' => $var
   ));

     $confirm = null;
     $editForm = $this->createForm('BufeteBundle\Form\editpersonaType', $persona);
     $editForm->handleRequest($request);

     if ($editForm->get('save')->isClicked()) {

       $post=$request->get("idPersona2");
       $em = $this->getDoctrine()->getManager();
       $persona = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
                    'idPersona' => $post
       ));

       return $this->redirectToRoute('personas_editpassestudiante',
          [
            'id' => $persona
          ], 307);
        }

     if ($editForm->isSubmitted()) {



       //($editForm->isValid()) {


       $em = $this->getDoctrine()->getManager();
       $persona_repo = $em->getRepository("BufeteBundle:Personas");
       $pe = $persona_repo->findOneBy(array('usuarioPersona' => $persona->getusuarioPersona()));


         if(count($pe) == 0)
         {
             $em->persist($persona);
             $flush = $em->flush();
             if ($flush == null) {
                 $status = "El usuario se ha creado correctamente";
                 $confirm = true;
             } else {
                 $status = "El usuario no se pudo registrar";
             }
         }else {
             $status = "El nombre de usuario ya existe";
         }

       if ($confirm)
       {
         return $this->redirectToRoute('personas_perfil',
            [
              'request' => $persona
            ], 307);
       }
       else {
         $this->session->getFlashBag()->add("status", $status);
       }
     }

     return $this->render('personas/editPersona.html.twig', array(
         'persona' => $persona,
         'edit_form' => $editForm->createView(),
     ));
 }
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////////
/*  AGREGAR ASESOR POR SECRETARIO */

public function newpersonalAction(Request $request)
{
$persona = new Personas();

//GENERAR CONTRASEÑA
$autocont = $this->get("app.autocont");
$pass = $autocont->obtener();

$form = $this->createForm('BufeteBundle\Form\PersonasAsesorType', $persona, array(
   'passEnvio' =>$pass,
));
$confirm = false;
$form->handleRequest($request);

if ($form->isSubmitted()){
 if ($form->isValid()) {
     $em = $this->getDoctrine()->getManager();
     $persona_repo = $em->getRepository("BufeteBundle:Personas");
     $pe = $persona_repo->findOneBy(array('usuarioPersona' => $form->get("usuarioPersona")->getData()));

     if (count($pe)==0) {
       $factory = $this->get("security.encoder_factory");
       $encoder = $factory->getEncoder($persona);
       $password = $encoder->encodePassword($form->get("passPersona")->getData(), $persona->getSalt());
       $persona->setPassPersona($password);
       $persona->setIdBufete($this->getUser()->getIdBufete());
       //$em = $this->getDoctrine()->getManager();

       $file = $persona->getFoto();
         if(($file instanceof UploadedFile) && ($file->getError() == '0'))
         {
           $validator = $this->get('validator');
           $errors = $validator->validate($persona);
           if (count($errors) > 0)
           {
             $errorsString = (string) $errors;
             return new Response($errorsString);
           }

           $fileName = md5(uniqid()).'.'.$file->guessExtension();
           $cvDir = $this->container->getparameter('kernel.root_dir').'/../web/uploads/profile';
           $file->move($cvDir, $fileName);
           $persona->setFoto($fileName);
         }

       $em->persist($persona);
       $flush = $em->flush();
       if ($flush == null) {
           $status = "El usuario se ha creado correctamente";
           $confirm = true;
       } else {
         $status = "El usuario no se pudo registrar";
       }
   } else {
         $status = "El usuario ya existe";
   }
 } else {
     $status = "El formulario no es valido";
 }
 if ($confirm) {
   $var=$persona->getIdPersona();



   $em = $this->getDoctrine()->getManager();
   $persona = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
                'idPersona' => $var
   ));

   //dump($persona);
   //die();

   return $this->redirectToRoute('personas_perfil',
      [
        'var' => $var
      ], 307);
 }else {
   $this->session->getFlashBag()->add("status", $status);
 }
}

return $this->render('personas/newpersonal.html.twig', array(
   'persona' => $persona,
   'form' => $form->createView(),
));
}



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

}
