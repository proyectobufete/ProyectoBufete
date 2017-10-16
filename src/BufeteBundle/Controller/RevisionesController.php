<?php

namespace BufeteBundle\Controller;

use BufeteBundle\Entity\Revisiones;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use BufeteBundle\Entity\Casos;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use BufeteBundle\Form\RevisionesEstudiantesType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

/**
 * Revisione controller.
 *
 */
class RevisionesController extends Controller
{


    /**
     * Lists all revisione entities.
     *
     */
    public function indexRevisionesAction()
    {
        $em = $this->getDoctrine()->getManager();

        $revisiones = $em->getRepository('BufeteBundle:Revisiones')->findAll();

        return $this->render('revisiones/indexRevisiones.html.twig', array(
            'revisiones' => $revisiones,
        ));
    }

    public function indexInformesAction()
    {
        $em = $this->getDoctrine()->getManager();

        $revisiones = $em->getRepository('BufeteBundle:Revisiones')->findAll();

        return $this->render('revisiones/indexInformes.html.twig', array(
            'revisiones' => $revisiones,
        ));
    }

    public function indexLinkAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $revisiones = $em->getRepository('BufeteBundle:Revisiones')->findAll();

        return $this->render('revisiones/indexLink.html.twig', array(
            'revisiones' => $revisiones,

        ));
    }

    public function indexLinkCasoAction(Request $request)
    {
      $var=$request->request->get("idCaso");

      $em = $this->getDoctrine()->getManager();
      $caso_datos = $em->getRepository('BufeteBundle:Casos')->findOneBy(array(
                   'idCaso' => $var
      ));

        $em = $this->getDoctrine()->getManager();
        $revisiones = $em->getRepository('BufeteBundle:Revisiones')->findBy(
          array(), array('idRevision' => 'DESC')
        );

        $rol = $this->getUser()->getRole();
        if($rol=='ROLE_ASESOR')
        {
          return $this->render('revisiones/indexLinkCaso.html.twig', array(
              'revisiones' => $revisiones,
              'ruta'=> 'uploads/final/',
              'varEnvio' =>$var,
              'casoEnvio'=>$caso_datos,
          ));
        }
        elseif ($rol=='ROLE_ESTUDIANTE')
        {
          return $this->render('revisiones/indexLinkCasoEst.html.twig', array(
              'revisiones' => $revisiones,
              'ruta'=> 'uploads/final/',
              'varEnvio' =>$var,
              'casoEnvio'=>$caso_datos,
          ));
        }
    }

