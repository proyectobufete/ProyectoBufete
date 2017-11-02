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

  /**
   * Lista de asesores
   *
   */
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

        return $this->render('personas/indexAsesores.html.twig', array(
          'asesores' => $asesores,
        ));
  }

  /**
   * Lista de casos laborales segun el asesor logueado
   *
   */
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

      return $this->render('casos/laboralesestudiante.html.twig', array(
          'casos' => $casos,
      ));
  }

  /**
   * Lista de casos civiles segun el asesor logueado
   *
   */
  public function civilesAsesorAction()
  {
      $idAsesor = $this->getUser()->getIdPersona();
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery(
        "SELECT c FROM BufeteBundle:Casos c
        INNER JOIN BufeteBundle:Civiles ci WITH c = ci.idCaso
        WHERE c.idPersona = :id
        ORDER BY c.fechaCaso DESC"
      )->setParameter('id', $idAsesor);
      $casos = $query->getResult();

      return $this->render('casos/civilesestudiante.html.twig', array(
          'casos' => $casos,
      ));
  }

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
    return $this->render('personas/indexEstudiantes.html.twig', array(
        'estudiantes' => $estudiantes,
    ));
  }


  public function detalleAction(Personas $persona)
  {
      $deleteForm = $this->createDeleteForm($persona);

      return $this->render('personas/detalle.html.twig', array(
          'persona' => $persona,
          'delete_form' => $deleteForm->createView(),
      ));
  }

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

      return $this->render('personas/index.html.twig', array(
          'personas' => $personas,
      ));
  }

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

  /**
   * funcion para el historial de estudiantes
   *
   */
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
public function HistorialEstudianteLaboralAction(Request $request, Personas $persona)
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
public function HistorialEstudianteClinicasAction(Request $request, Personas $persona)
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
/*  AGREGAR ESTUDIANTE */



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
              $status = "CARNE INCORRECTO";
              $this->session->getFlashBag()->add("status", $status);
            }
            else if($datos==16)
            {
              $status = "EL ESTUDIANTE NO SE ENCUENTRA INSCRITO";
              $this->session->getFlashBag()->add("status", $status);
            }
            else if($datos==10)
            {
              $status = "EL ESTUDIANTE NO ESTA INSCRITO EN LA DIVISIÓN DE CIENCIAS JURIDICAS Y SOCIALES";
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

                return $this->redirectToRoute('personas_detalle', array('idPersona' => $persona->getIdPersona()));
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

  ////////////////////////////////////////////////////////////////////////////////////
  /*  EDITAR DATOS DEL ESTUDIANTE */
     public function editEstudianteAction(Request $request, Personas $persona)
     {
         $nomComp = $persona->getnombrePersona();
         $telefono = $persona->gettelefonoPersona();
         $direccion = $persona->getdireccionPersona();
         $correo = $persona->getemailPersona();
         $usuario = $persona->getusuarioPersona();
         //GENERAR CONTRASEÑA
         $autocont = $this->get("app.autocont");
         $pass = $autocont->obtener();

         $deleteForm = $this->createDeleteForm($persona);

         if($persona->getrole() == "ROLE_ESTUDIANTE")
         {
           $carne = $persona->getestudiantes()->getcarneEstudiante();
           $editForm = $this->createForm('BufeteBundle\Form\editarestudianteType', $persona, array(
               'nombreEnvio' => $nomComp,
               'carneEnvio'=> $carne,
               'telefonoEnvio'=>$telefono,
               'direccionEnvio'=>$direccion,
               'correoEnvio'=>$correo,
               //'passEnvio' =>$pass,
           ));
         }

         $editForm->handleRequest($request);
         $confirm = null;
         $nom = null;
         if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $username = $editForm->get("usuarioPersona")->getData();
            $persona_repo = $em->getRepository("BufeteBundle:Personas");
            $pe = $persona_repo->findOneBy(array('usuarioPersona' => $editForm->get("usuarioPersona")->getData()));
            if($usuario == $username)
            {
              $em->persist($persona);
              $flush = $em->flush();
              if ($flush == null) {
                  $status = "El usuario se ha creado correctamente";
                  $confirm = true;
              } else {
                $status = "El usuario no se pudo registrar";
              }
            }elseif ($usuario != $username ) {
              if(count($pe) == 0){
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
            }

            if ($confirm) {
              return $this->redirectToRoute('personas_indexEstudiantes');
            }else {
              $this->session->getFlashBag()->add("status", $status);
            }
             //$this->getDoctrine()->getManager()->flush();
             //return $this->redirectToRoute('personas_editEstudiante', array('idPersona' => $persona->getIdpersona()));
         }

         return $this->render('personas/editEstudiante.html.twig', array(
             'persona' => $persona,
             'edit_form' => $editForm->createView(),
             'delete_form' => $deleteForm->createView(),
         ));
     }

////////////////////////////////////////////////////////////////////////////////////
/*  EDITAR CONTRASEÑA DEL ESTUDIANTE */
     public function editpassestudianteAction(Request $request, Personas $persona)
     {
         $nomComp = $persona->getnombrePersona();
         $telefono = $persona->gettelefonoPersona();
         $direccion = $persona->getdireccionPersona();
         $correo = $persona->getemailPersona();

         //GENERAR CONTRASEÑA
         $autocont = $this->get("app.autocont");
         $pass = $autocont->obtener();

         $deleteForm = $this->createDeleteForm($persona);

         if($persona->getrole() == "ROLE_ESTUDIANTE")
         {
           $carne = $persona->getestudiantes()->getcarneEstudiante();
           $editForm = $this->createForm('BufeteBundle\Form\PersonasEstudianteType', $persona, array(
               'nombreEnvio' => $nomComp,
               'carneEnvio'=> $carne,
               'telefonoEnvio'=>$telefono,
               'direccionEnvio'=>$direccion,
               'correoEnvio'=>$correo,
               'passEnvio' =>$pass,
           ));
         }

         $editForm->handleRequest($request);

         if ($editForm->isSubmitted() && $editForm->isValid()) {
             $factory = $this->get("security.encoder_factory");
             $encoder = $factory->getEncoder($persona);
             $password = $encoder->encodePassword($editForm->get("passPersona")->getData(), $persona->getSalt());
             $persona->setPassPersona($password);
             $this->getDoctrine()->getManager()->flush();


             return $this->redirectToRoute('personas_detalle', array('idPersona' => $persona->getIdpersona()));
         }

         return $this->render('personas/editpassestudiante.html.twig', array(
             'persona' => $persona,
             'edit_form' => $editForm->createView(),
             'delete_form' => $deleteForm->createView(),
         ));
     }


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
             return $this->redirectToRoute('personas_detalle', array('idPersona' => $persona->getIdPersona()));
           }else {
             $this->session->getFlashBag()->add("status", $status);
           }
         }
         return $this->render('personas/new.html.twig', array(
             'persona' => $persona,
             'form' => $form->createView(),
         ));
     }

