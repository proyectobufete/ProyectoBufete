<?php 

//funcion para ejecutar los web services de registro y estadistica campus central
function verificar_con_RYE($metodo, $xmlMetodo, $xml_verifica_datos, $url)
{
    global $v_msg_error; //almacena el error
    global $v_xml_retorno; //valor de retorno
    global $v_resultado_invoke; //resultado obtenido
 
    $v_msg_error = "";
    $v_xml_retorno = "";
    $v_resultado_invoke = 0; // 0 indica que ha ocurrido algun error, el error quedara en $v_msg_error
                             // 1 indica que hubo exito en la transaccion, el xml resultante esta en $v_xml_retorno
    $oSoapClient = new nusoap_client($url,'wsdl');
 
    if ($sError = $oSoapClient->getError()) {
        $v_msg_error = "No se pudo realizar la operacion [" . $sError . "]";
        $v_resultado_invoke = 0;
        return $v_msg_error;
    }
    $aParametros = array($xmlMetodo => $xml_verifica_datos);
    $aRespuesta = $oSoapClient->call($metodo, $aParametros);
     
    // Existe alguna falla en el servicio? 
    if ($oSoapClient->fault) { // Si
        $v_msg_error = 'No se pudo completar la operacion';
        $v_resultado_invoke = 0;
        return 0;
    } else { // No
        $sError = $oSoapClient->getError();
        // Hay algun error ?
        if ($sError) { // Si
            $v_msg_error = 'Error:' . $sError;
            $v_resultado_invoke = 0;
            return 0;
        } else {
            $v_resultado_invoke = 1;
            //$v_datos_retorno = xml2array(utf8_encode($aRespuesta)); //datos de retorno encodificacion estandar
            $v_datos_retorno = utf8_encode($aRespuesta); //datos de retorno encodificacion estandar
        }
    }
    return $v_datos_retorno;
} 





?>