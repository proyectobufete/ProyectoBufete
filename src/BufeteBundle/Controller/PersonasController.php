<?php

namespace BufeteBundle\Controller;

use BufeteBundle\Entity\Personas;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Session\Session;
use BufeteBundle\Entity\Estudiantes;
use BufeteBundle\Form\PersonasType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use BufeteBundle\Entity\Post;


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

  public function indexAsesoresAction()
  {

      $em = $this->getDoctrine()->getManager();

      $query = $em->CreateQuery(
          "SELECT p FROM BufeteBundle:Personas p
          WHERE p.role LIKE 'ROLE_ASESOR'"
        );

        $asesores = $query->getResult();

        return $this->render('personas/indexAsesores.html.twig', array(
          'asesores' => $asesores,
        ));
  }

  public function indexEstudiantesAction()
  {
    $em = $this->getDoctrine()->getManager();

/*
    $query = $em->CreateQuery(
        "SELECT p FROM BufeteBundle:Personas p
        WHERE p.role LIKE 'ROLE_ESTUDIANTE'"
      );
*/
    $query = $em->CreateQuery(
       "SELECT p FROM BufeteBundle:Personas p
        INNER JOIN BufeteBundle:Estudiantes e
        WITH p=e.idPersona"
      );

      $estudiantes = $query->getResult();

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

          $nomComp ="";
          $carrera ="";
          $telefono="";
          $correo="";
          $direccion="";
          $muni_dep="";

          if(isset($datos->STATUS,$datos->DATOS[0]->CARNET,$datos->DATOS[0]->NOM1))
          {
              $carne = $datos->DATOS[0]->CARNET;
              $nomComp =$datos->DATOS[0]->NOM1." ".$datos->DATOS[0]->NOM2." ".$datos->DATOS[0]->NOM3." ".$datos->DATOS[0]->APE1." ".$datos->DATOS[0]->APE2;
              $telefono=$datos->DATOS->TELEFONO;
              $correo=$datos->DATOS->CORREO;
              $direccion=$datos->DATOS->DIRECCION;
              $muni_dep=$datos->DATOS->MUNICIPIO." ".$datos->DATOS->DEPARTAMENTO;
          }

          $form = $this->createForm('BufeteBundle\Form\PersonasType', $persona,
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




    /**
     * Lists all persona entities.
     *
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();

        $personas = $em->getRepository('BufeteBundle:Personas')->findAll();

        return $this->render('personas/index.html.twig', array(
            'personas' => $personas,
        ));
    }


    /**
     * Creates a new persona entity.
     *
     */
    public function newAction(Request $request)
    {
        $persona = new Personas();

        //GENERAR CONTRASEÑA
        $autocont = $this->get("app.autocont");
        $pass = $autocont->obtener();

        $form = $this->createForm('BufeteBundle\Form\PersonasnuevasType', $persona, array(
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
     * Displays a form to edit an existing persona entity.
     *
     */
     public function editEstudianteAction(Request $request, Personas $persona)
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
           $editForm = $this->createForm('BufeteBundle\Form\PersonasType', $persona, array(
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
             $this->getDoctrine()->getManager()->flush();

             return $this->redirectToRoute('personas_editEstudiante', array('idPersona' => $persona->getIdpersona()));
         }

         return $this->render('personas/editEstudiante.html.twig', array(
             'persona' => $persona,
             'edit_form' => $editForm->createView(),
             'delete_form' => $deleteForm->createView(),
         ));
     }

     public function editPersonalAction(Request $request, Personas $persona)
     {
         $nomComp = $persona->getnombrePersona();
         $telefono = $persona->gettelefonoPersona();
         $direccion = $persona->getdireccionPersona();
         $correo = $persona->getemailPersona();

         //GENERAR CONTRASEÑA
         $autocont = $this->get("app.autocont");
         $pass = $autocont->obtener();


         $deleteForm = $this->createDeleteForm($persona);

         if($persona->getrole() == "ROLE_ADMIN" || "ROLE_ASESOR" || "ROLE_SECRETARIO" ||"ROLE_DIRECTOR")
         {
             $carne = "null";
             $editForm = $this->createForm('BufeteBundle\Form\PersonasnuevasType', $persona, array(
               'passEnvio' =>$pass,
             ));
         }
         $editForm->handleRequest($request);

         if ($editForm->isSubmitted() && $editForm->isValid()) {
             $this->getDoctrine()->getManager()->flush();

             return $this->redirectToRoute('personas_editPersonal', array('idPersona' => $persona->getIdpersona()));
         }

         return $this->render('personas/editPersonal.html.twig', array(
             'persona' => $persona,
             'edit_form' => $editForm->createView(),
             'delete_form' => $deleteForm->createView(),

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