////////////////////////////////////////////////////////////////////////////////////
/*  EDITAR DATOS DE CUALQUIER TIPO DE PERSONA POR EL ADMINISTRADOR */

     public function editPersonaAction(Request $request, Personas $persona)
     {
         $usuario = $persona->getusuarioPersona();
         $deleteForm = $this->createDeleteForm($persona);
         $editForm = $this->createForm('BufeteBundle\Form\editpersonaType', $persona);

         $editForm->handleRequest($request);
         $confirm = null;
         if ($editForm->isSubmitted() && $editForm->isValid()) {
           $em = $this->getDoctrine()->getManager();
           $username = $editForm->get("usuarioPersona")->getData();
           $persona_repo = $em->getRepository("BufeteBundle:Personas");
           $pe = $persona_repo->findOneBy(array('usuarioPersona' => $editForm->get("usuarioPersona")->getData()));
           if($usuario == $username)
           {
             $em->persist($persona);
             $flush = $em->flush();
             if ($flush == null) {
                 $status = "El usuario se ha creado correctamente";
                 $confirm = true;
             } else {
               $status = "El usuario no se pudo registrar";
             }
           }elseif ($usuario != $username ) {
             if(count($pe) == 0){
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
           }
           if ($confirm) {
             return $this->redirectToRoute('personas_index');
           }else {
             $this->session->getFlashBag()->add("status", $status);
           }
             //$this->getDoctrine()->getManager()->flush();
             //return $this->redirectToRoute('personas_editPersona', array('idPersona' => $persona->getIdpersona()));
         }

         return $this->render('personas/editPersona.html.twig', array(
             'persona' => $persona,
             'edit_form' => $editForm->createView(),
             'delete_form' => $deleteForm->createView(),
         ));
     }

////////////////////////////////////////////////////////////////////////////////////
/*  EDITAR CONTRASEÑA DE CUALQUIER TIPO DE PERSONA POR EL ADMINISTRADOR */

     public function editpasspersonaAction(Request $request, Personas $persona)
     {
         //GENERAR CONTRASEÑA
         $autocont = $this->get("app.autocont");
         $pass = $autocont->obtener();

         $deleteForm = $this->createDeleteForm($persona);

         $editForm = $this->createForm('BufeteBundle\Form\PersonasPersonaType', $persona, array(
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
            return $this->redirectToRoute('personas_detalle', array('idPersona' => $persona->getIdPersona()));
          }else {
            $this->session->getFlashBag()->add("status", $status);
          }
        }

        return $this->render('personas/newpersonal.html.twig', array(
            'persona' => $persona,
            'form' => $form->createView(),
        ));
    }

////////////////////////////////////////////////////////////////////////////////////
/*  EDITAR DATOS DE ASESOR POR SECRETARIO */

     public function editAsesorAction(Request $request, Personas $persona)
     {
         $usuario = $persona->getusuarioPersona();
         $deleteForm = $this->createDeleteForm($persona);
         $editForm = $this->createForm('BufeteBundle\Form\editasesorType', $persona);

         $editForm->handleRequest($request);
         $confirm = null;
         if ($editForm->isSubmitted() && $editForm->isValid()) {
           $em = $this->getDoctrine()->getManager();
           $username = $editForm->get("usuarioPersona")->getData();
           $persona_repo = $em->getRepository("BufeteBundle:Personas");
           $pe = $persona_repo->findOneBy(array('usuarioPersona' => $editForm->get("usuarioPersona")->getData()));
           if($usuario == $username)
           {
             $em->persist($persona);
             $flush = $em->flush();
             if ($flush == null) {
                 $status = "El usuario se ha creado correctamente";
                 $confirm = true;
             } else {
               $status = "El usuario no se pudo registrar";
             }
           }elseif ($usuario != $username ) {
             if(count($pe) == 0){
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
           }

           if ($confirm) {
             return $this->redirectToRoute('personas_indexAsesores');
           }else {
             $this->session->getFlashBag()->add("status", $status);
           }

             //$this->getDoctrine()->getManager()->flush();
             //return $this->redirectToRoute('personas_detalle', array('idPersona' => $persona->getIdpersona()));
         }

         return $this->render('personas/editAsesor.html.twig', array(
             'persona' => $persona,
             'edit_form' => $editForm->createView(),
             'delete_form' => $deleteForm->createView(),
         ));
     }

////////////////////////////////////////////////////////////////////////////////////
/*  EDITAR CONTRASEÑA DE  ASESOR POR SECRETARIO */

     public function editpassasesorAction(Request $request, Personas $persona)
     {
         //GENERAR CONTRASEÑA
         $autocont = $this->get("app.autocont");
         $pass = $autocont->obtener();

         $deleteForm = $this->createDeleteForm($persona);

         $editForm = $this->createForm('BufeteBundle\Form\PersonasAsesorType', $persona, array(
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

         return $this->render('personas/editpassasesor.html.twig', array(
             'persona' => $persona,
             'edit_form' => $editForm->createView(),
             'delete_form' => $deleteForm->createView(),
         ));
     }

     /**
      * Lista de asesores para el Director
      *
      */
     public function indexAsesoresDirAction(Request $request)
     {
         $em = $this->getDoctrine()->getManager();
         $searchQuery = $request->get('query');
         $searchBufete = $request->get('sbufete');
         $asesores = null;
         $rol = $this->getUser()->getRole();

         if ($rol == "ROLE_DIRECTOR") {
           if (!empty($searchQuery ||$searchBufete)) {
             $repo = $em->getRepository("BufeteBundle:Personas");
             $query = $repo->createQueryBuilder('p')
             #->where('p.nombrePersona LIKE :param')
             ->Where('p.idBufete = :searchbufete')
             ->andWhere('p.role = :rol')
             ->setParameter('rol', 'ROLE_ASESOR')
             ->setParameter('searchbufete', $searchBufete)
             #->setParameter('param', '%'.$searchQuery.'%')
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

           return $this->render('personas/indexAsesorDir.html.twig', array(
             'asesores' => $asesores,
             'bufetes' => $bufetes,
           ));
     }

     /**
      * funcion para el historial de Asesor
      *
      */
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



    /**
     * Deletes a persona entity.
     *
     */
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

    /**
     * Creates a form to delete a persona entity.
     *
     * @param Personas $persona The persona entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Personas $persona)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('personas_delete', array('idPersona' => $persona->getIdpersona())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
