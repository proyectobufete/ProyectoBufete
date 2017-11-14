<?php

namespace BufeteBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BufeteBundle\Entity\AvisoNotificacion;
use BufeteBundle\Form\AvisoNotificacionType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;

use PDO;
class AvisosController extends Controller
{
    private $session;

    public function __construct()
    {
        $this->session=new Session();
    }


    public function getDemandantes()
    {
        return $this->get('demandante_dao');
    }

    public function eliminarAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $avi_repo = $em->getRepository("BufeteBundle:AvisoNotificacion");
        $avi = $avi_repo->find($id);

        $em->remove($avi);
        $em->flush();

        return $this->redirectToRoute('avisos_index');
    }

     public function buscAction(Request $request)
    {
        $bandera=0;
        $em = $this->getDoctrine()->getEntityManager();
        $db = $em->getConnection();

        $query = "Select id_caso, no_caso, fecha_caso, d.id_demandante, d.nombre_demandante, c.nombre_demandado, e.id_estudiante,
                           p.nombre_persona, t.tipo from casos as c inner join demandantes as d on c.id_demandante=d.id_demandante inner join estudiantes as e on (c.id_estudiante=e.id_estudiante) inner join personas as p on (e.id_persona=p.id_persona) inner join tipocaso as t on c.id_tipo=t.id_tipo";
        $stmt = $db->prepare($query);
        $params = array();
        $stmt->execute($params);

        $buf = $stmt->fetchAll();


        $pdf="prueba.pdf";

        $avi = new AvisoNotificacion();
        $form = $this->createForm(AvisoNotificacionType::class, $avi);

        $form->handleRequest($request);

        if($form->isSubmitted($request))
        {
            $bandera=1;
            if($form->isValid())
            {
                $caso = $form['descripcion']->getData();
                $file = $form['pdf']->getData();
                $ext = $file->guessExtension();
                $file_name = time().".".$ext;
                $file->move("uploads", $file_name);

                $em = $this->getDoctrine()->getEntityManager();
                $db = $em->getConnection();
                $query = "Select id_caso, no_caso, fecha_caso, d.id_demandante, d.nombre_demandante, c.nombre_demandado, e.id_estudiante,
                                   p.nombre_persona, t.tipo from casos as c inner join demandantes as d on c.id_demandante=d.id_demandante inner join estudiantes as e on (c.id_estudiante=e.id_estudiante) inner join personas as p on (e.id_persona=p.id_persona) inner join tipocaso as t on c.id_tipo=t.id_tipo where c.no_caso = '$caso' ";

                $stmt = $db->prepare($query);
                $params = array();
                $stmt->execute($params);
                $buf = $stmt->fetchAll();
                return $this->render('BufeteBundle:Avisos:add.html.twig', array(
                "av"=>$buf, "form"=>$form->createView(),"pdf"=>$file_name, "flag"=>$bandera, "id"=>$caso
            ));
            }
            else
            {
                $status = "La Entrada no se ha creado porque el formulario no es válido";
            }
            //$this->session->getFlashBag()->add("status", $status);
            return $this->redirectToRoute("avisos_index");
        }
            return $this->render("BufeteBundle:Avisos:add.html.twig", array(
               "form" => $form->createView(), "av"=>$buf, "pdf"=>$pdf, "flag"=>$bandera
            ));
    }

    public function indexAction(Request $request)
    {
        $bandera = 1;
        $em = $this->getDoctrine()->getEntityManager();
        $db = $em->getConnection();

        $query = "SELECT id_aviso, c.no_caso, p.nombre_persona, d.nombre_demandante, fecha_visita, hora_visita, vista, descripcion, t.tipo, c.nombre_demandado from aviso_notificacion as a inner join casos as c on (a.id_caso=c.id_caso) inner join tipocaso as t on (c.id_tipo=t.id_tipo) inner join demandantes as d on a.id_demandante=d.id_demandante inner join estudiantes as e on (a.id_estudiante=e.id_estudiante) inner join personas as p on (e.id_persona=p.id_persona) ORDER BY id_aviso DESC LIMIT 5";
        $stmt = $db->prepare($query);
        $params = array();
        $stmt->execute($params);

        $buf = $stmt->fetchAll(PDO::FETCH_OBJ);

        $query1 = "SELECT c.no_caso from aviso_notificacion as a inner join casos as c on (a.id_caso=c.id_caso)";
        $stmt1 = $db->prepare($query1);
        $params1 = array();
        $stmt1->execute($params1);

        $buf1 = $stmt1->fetchAll(PDO::FETCH_OBJ);


        if ($request->getMethod()=="POST")
            {
                $caso=$request->get("res");
                $em = $this->getDoctrine()->getEntityManager();
                $db = $em->getConnection();
                $query = "SELECT id_aviso, c.no_caso, p.nombre_persona, d.nombre_demandante, fecha_visita, hora_visita, vista, descripcion, t.tipo, c.nombre_demandado from aviso_notificacion as a inner join casos as c on (a.id_caso=c.id_caso) inner join tipocaso as t on (c.id_tipo=t.id_tipo) inner join demandantes as d on a.id_demandante=d.id_demandante inner join estudiantes as e on (a.id_estudiante=e.id_estudiante) inner join personas as p on (e.id_persona=p.id_persona) where c.no_caso = '$caso'";

                $stmt = $db->prepare($query);
                $params = array();
                $stmt->execute($params);
                $bufe = $stmt->fetchAll(PDO::FETCH_OBJ);
                return $this->render('BufeteBundle:Avisos:index.html.twig', array(
                "av"=>$bufe, "flag"=>$bandera, "avisos"=>$bufe
              ));
        }

        return $this->render('BufeteBundle:Avisos:index.html.twig', array(
            "avisos"=>$buf, "flag"=>$bandera, "av"=>$buf1
        ));
    }

     public function addAction($idcaso, $idest, $iddem, $pdf)
    {
        $bandera = 0;
        $notif = new AvisoNotificacion();
        $em = $this->getDoctrine()->getEntityManager();
	       $caso_repo=$em->getRepository("BufeteBundle:Casos");
	        $caso=$caso_repo->find($idcaso);
	         $estu_repo=$em->getRepository("BufeteBundle:Estudiantes");
        $estu=$estu_repo->find($idest);
        $dema_repo=$em->getRepository("BufeteBundle:Demandantes");
        $dema=$dema_repo->find($iddem);
        $per_repo=$em->getRepository("BufeteBundle:Personas");
        $per=$per_repo->find(1);

        $notif->setIdCaso($caso);
	       $notif->setIdEstudiante($estu);
        $notif->setIdDemandante($dema);
        $notif->setIdPersona($per);
        $notif->setFechaVisita(new \DateTime("now"));
	       $notif->setHoraVisita(new \DateTime("now"));
        $notif->setVista(0);
	       $notif->setDescripcion($pdf);

        $em->persist($notif);
	$flush = $em->flush();

	if($flush==null)
        {
	$status = "El aviso se ha creado correctamente !!";
	}
        else{
		$status ="Error al añadir el Aviso!!";
	    }

        //$this->session->getFlashBag()->add("status", $status);

        $db = $em->getConnection();
        $query = "SELECT id_aviso, c.no_caso, p.nombre_persona, d.nombre_demandante, fecha_visita, hora_visita, vista, descripcion, t.tipo, c.nombre_demandado from aviso_notificacion as a inner join casos as c on (a.id_caso=c.id_caso) inner join tipocaso as t on (c.id_tipo=t.id_tipo) inner join demandantes as d on a.id_demandante=d.id_demandante inner join estudiantes as e on (a.id_estudiante=e.id_estudiante) inner join personas as p on (e.id_persona=p.id_persona) where c.id_caso=$idcaso";
        $stmt = $db->prepare($query);
        $params = array();
        $stmt->execute($params);
        $buf = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $this->render('BufeteBundle:Avisos:index.html.twig', array(
            "avisos"=>$buf, "flag"=>$bandera
        ));
    }

    public function pdfAction(Request $request)
    {

      $snappy = $this->get("knp_snappy.pdf");


        $em = $this->getDoctrine()->getEntityManager();
        $db = $em->getConnection();

        $query = "SELECT id_aviso, c.no_caso, p.nombre_persona, d.nombre_demandante, fecha_visita, hora_visita, vista, descripcion, t.tipo, c.nombre_demandado from aviso_notificacion as a inner join casos as c on (a.id_caso=c.id_caso) inner join tipocaso as t on (c.id_tipo=t.id_tipo) inner join demandantes as d on a.id_demandante=d.id_demandante inner join estudiantes as e on (a.id_estudiante=e.id_estudiante) inner join personas as p on (e.id_persona=p.id_persona) ORDER BY id_aviso DESC LIMIT 5";
        $stmt = $db->prepare($query);
        $params = array();
        $stmt->execute($params);

        $buf = $stmt->fetchAll(PDO::FETCH_OBJ);


         $html = $this->renderView("BufeteBundle:Avisos:reportes.html.twig", array(
           "avisos" =>$buf

        ));
        $filename = "custom_pdf_from_twing";

    return new Response(
            $snappy->getOutputFromHtml($html,
                    array('lowquality' => false,
            'print-media-type' => true,
            'encoding' => 'utf-8',
            'page-size' => 'Letter',
            'outline-depth' => 8,
                        'footer-font-name' =>'Times New Roman',
                        'footer-center'=>'text',
                        'footer-font-size'=>20,
            'orientation' => 'Portrait',
            'title'=> 'Reportes',
            'user-style-sheet'=> 'web/asst/css/style.css',
            'header-right'=>'Pag. [page] de [toPage]',
            'header-font-size'=>7,

                )),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$filename.'.pdf"'
                )
            );
    }

      public function indexBuscarAction(Request $request)
    {
        $bandera=2;
        $flag=1;

        $em = $this->getDoctrine()->getEntityManager();
        $db = $em->getConnection();

        $query = "Select carne_estudiante from aviso_notificacion as a inner join casos as c on (a.id_caso=c.id_caso) inner join estudiantes as e on (c.id_estudiante=e.id_estudiante)";
        $stmt = $db->prepare($query);
        $params = array();
        $stmt->execute($params);

        $buf = $stmt->fetchAll(PDO::FETCH_OBJ);


        if ($request->getMethod()=="POST") {

             $id = $request->get('bus');
                $em = $this->getDoctrine()->getEntityManager();
                $db = $em->getConnection();
                $query = "SELECT id_aviso, c.no_caso, p.nombre_persona, e.carne_estudiante, d.nombre_demandante, fecha_visita, hora_visita, vista, descripcion, t.tipo, c.nombre_demandado from aviso_notificacion as a inner join casos as c on (a.id_caso=c.id_caso) inner join tipocaso as t on (c.id_tipo=t.id_tipo) inner join demandantes as d on a.id_demandante=d.id_demandante inner join estudiantes as e on (a.id_estudiante=e.id_estudiante) inner join personas as p on (e.id_persona=p.id_persona) where e.carne_estudiante='$id' ORDER BY id_aviso DESC";
                $stmt = $db->prepare($query);
                $params = array();
                $stmt->execute($params);
                $buf = $stmt->fetchAll(PDO::FETCH_OBJ);
                return $this->render('BufeteBundle:Avisos:index.html.twig', array(
                    "avisos"=>$buf, "flag"=>$bandera
        ));
        }

            return $this->render("BufeteBundle:Avisos:form.html.twig", array(
                "av" => $buf, "bandera"=>$flag
            ));
    }

    public function pdfestuAction($estu)
    {

       $snappy = $this->get("knp_snappy.pdf");

         $em = $this->getDoctrine()->getEntityManager();
         $db = $em->getConnection();
         $query = "SELECT id_aviso, c.no_caso, p.nombre_persona, e.carne_estudiante, d.nombre_demandante, fecha_visita, hora_visita, vista, descripcion, t.tipo, c.nombre_demandado from aviso_notificacion as a inner join casos as c on (a.id_caso=c.id_caso) inner join tipocaso as t on (c.id_tipo=t.id_tipo) inner join demandantes as d on a.id_demandante=d.id_demandante inner join estudiantes as e on (a.id_estudiante=e.id_estudiante) inner join personas as p on (e.id_persona = p.id_persona) where e.carne_estudiante='$estu'";
         $stmt = $db->prepare($query);
         $params = array();
         $stmt->execute($params);
         $buf = $stmt->fetchAll(PDO::FETCH_OBJ);

         $html = $this->renderView("BufeteBundle:Avisos:reportes.html.twig", array(
           "avisos" =>$buf

        ));


        $filename = "custom_pdf_from_twing";

    return new Response(
            $snappy->getOutputFromHtml($html,
                    array('lowquality' => false,
                'print-media-type' => true,
                    'encoding' => 'utf-8',
                    'page-size' => 'Letter',
                    'outline-depth' => 8,
                    'orientation' => 'Portrait',
                    'title' => 'Reportes',
                    'user-style-sheet' => 'css/bootstrap.css',
                    'header-right' => 'Pag. [page] de [toPage]',
                    'header-font-size' => 7,
                )), 200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$filename.'.pdf"'
            )
            );

    }


    public function indexCasoAction(Request $request)
    {
        $bandera=3;
        $flag=2;

        $em = $this->getDoctrine()->getEntityManager();
        $db = $em->getConnection();

        $query = "Select c.no_caso from aviso_notificacion as a inner join casos as c on a.id_caso=c.id_caso";
        $stmt = $db->prepare($query);
        $params = array();
        $stmt->execute($params);

        $buf = $stmt->fetchAll(PDO::FETCH_OBJ);


        if ($request->getMethod()=="POST") {

             $id = $request->get('res');
                $em = $this->getDoctrine()->getEntityManager();
                $db = $em->getConnection();
                $query = "SELECT id_aviso, c.no_caso, p.nombre_persona, d.nombre_demandante, fecha_visita, hora_visita, vista, descripcion, t.tipo, c.nombre_demandado from aviso_notificacion as a inner join casos as c on (a.id_caso=c.id_caso) inner join tipocaso as t on (c.id_tipo=t.id_tipo) inner join demandantes as d on a.id_demandante=d.id_demandante inner join estudiantes as e on (a.id_estudiante=e.id_estudiante) inner join personas as p on (e.id_persona=p.id_persona) where c.no_caso='$id' ORDER BY id_aviso DESC";
                $stmt = $db->prepare($query);
                $params = array();
                $stmt->execute($params);
                $buf = $stmt->fetchAll(PDO::FETCH_OBJ);
                return $this->render('BufeteBundle:Avisos:index.html.twig', array(
                    "avisos"=>$buf, "flag"=>$bandera
        ));
        }

            return $this->render("BufeteBundle:Avisos:form.html.twig", array(
                "av" => $buf, "bandera"=>$flag
            ));
    }

    public function pdfcasoAction($caso)
    {

       $snappy = $this->get("knp_snappy.pdf");

         $em = $this->getDoctrine()->getEntityManager();
         $db = $em->getConnection();
         $query = "SELECT id_aviso, c.no_caso, p.nombre_persona, d.nombre_demandante, fecha_visita, hora_visita, vista, t.tipo, c.nombre_demandado from aviso_notificacion as a inner join casos as c on (a.id_caso=c.id_caso) inner join tipocaso as t on (c.id_tipo=t.id_tipo) inner join demandantes as d on a.id_demandante=d.id_demandante inner join estudiantes as e on (a.id_estudiante=e.id_estudiante) inner join personas as p on (e.id_persona=p.id_persona) where c.no_caso='$caso'";
         $stmt = $db->prepare($query);
         $params = array();
         $stmt->execute($params);
         $buf = $stmt->fetchAll(PDO::FETCH_OBJ);

         $html = $this->renderView("BufeteBundle:Avisos:reportes.html.twig", array(
           "avisos" =>$buf

        ));


        $filename = "custom_pdf_from_twing";

    return new Response(
            $snappy->getOutputFromHtml($html,
                    array('lowquality' => false,
                'print-media-type' => true,
                    'encoding' => 'utf-8',
                    'page-size' => 'Letter',
                    'outline-depth' => 8,
                    'orientation' => 'Portrait',
                    'title' => 'Reportes',
                    'user-style-sheet' => 'css/bootstrap.css',
                    'header-right' => 'Pag. [page] de [toPage]',
                    'header-font-size' => 7,
                )),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$filename.'.pdf"'
            )
            );

    }

    public function indexFechaAction(Request $request)
    {
        $bandera=4;
        $flag=3;


        if ($request->getMethod()=="POST") {

             $fec1 = $request->get('fec1');
             $fec2 = $request->get('fec2');
                $em = $this->getDoctrine()->getEntityManager();
                $db = $em->getConnection();
                $query = "SELECT id_aviso, c.no_caso, p.nombre_persona, d.nombre_demandante, fecha_visita, hora_visita, vista, descripcion, t.tipo, c.nombre_demandado from aviso_notificacion as a inner join casos as c on (a.id_caso=c.id_caso) inner join tipocaso as t on (c.id_tipo=t.id_tipo) inner join demandantes as d on a.id_demandante=d.id_demandante inner join estudiantes as e on (a.id_estudiante=e.id_estudiante) inner join personas as p on (e.id_persona=p.id_persona) where CAST(fecha_visita AS DATE) between '$fec1' and '$fec2' ORDER BY id_aviso DESC";
                $stmt = $db->prepare($query);
                $params = array();
                $stmt->execute($params);
                $buf = $stmt->fetchAll(PDO::FETCH_OBJ);
                return $this->render('BufeteBundle:Avisos:index.html.twig', array(
                    "avisos"=>$buf, "flag"=>$bandera, "fec1"=>$fec1, "fec2"=>$fec2
        ));
        }

            return $this->render("BufeteBundle:Avisos:form.html.twig", array(
                "bandera"=>$flag
            ));
    }

    public function pdfecAction($fec1, $fec2)
    {

       $snappy = $this->get("knp_snappy.pdf");

         $em = $this->getDoctrine()->getEntityManager();
         $db = $em->getConnection();
         $query = "SELECT id_aviso, c.no_caso, p.nombre_persona, d.nombre_demandante, fecha_visita, hora_visita, vista, descripcion, t.tipo, c.nombre_demandado from aviso_notificacion as a inner join casos as c on (a.id_caso=c.id_caso) inner join tipocaso as t on (c.id_tipo=t.id_tipo) inner join demandantes as d on a.id_demandante=d.id_demandante inner join estudiantes as e on (a.id_estudiante=e.id_estudiante) inner join personas as p on (e.id_persona=p.id_persona) where CAST(fecha_visita AS DATE) between '$fec1' and '$fec2' ORDER BY id_aviso DESC";
         $stmt = $db->prepare($query);
         $params = array();
         $stmt->execute($params);
         $buf = $stmt->fetchAll(PDO::FETCH_OBJ);

         $html = $this->renderView("BufeteBundle:Avisos:reportes.html.twig", array(
           "avisos" =>$buf

        ));


        $filename = "custom_pdf_from_twing";

    return new Response(
            $snappy->getOutputFromHtml($html,
                    array('lowquality' => false,
                'print-media-type' => true,
                    'encoding' => 'utf-8',
                    'page-size' => 'Letter',
                    'outline-depth' => 8,
                    'orientation' => 'Portrait',
                    'title' => 'Reportes',
                    'user-style-sheet' => 'css/bootstrap.css',
                    'header-right' => 'Pag. [page] de [toPage]',
                    'header-font-size' => 7,
                )),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$filename.'.pdf"'
            )
            );

    }

    public function indexDemandanteAction(Request $request)
    {
        $bandera=5;
        $flag=4;

        $em = $this->getDoctrine()->getEntityManager();
        $db = $em->getConnection();

        $query = "SELECT id_aviso, c.no_caso, p.nombre_persona, d.nombre_demandante, fecha_visita, hora_visita, vista, descripcion, t.tipo, c.nombre_demandado from aviso_notificacion as a inner join casos as c on (a.id_caso=c.id_caso) inner join tipocaso as t on (c.id_tipo=t.id_tipo) inner join demandantes as d on a.id_demandante=d.id_demandante inner join estudiantes as e on (a.id_estudiante=e.id_estudiante) inner join personas as p on (e.id_persona=p.id_persona)";
        $stmt = $db->prepare($query);
        $params = array();
        $stmt->execute($params);

        $buf = $stmt->fetchAll(PDO::FETCH_OBJ);


        if ($request->getMethod()=="POST") {

             $id = $request->get('res');
                $em = $this->getDoctrine()->getEntityManager();
                $db = $em->getConnection();
                $query = "SELECT id_aviso, c.no_caso, p.nombre_persona, d.nombre_demandante, fecha_visita, hora_visita, vista, descripcion, t.tipo, c.nombre_demandado from aviso_notificacion as a inner join casos as c on (a.id_caso=c.id_caso) inner join tipocaso as t on (c.id_tipo=t.id_tipo) inner join demandantes as d on a.id_demandante=d.id_demandante inner join estudiantes as e on (a.id_estudiante=e.id_estudiante) inner join personas as p on (e.id_persona=p.id_persona) where d.nombre_demandante='$id' ORDER BY id_aviso DESC";
                $stmt = $db->prepare($query);
                $params = array();
                $stmt->execute($params);
                $buf = $stmt->fetchAll(PDO::FETCH_OBJ);
                return $this->render('BufeteBundle:Avisos:index.html.twig', array(
                    "avisos"=>$buf, "flag"=>$bandera
        ));
        }

            return $this->render("BufeteBundle:Avisos:form.html.twig", array(
                "av" => $buf, "bandera"=>$flag
            ));
    }

    public function pdfdemaAction($dem)
    {

       $snappy = $this->get("knp_snappy.pdf");

         $em = $this->getDoctrine()->getEntityManager();
         $db = $em->getConnection();
         $query = "SELECT id_aviso, c.no_caso, p.nombre_persona, d.nombre_demandante, fecha_visita, hora_visita, vista, t.tipo, c.nombre_demandado from aviso_notificacion as a inner join casos as c on (a.id_caso=c.id_caso) inner join tipocaso as t on (c.id_tipo=t.id_tipo) inner join demandantes as d on a.id_demandante=d.id_demandante inner join estudiantes as e on (a.id_estudiante=e.id_estudiante) inner join personas as p on (e.id_persona=p.id_persona) where d.nombre_demandante='$dem'";
         $stmt = $db->prepare($query);
         $params = array();
         $stmt->execute($params);
         $buf = $stmt->fetchAll(PDO::FETCH_OBJ);

         $html = $this->renderView("BufeteBundle:Avisos:reportes.html.twig", array(
           "avisos" =>$buf

        ));


        $filename = "custom_pdf_from_twing";

    return new Response(
            $snappy->getOutputFromHtml($html,
                    array('lowquality' => false,
                'print-media-type' => true,
                    'encoding' => 'utf-8',
                    'page-size' => 'Letter',
                    'outline-depth' => 8,
                    'orientation' => 'Portrait',
                    'title' => 'Reportes',
                    'user-style-sheet' => 'css/bootstrap.css',
                    'header-right' => 'Pag. [page] de [toPage]',
                    'header-font-size' => 7,
                )),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$filename.'.pdf"'
            )
            );

    }

    public function indexDemandadoAction(Request $request)
    {
        $bandera=6;
        $flag=5;

        $em = $this->getDoctrine()->getEntityManager();
        $db = $em->getConnection();

        $query = "Select nombre_demandado from aviso_notificacion as a inner join casos as c on a.id_caso=c.id_caso";
        $stmt = $db->prepare($query);
        $params = array();
        $stmt->execute($params);

        $buf = $stmt->fetchAll(PDO::FETCH_OBJ);


        if ($request->getMethod()=="POST") {

             $id = $request->get('res');
                $em = $this->getDoctrine()->getEntityManager();
                $db = $em->getConnection();
                $query = "SELECT id_aviso, c.no_caso, p.nombre_persona, d.nombre_demandante, fecha_visita, hora_visita, vista, descripcion, t.tipo, c.nombre_demandado from aviso_notificacion as a inner join casos as c on (a.id_caso=c.id_caso) inner join tipocaso as t on (c.id_tipo=t.id_tipo) inner join demandantes as d on a.id_demandante=d.id_demandante inner join estudiantes as e on (a.id_estudiante=e.id_estudiante) inner join personas as p on (e.id_persona=p.id_persona) where c.nombre_demandado='$id' ORDER BY id_aviso DESC";
                $stmt = $db->prepare($query);
                $params = array();
                $stmt->execute($params);
                $buf = $stmt->fetchAll(PDO::FETCH_OBJ);
                return $this->render('BufeteBundle:Avisos:index.html.twig', array(
                    "avisos"=>$buf, "flag"=>$bandera
        ));
        }

            return $this->render("BufeteBundle:Avisos:form.html.twig", array(
                "av" => $buf, "bandera"=>$flag
            ));
    }

    public function pdfdeAction($demo)
    {

       $snappy = $this->get("knp_snappy.pdf");

         $em = $this->getDoctrine()->getEntityManager();
         $db = $em->getConnection();
         $query = "SELECT id_aviso, c.no_caso, p.nombre_persona, d.nombre_demandante, fecha_visita, hora_visita, vista, t.tipo, c.nombre_demandado from aviso_notificacion as a inner join casos as c on (a.id_caso=c.id_caso) inner join tipocaso as t on (c.id_tipo=t.id_tipo) inner join demandantes as d on a.id_demandante=d.id_demandante inner join estudiantes as e on (a.id_estudiante=e.id_estudiante) inner join personas as p on (e.id_persona=p.id_persona) where c.nombre_demandado='$demo'";
         $stmt = $db->prepare($query);
         $params = array();
         $stmt->execute($params);
         $buf = $stmt->fetchAll(PDO::FETCH_OBJ);

         $html = $this->renderView("BufeteBundle:Avisos:reportes.html.twig", array(
           "avisos" =>$buf

        ));


        $filename = "custom_pdf_from_twing";

    return new Response(
            $snappy->getOutputFromHtml($html,
                    array('lowquality' => false,
                'print-media-type' => true,
                    'encoding' => 'utf-8',
                    'page-size' => 'Letter',
                    'outline-depth' => 8,
                    'orientation' => 'Portrait',
                    'title' => 'Reportes',
                    'user-style-sheet' => 'css/bootstrap.css',
                    'header-right' => 'Pag. [page] de [toPage]',
                    'header-font-size' => 7,
                )),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$filename.'.pdf"'
            )
            );

    }


    public function obtenerEstuAction($id)
        {
                    $em = $this->getDoctrine()->getEntityManager();
                    $db = $em->getConnection();
                    $query = "SELECT p.Nombre_Persona as nombre, p.dpi_persona,p.Telefono_Persona as tel,p.tel2_persona as tel2,e.Carne_estudiante as carne,p.Direccion_Persona,p.dir2_persona as dir2,p.Email_Persona, p.foto
    				from personas as p
    				inner join estudiantes as e
    				on (p.Id_Persona=e.Id_Persona) WHERE e.Id_Estudiante='$id'";
                    $stmt = $db->prepare($query);
                    $params = array();
                    $stmt->execute($params);
                    $buf = $stmt->fetchAll(PDO::FETCH_OBJ);
                    return new JsonResponse($buf);
        }

            public function valEstudianteAction($carne)
        {
                    $em = $this->getDoctrine()->getEntityManager();
                    $db = $em->getConnection();
                    $query = ("SELECT e.Id_Estudiante,e.Carne_estudiante as Carne_Pasante,p.Nombre_Persona,p.Usuario_Persona,p.pass_Persona as Contrasena_Persona FROM estudiantes as e INNER JOIN personas as p ON e.Id_Persona=p.Id_Persona WHERE e.Carne_estudiante='$carne'");
                    $stmt = $db->prepare($query);
                    $params = array();
                    $stmt->execute($params);
                    $buf = $stmt->fetchAll(PDO::FETCH_OBJ);
                    return new JsonResponse($buf);
        }

            public function casosEstudianteAction($id_estudiante)
        {
                    $em = $this->getDoctrine()->getEntityManager();
                    $db = $em->getConnection();
                    $query=("SELECT No_Caso as Caso,tipo as Tipo,Nombre_Demandante as Demandante, Nombre_Demandado as Demandado,Tribunal, p.Nombre_Persona as Asesor
    				FROM `casos` as c
    				inner join `demandantes` AS d1 ON (d1.id_Demandante=c.id_demandante)
    				inner join `tipocaso` AS tip ON(tip.id_tipo=c.id_Tipo)
    				inner join `tribunales` AS trib On(trib.id_tribunal=c.id_tribunal)
    				inner join `personas` AS p On(p.id_persona=c.id_persona)
    				WHERE id_estudiante='$id_estudiante'");

                    $stmt = $db->prepare($query);
                    $params = array();
                    $stmt->execute($params);
                    $buf = $stmt->fetchAll(PDO::FETCH_OBJ);
                    return new JsonResponse($buf);
        }

        public function notifEstudianteAction($id_estudiante)
        {
                    $em = $this->getDoctrine()->getEntityManager();
                    $db = $em->getConnection();
                    $query=("SELECT id_aviso, Date(fecha_visita) as Fecha,c.no_caso as Codigo_Caso, p.nombre_persona as Estudiante, d.nombre_demandante as Demandante, descripcion as Descripcion, t.tipo as Tipo_Caso,a.vista as Vista
    				from aviso_notificacion as a
    				inner join casos as c on (a.id_caso=c.id_caso)
    				inner join tipocaso as t on (c.Id_Tipo=t.Id_Tipo)
    				inner join demandantes as d on a.id_demandante=d.id_demandante
    				inner join estudiantes as e on (a.id_estudiante=e.id_estudiante)
    				inner join personas as p on (e.id_persona=p.id_persona)
    				WHERE e.id_estudiante='$id_estudiante' ORDER BY fecha_visita DESC");

                    $stmt = $db->prepare($query);
                    $params = array();
                    $stmt->execute($params);
                    $buf = $stmt->fetchAll(PDO::FETCH_OBJ);
                    return new JsonResponse($buf);
        }

        public function notifVistasEstudianteAction($id_estudiante)
        {
                    $em = $this->getDoctrine()->getEntityManager();
                    $db = $em->getConnection();
                    $query=("SELECT id_aviso, Date(fecha_visita) as Fecha,c.no_caso as Codigo_Caso, p.nombre_persona as Estudiante, d.nombre_demandante as Demandante, descripcion as Descripcion, t.tipo as Tipo_Caso from aviso_notificacion as a
    				inner join casos as c on (a.id_caso=c.id_caso) inner join tipocaso as t on (c.Id_Tipo=t.Id_Tipo)
    				inner join demandantes as d on a.id_demandante=d.id_demandante inner join estudiantes as e on (a.id_estudiante=e.id_estudiante)
    				inner join personas as p on (e.id_persona=p.id_persona)
    				  WHERE e.id_estudiante='$id_estudiante' and a.vista=1 ORDER BY fecha_visita DESC");

                    $stmt = $db->prepare($query);
                    $params = array();
                    $stmt->execute($params);
                    $buf = $stmt->fetchAll(PDO::FETCH_OBJ);
                    return new JsonResponse($buf);
        }

        public function notifNoVistasEstudianteAction($id_estudiante)
        {
                    $em = $this->getDoctrine()->getEntityManager();
                    $db = $em->getConnection();
                   $query=("SELECT id_aviso, Date(fecha_visita) as Fecha,c.no_caso as Codigo_Caso, p.nombre_persona as Estudiante, d.nombre_demandante as Demandante, descripcion as Descripcion, t.tipo as Tipo_Caso
    				from aviso_notificacion as a inner join casos as c on (a.id_caso=c.id_caso) inner join tipocaso as t on (c.Id_Tipo=t.Id_Tipo) inner join demandantes as d on a.id_demandante=d.id_demandante inner join estudiantes as e on (a.id_estudiante=e.id_estudiante) inner join personas as p on (e.id_persona=p.id_persona)
    				 WHERE e.id_estudiante='$id_estudiante' and a.vista=0 ORDER BY fecha_visita DESC");

                    $stmt = $db->prepare($query);
                    $params = array();
                    $stmt->execute($params);
                    $buf = $stmt->fetchAll(PDO::FETCH_OBJ);
                    return new JsonResponse($buf);
        }

         public function detalleNotifEstudianteAction($id)
        {
                    $em = $this->getDoctrine()->getEntityManager();
                    $db = $em->getConnection();
                   $query=("SELECT id_aviso, Date(fecha_visita) as Fecha,Time(fecha_visita) as Hora,c.no_caso as Codigo_Caso, p.nombre_persona as Estudiante,d.nombre_demandante as Demandante,
    				ase.nombre_persona as Asesor,descripcion as Descripcion, t.tipo as Tipo_Caso

    				from aviso_notificacion as a
    				inner join casos as c on (a.id_caso=c.id_caso)
    				inner join tipocaso as t on (c.Id_Tipo=t.Id_Tipo)
    				inner join demandantes as d on a.id_demandante=d.id_demandante
    				inner join estudiantes as e on (a.id_estudiante=e.id_estudiante)
    				inner join personas as p on (e.id_persona=p.id_persona)
    				inner join `personas` as ase on(ase.Id_Persona=c.Id_Persona) WHERE  id_aviso='$id'");

                    $stmt = $db->prepare($query);
                    $params = array();
                    $stmt->execute($params);
                    $buf = $stmt->fetchAll(PDO::FETCH_OBJ);
                    return new JsonResponse($buf);
        }

        public function detalleCasoAction($id)
        {
                    $em = $this->getDoctrine()->getEntityManager();
                    $db = $em->getConnection();
                   $query=("SELECT No_Caso as Caso,Fecha_Caso as Fecha,Nombre_Demandante as Demandante,Nombre_Demandado as Demandado,Tipo,Tribunal,ase.Nombre_Persona as Asesor,estu.Nombre_Persona as Estudiante,
    				Asunto,Estado_Caso as Estado
    				FROM `casos` as c INNER JOIN `demandantes` AS d1 ON (d1.Id_Demandante=c.Id_Demandante)
    				inner join `tipocaso` AS tip ON(tip.Id_Tipo=c.Id_Tipo)
    				inner join `tribunales` as trib on (trib.Id_Tribunal=c.Id_Tribunal)
    				inner join `personas` as ase on(ase.Id_Persona=c.Id_Persona)
    				inner join `personas` as estu on(estu.Id_Persona=c.Id_Estudiante)
    				inner join `tipoasunto`as asunto on(asunto.Id_TipoAsunto=c.Id_TipoAsunto) WHERE No_Caso='$id'");
                    $stmt = $db->prepare($query);
                    $params = array();
                    $stmt->execute($params);
                    $buf = $stmt->fetchAll(PDO::FETCH_OBJ);
                    return new JsonResponse($buf);
        }

          public function actualizaNotificacionAction($id)
        {
                    $em = $this->getDoctrine()->getEntityManager();
                    $ver_repo = $em->getRepository("BufeteBundle:AvisoNotificacion");
                    $ver = $ver_repo->find($id);
                    $ver->setVista(1);
                    $ver->setHoraVisita(new \DateTime("now"));
                    $em->persist($ver);
                    $flush = $em->flush();

                    if($flush==null)
                    {
                        $status = "Se ha visto la notificaci\F3n";
                    }

                    else
                    {
                        $status = "No se ha visto la notificaci\F3n";
                    }

                    return $this->redirectToRoute("avisos_index");
        }

        public function asesorAction($id)
        {
                    $em = $this->getDoctrine()->getEntityManager();
                    $db = $em->getConnection();
                    $query = "SELECT * FROM `personas` WHERE role='ROLE_ASESOR' AND id_persona='$id'";
                    $stmt = $db->prepare($query);
                    $params = array();
                    $stmt->execute($params);
                    $buf = $stmt->fetchAll(PDO::FETCH_OBJ);
                    return new JsonResponse($buf);
        }

         public function valAsesorAction($usuario)
        {
                    $em = $this->getDoctrine()->getEntityManager();
                    $db = $em->getConnection();
                    $query=("SELECT * FROM `personas` WHERE role='ROLE_ASESOR' AND usuario_persona='$usuario'");
                    $stmt = $db->prepare($query);
                    $params = array();
                    $stmt->execute($params);
                    $buf = $stmt->fetchAll(PDO::FETCH_OBJ);
                    return new JsonResponse($buf);
        }

         public function casosAsesorAction($id_persona)
        {
                    $em = $this->getDoctrine()->getEntityManager();
                    $db = $em->getConnection();
                    $query=("SELECT No_Caso as Caso,es.id_estudiante as Id_Estudiante,estu.nombre_persona as Estudiante,es.Carne_estudiante,tipo as Tipo,Nombre_Demandante as Demandante, Nombre_Demandado as Demandado,Tribunal
            FROM `casos` as c
            inner join `demandantes` AS d1 ON (d1.id_Demandante=c.id_demandante)
            inner join `tipocaso` AS tip ON(tip.id_tipo=c.id_Tipo)
            inner join `tribunales` AS trib On(trib.id_tribunal=c.id_tribunal)
            inner join `personas` AS estu on(estu.id_persona=c.id_estudiante)
            inner join `personas` AS ases on(ases.id_persona=c.id_persona)
            inner join `estudiantes` AS es on(es.id_estudiante=c.id_estudiante)

            WHERE ases.role='ROLE_ASESOR' AND  c.id_persona='$id_persona'");

                    $stmt = $db->prepare($query);
                    $params = array();
                    $stmt->execute($params);
                    $buf = $stmt->fetchAll(PDO::FETCH_OBJ);
                    return new JsonResponse($buf);
        }

        public function estusAsesorAction($id_persona)
        {
                    $em = $this->getDoctrine()->getEntityManager();
                    $db = $em->getConnection();
                    $query=("SELECT No_Caso as Caso,estu.Nombre_Persona as Estudiante,es.Id_Estudiante,es.Carne_estudiante as Carne
    				FROM `casos` as c
    				inner join `personas` AS estu on(estu.id_persona=c.id_estudiante)
    				inner join `personas` AS ases on(ases.id_persona=c.id_persona)
    				inner join `estudiantes` as es on(es.id_estudiante=c.id_estudiante)
    				WHERE ases.role='ROLE_ASESOR' AND  c.id_persona='$id_persona' Group by es.Carne_estudiante  ");

                    $stmt = $db->prepare($query);
                    $params = array();
                    $stmt->execute($params);
                    $buf = $stmt->fetchAll(PDO::FETCH_OBJ);
                    return new JsonResponse($buf);
        }

        public function notifAsesorAction($id_persona)
        {
                    $em = $this->getDoctrine()->getEntityManager();
                    $db = $em->getConnection();
                    $query=("SELECT id_aviso, Date(fecha_visita) as Fecha,c.no_caso as Codigo_Caso, p.nombre_persona as Estudiante,ases.Nombre_Persona as Asesor, d.nombre_demandante as Demandante, descripcion as Descripcion, t.tipo as Tipo_Caso,a.vista as Vista
    				from aviso_notificacion as a inner join casos as c on (a.id_caso=c.id_caso)
    				inner join tipocaso as t on (c.Id_Tipo=t.Id_Tipo)
    				inner join demandantes as d on a.id_demandante=d.id_demandante
    				inner join estudiantes as e on (a.id_estudiante=e.id_estudiante)
    				inner join personas as p on (e.id_persona=p.id_persona)
    				inner join personas as ases on(ases.Id_Persona=c.Id_Persona)
    				WHERE ases.role='ROLE_ASESOR' AND ases.id_persona='$id_persona'
    				ORDER BY fecha_visita DESC");

                    $stmt = $db->prepare($query);
                    $params = array();
                    $stmt->execute($params);
                    $buf = $stmt->fetchAll(PDO::FETCH_OBJ);
                    return new JsonResponse($buf);
        }


}
