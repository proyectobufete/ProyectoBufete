<?php

namespace BufeteBundle\Services;

class Autocont {

    public function obtener()
    {
          $cadena = "ABC123DEFG456HIJ789KLMNOPQRST0UVWXYZ";
          $longitudCadena=strlen($cadena);
          $pass = "";
          $longitudPass=8;

          for($i=1 ; $i<=$longitudPass ; $i++)
          {
              $pos=rand(0,$longitudCadena-1);
              $pass .= substr($cadena,$pos,1);
          }
        return $pass;
    }
}
?>
