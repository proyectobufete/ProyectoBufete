<?php

namespace BufeteBundle\Services;

class Registrocunoc {

    public function consultar($carne)
    {
      header('Content-Type: text/html; charset=UTF-8');
      include ("lib/libryeser.php");
      include ("lib/parsexml.php");
      require_once("lib/nusoap/nusoap.php");

        if(isset($carne) && is_numeric($carne))
        {
            $ciclo=date('Y');
            $unidad=12;
            $acceso="<DEPENDENCIA>UA12</DEPENDENCIA><LOGIN>20040750</LOGIN><PWD>d7476d19</PWD>";
            $xml03="<VERIFICAR_NUEVO>".$acceso."<CARNET>".$carne."</CARNET>
                    <UNIDAD_ACADEMICA>".$unidad."</UNIDAD_ACADEMICA><CICLO>".$ciclo."</CICLO></VERIFICAR_NUEVO>";

            $url="http://rye.usac.edu.gt/WS/verificadatosRyEv01.php?wsdl";

            $res03 = verificar_con_RYE("VerificaNuevos", "xml_verificaNuevos", $xml03,$url);
            $xml02="<VERIFICAR_CARRERAS>".$acceso."<CARNET>".$carne."</CARNET>
                    <UNIDAD_ACADEMICA>12</UNIDAD_ACADEMICA><CICLO>".$ciclo."</CICLO></VERIFICAR_CARRERAS>";

            $url="http://rye.usac.edu.gt/WS/verificadatosRyEv01.php?wsdl";

            $res02 = verificar_con_RYE("VerificaCarreras", "xml_verificaCarreras", $xml02,$url);
            $res03 = "<?xml version='1.0' encoding='UTF-8'?>".$res03;
            $res02 = "<?xml version='1.0' encoding='UTF-8'?>".$res02;

            $datos = new \SimpleXMLElement($res03);
            $datos1 = new \SimpleXMLElement($res02);

          }
        return $datos;
    }

}
?>