/*
    public function indexLinkCasoEstAction(Request $request)
    {
      $var=$request->request->get("idCaso");

      $em = $this->getDoctrine()->getManager();
      $caso_datos = $em->getRepository('BufeteBundle:Casos')->findOneBy(array(
                   'idCaso' => $var
      ));

        $em = $this->getDoctrine()->getManager();
        $revisiones = $em->getRepository('BufeteBundle:Revisiones')->findBy(
          array(), array('idRevision' => 'DESC')
        );

          return $this->render('revisiones/indexLinkCasoEst.html.twig', array(
              'revisiones' => $revisiones,
              'ruta'=> 'uploads/final/',
              'varEnvio' =>$var,
              'casoEnvio'=>$caso_datos,
          ));
    }
*/

    /**
     * Creates a new revisione entity.
     *
     */
    public function newLinkAction(Request $request)
    {
        $revisione = new Revisiones();
        $caso = new Casos();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm('BufeteBundle\Form\RevisionesType', $revisione);
        $form->handleRequest($request);


                $var=$request->request->get("idCaso");
                $nuevavar = (int)$var;
                $idrecibido=$var;

                $em = $this->getDoctrine()->getManager();
                $caso_repo1 = $em->getRepository("BufeteBundle:Casos");
                $numcaso = $caso_repo1->find($idrecibido);
                $num = $numcaso->getNoCaso();


        if ($form->isSubmitted() && $form->isValid()) {

          $em = $this->getDoctrine()->getManager();
          $caso_repo = $em->getRepository("BufeteBundle:Casos");
          $idCaso = $caso_repo->find($idrecibido);
          $revisione->setIdCaso($idCaso);

          $revisione->setIdRevisado(111);



          $revisione->SetIdPersona($idCaso->getIdEstudiante()->getIdEstudiante());


            $file = $revisione->getRutaArchivo();

                            if(($file instanceof UploadedFile) && ($file->getError() == '0'))
                            {
                              $validator = $this->get('validator');
                              $errors = $validator->validate($revisione);
                              if (count($errors) > 0)
                              {

                                $errorsString = (string) $errors;
                                return new Response($errorsString);
                              }

                              $revisione->setNombreArchivo($file->getClientOriginalName());
                              $fileName = md5(uniqid()).'.'.$file->guessExtension();
                              $cvDir = $this->container->getparameter('kernel.root_dir').'/../web/uploads/final';
                              $file->move($cvDir, $fileName);
                              $revisione->setRutaArchivo($fileName);
                            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($revisione);
            $em->flush();

            return $this->redirectToRoute('revisiones_showLink', array('idRevision' => $revisione->getIdrevision()));
        }

        return $this->render('revisiones/newLink.html.twig', array(
            'revisione' => $revisione,
            'var'=> $var,
            'comEnvio'=>$num,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a revisione entity.
     *
     */
    public function showInformeAction(Revisiones $revisione)
    {

      $idcaso = $revisione->getIdcaso()->getIdcaso();
      $em = $this->getDoctrine()->getManager();
      $caso_datos = $em->getRepository('BufeteBundle:Casos')->findOneBy(array(
            'idCaso' => $idcaso,
      ));
      $idasesor = $caso_datos->getIdPersona()->getIdPersona();

      $em = $this->getDoctrine()->getManager();
      $asesor_datos = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
                   'idPersona' => $idasesor
      ));

      $nombre = $asesor_datos->getNombrePersona();
      $numcaso = $caso_datos->getNoCaso();

        $deleteForm = $this->createDeleteForm($revisione);

        return $this->render('revisiones/showInforme.html.twig', array(
            'revisione' => $revisione,
            'ruta'=> 'uploads/final/',
            'nombreEnvio' => $nombre,
            'nocasoEnvio' => $numcaso,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function showLinkAction(Revisiones $revisione)
    {

      $idestudiante = $revisione->getIdPersona();
      $em = $this->getDoctrine()->getManager();
      $estudiante_datos = $em->getRepository('BufeteBundle:Estudiantes')->findOneBy(array(
                   'idEstudiante' => $idestudiante
      ));
      $idpersona = $estudiante_datos->getIdPersona();

      $em = $this->getDoctrine()->getManager();
      $persona_datos = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
                   'idPersona' => $idpersona
      ));


      $idcaso = $revisione->getIdcaso();
      $em = $this->getDoctrine()->getManager();
      $caso_datos = $em->getRepository('BufeteBundle:Casos')->findOneBy(array(
                   'idCaso' => $idcaso
      ));

      $nombre = $persona_datos->getNombrePersona();
      $carne = (string)$estudiante_datos->getCarneEstudiante();
      $numcaso = $caso_datos->getNoCaso();




        $deleteForm = $this->createDeleteForm($revisione);



        return $this->render('revisiones/showLink.html.twig', array(
            'revisione' => $revisione,
            'nombrecarneEnvio' => $nombre." - ".$carne,
            'nocasoEnvio' => $numcaso,
            'ruta'=> 'uploads/final/',
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function showRevisionAction(Revisiones $revisione)
    {
      $id = $revisione->getIdPersona();
      $em = $this->getDoctrine()->getManager();
      $persona = $em->getRepository('BufeteBundle:Personas')->findOneBy(array(
            'idPersona' => $id,
      ));


      $idcaso = $revisione->getIdcaso();
      $em = $this->getDoctrine()->getManager();
      $caso_datos = $em->getRepository('BufeteBundle:Casos')->findOneBy(array(
                   'idCaso' => $idcaso
      ));

      $nombre = $persona->getNombrePersona();
      $numcaso = $caso_datos->getNoCaso();



        $deleteForm = $this->createDeleteForm($revisione);

        $rol = $this->getUser()->getRole();
        if($rol=='ROLE_ASESOR')
        {
          return $this->render('revisiones/showRevision.html.twig', array(
              'revisione' => $revisione,
              'asesorEnvio' =>$nombre,
              'nocasoEnvio' => $numcaso,
              'ruta'=> 'uploads/final/',
              'delete_form' => $deleteForm->createView(),
          ));
        }
        elseif ($rol=='ROLE_ESTUDIANTE')
        {
          return $this->render('revisiones/showRevisionEstudiante.html.twig', array(
              'revisione' => $revisione,
              'asesorEnvio' =>$nombre,
              'nocasoEnvio' => $numcaso,
              'ruta'=> 'uploads/final/',
              'delete_form' => $deleteForm->createView(),
          ));
        }
    }

    /**
     * Displays a form to edit an existing revisione entity.
     *
     */
    public function uploadAction(Request $request)
    {

        $post=$request->get("idRevision");

        $em = $this->getDoctrine()->getManager();
        //$revisione = $em->getRepository('BufeteBundle:Revisiones')->find($post);
        $revisione = $em->getRepository('BufeteBundle:Revisiones')->findOneBy(array(
                     'idRevision' => $post
        ));






        $editForm = $this->createForm('BufeteBundle\Form\RevisionesEstudiantesType', $revisione);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

          if ($revisione->getnombreArchivo() == "")
          {


              $file = $revisione->getRutaArchivo();

              if(($file instanceof UploadedFile) && ($file->getError() == '0'))
              {
                $validator = $this->get('validator');
                $errors = $validator->validate($revisione);
                  if (count($errors) > 0)
                  {
                    $errorsString = (string) $errors;
                    return new Response($errorsString);
                  }

                $revisione->setNombreArchivo($file->getClientOriginalName());
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $cvDir = $this->container->getparameter('kernel.root_dir').'/../web/uploads/final';
                $file->move($cvDir, $fileName);
                $revisione->setRutaArchivo($fileName);



              }




          }



                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('revisiones_showInforme', array('idRevision' => $revisione->getIdrevision()));
        }

        return $this->render('revisiones/upload.html.twig', array(
            'envio'=> $revisione,
            'revisione' => $revisione,
            'edit_form' => $editForm->createView(),

        ));
    }

    public function editLinkAction(Request $request)
    {
        $post=$request->get("idRevision");

        $em = $this->getDoctrine()->getManager();
        //$revisione = $em->getRepository('BufeteBundle:Revisiones')->find($post);
        $revisione = $em->getRepository('BufeteBundle:Revisiones')->findOneBy(array(
                     'idRevision' => $post
        ));

          $fechaRecibida = $revisione->getFechaLimite()->format('Y-m-d H:i:s');

          /*
            $date = new \DateTime('2000-01-01 2:2:1');
            $fecha2 = $date->format('Y-m-d H:i:s');
            echo $fecha2;

            echo $ddd;
            die();
          */

        $editForm = $this->createForm('BufeteBundle\Form\RevisionesType', $revisione);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

          $file = $revisione->getRutaArchivo();

          if(($file instanceof UploadedFile) && ($file->getError() == '0'))
          {
            $validator = $this->get('validator');
            $errors = $validator->validate($revisione);
            if (count($errors) > 0)
            {

              $errorsString = (string) $errors;
              return new Response($errorsString);
            }

            $revisione->setNombreArchivo($file->getClientOriginalName());
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $cvDir = $this->container->getparameter('kernel.root_dir').'/../web/uploads/final';
            $file->move($cvDir, $fileName);
            $revisione->setRutaArchivo($fileName);
          }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('revisiones_showLink', array('idRevision' => $revisione->getIdrevision()));
        }

        echo $fechaRecibida;
        die();

        return $this->render('revisiones/editLink.html.twig', array(
            'envio'=> $revisione,
            'revisione' => $revisione,
            'fechLimEnvio' =>$fechaRecibida,
            'edit_form' => $editForm->createView(),

        ));
    }

    public function editRevisionAction(Request $request)
    {
        $post=$request->get("idRevision");

        $em = $this->getDoctrine()->getManager();
        //$revisione = $em->getRepository('BufeteBundle:Revisiones')->find($post);
        $revisione = $em->getRepository('BufeteBundle:Revisiones')->findOneBy(array(
                     'idRevision' => $post
        ));


        $editForm = $this->createForm('BufeteBundle\Form\RevisionesAsesorType', $revisione);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

          $file = $revisione->getRutaArchivo();

          if(($file instanceof UploadedFile) && ($file->getError() == '0'))
          {
            $validator = $this->get('validator');
            $errors = $validator->validate($revisione);
            if (count($errors) > 0)
            {

              $errorsString = (string) $errors;
              return new Response($errorsString);
            }

            $revisione->setNombreArchivo($file->getClientOriginalName());
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $cvDir = $this->container->getparameter('kernel.root_dir').'/../web/uploads/final';
            $file->move($cvDir, $fileName);
            $revisione->setRutaArchivo($fileName);
          }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('revisiones_showInforme', array('idRevision' => $revisione->getIdrevision()));
        }

        return $this->render('revisiones/upload.html.twig', array(
            'envio'=> $revisione,
            'revisione' => $revisione,
            'edit_form' => $editForm->createView(),

        ));
    }
    public function uploadRevisionAction(Request $request)
    {
        $revisione = new Revisiones();
        $caso = new Casos();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm('BufeteBundle\Form\RevisionesAsesorType', $revisione);
        $form->handleRequest($request);


                $var=$request->request->get("idCaso");
                $nuevavar = (int)$var;
                $idrecibido=$var;

        if ($form->isSubmitted() && $form->isValid()) {

          $em = $this->getDoctrine()->getManager();
          $caso_repo = $em->getRepository("BufeteBundle:Casos");
          $idCaso = $caso_repo->find($idrecibido);
          $revisione->setIdCaso($idCaso);


          $revisione->SetIdPersona($idCaso->getIdPersona()->getIdPersona());

            $file = $revisione->getRutaArchivo();

                            if(($file instanceof UploadedFile) && ($file->getError() == '0'))
                            {
                              $validator = $this->get('validator');
                              $errors = $validator->validate($revisione);
                              if (count($errors) > 0)
                              {

                                $errorsString = (string) $errors;
                                return new Response($errorsString);
                              }

                              $revisione->setNombreArchivo($file->getClientOriginalName());
                              $fileName = md5(uniqid()).'.'.$file->guessExtension();
                              $cvDir = $this->container->getparameter('kernel.root_dir').'/../web/uploads/final';
                              $file->move($cvDir, $fileName);
                              $revisione->setRutaArchivo($fileName);
                            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($revisione);
            $em->flush();

            return $this->redirectToRoute('revisiones_showRevision', array('idRevision' => $revisione->getIdrevision()));
        }

        return $this->render('revisiones/uploadRevision.html.twig', array(
            'revisione' => $revisione,
            'var'=> $var,

            'form' => $form->createView(),
        ));
    }

    public function envioCorreoAction()
    {


  /*
        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('a.j.orozco038@gmail.com')
            ->setTo('a.j.orozco038@gmail.com')
            ->setBody(
                $this->renderView(
                    'revisiones/envioCorreo.html.twig',
                    array('name' => "adder",)
                )
            )
        ;
        $this->get('mailer')
        ->send($message);
*/




        $message = (new \Swift_Message('Hello Email'))
                ->setFrom('grupo15carrera@gmial.com')
                ->setTo('a.j.orozco038@gmail.com')
                ->setBody(
                    $this->renderView(
                      'revisiones/envioCorreo.html.twig',
                      array('name' => "adder",)
                )
              );
        $this->get('mailer')
        ->send($message);


        return $this->render('revisiones/envioCorreo.html.twig', array(
          'name' => "no",

        ));


    }


    /**
     * Deletes a revisione entity.
     *
     */
    public function deleteAction(Request $request, Revisiones $revisione)
    {
        $form = $this->createDeleteForm($revisione);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($revisione);
            $em->flush();
        }

        return $this->redirectToRoute('revisiones_index');
    }

    /**
     * Creates a form to delete a revisione entity.
     *
     * @param Revisiones $revisione The revisione entity
     *
     * @return \Symfony\Component\Form\Form The form
     */

    private function createDeleteForm(Revisiones $revisione)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('revisiones_delete', array('idRevision' => $revisione->getIdrevision())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
