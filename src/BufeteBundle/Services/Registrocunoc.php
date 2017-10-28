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

            if($res03 !== 0 && $res02!==0)
            {
              $res03 = "<?xml version='1.0' encoding='UTF-8'?>".$res03;
              $res02 = "<?xml version='1.0' encoding='UTF-8'?>".$res02;

              $datos = new \SimpleXMLElement($res03);
              if ($datos->STATUS != 6)
              {
                  $datos1 = new \SimpleXMLElement($res02);
                  if($datos1->REGISTRO->COD_CAR == '01')
                  {
                      $datos = new \SimpleXMLElement($res03);
                      if($datos->STATUS == 1 && $datos1->STATUS == 1 )
                      {
                        $datos = new \SimpleXMLElement($res03);
                      }
                      else if($datos->STATUS == 1 && $datos1->STATUS == 6)
                      {
                        $foo = 16;
                        $alert = "<ALERT>$foo</ALERT>";
                        $res = $alert;
                        $datos = new \SimpleXMLElement($res);
                      }
                  }
                  else
                  {
                    $foo = 10;
                    $alert = "<ALERT>$foo</ALERT>";
                    $res = $alert;
                    $datos = new \SimpleXMLElement($res);
                  }
              }
              else
              {
                $foo = 6;
                $alert = "<ALERT>$foo</ALERT>";
                $res = $alert;
                $res03 = $alert;
                $res02 = $alert;
                $datos = new \SimpleXMLElement($res03);
              }
            }
            else if($res03 === 0 && $res02 === 0)
            {
              $foo = 1;
              $alert = "<ALERT>$foo</ALERT>";
              $res = $alert;
              $res03 = $alert;
              $res02 = $alert;
              $datos = new \SimpleXMLElement($res03);
            }
        }

        return $datos;
    }

}
?>
