<?php 
function numeros_a_letras($numero, $moneda = '', $subfijo = 'BOLIVIANOS.')
{
    $xarray = array(
        0 => 'Cero'
        , 1 => 'UN', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'
        , 'DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISEIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE'
        , 'VEINTI', 30 => 'TREINTA', 40 => 'CUARENTA', 50 => 'CINCUENTA'
        , 60 => 'SESENTA', 70 => 'SETENTA', 80 => 'OCHENTA', 90 => 'NOVENTA'
        , 100 => 'CIENTO', 200 => 'DOSCIENTOS', 300 => 'TRESCIENTOS', 400 => 'CUATROCIENTOS', 500 => 'QUINIENTOS'
        , 600 => 'SEISCIENTOS', 700 => 'SETECIENTOS', 800 => 'OCHOCIENTOS', 900 => 'NOVECIENTOS'
    );

    $numero = trim($numero);
    $xpos_punto = strpos($numero, '.');
    $xaux_int = $numero;
    $xdecimales = '00';
    if (!($xpos_punto === false)) {
        if ($xpos_punto == 0) {
            $numero = '0' . $numero;
            $xpos_punto = strpos($numero, '.');
        }
        $xaux_int = substr($numero, 0, $xpos_punto);
        $xdecimales = substr($numero . '00', $xpos_punto + 1, 2);
    }

    $XAUX = str_pad($xaux_int, 18, ' ', STR_PAD_LEFT);
    $xcadena = '';
    for ($xz = 0; $xz < 3; $xz++) {
        $xaux = substr($XAUX, $xz * 6, 6);
        $xi = 0;
        $xlimite = 6;
        $xexit = true;
        while ($xexit) {
            if ($xi == $xlimite) {
                break;
            }
            $x3digitos = ($xlimite - $xi) * -1;
            $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtenemos las centenas (los tres dígitos)
            for ($xy = 1; $xy < 4; $xy++) {
                switch ($xy) {
                    case 1: // verificamos las centenas
                        $key = (int) substr($xaux, 0, 3);
                        if (100 > $key) {

                        } else {
                            if (TRUE === array_key_exists($key, $xarray)) {
                                $xseek = $xarray[$key];
                                $xsub = subfijo($xaux);
                                if (100 == $key) {
                                    $xcadena = ' ' . $xcadena . ' CIEN ' . $xsub;
                                } else {
                                    $xcadena = ' ' . $xcadena . ' ' . $xseek . ' ' . $xsub;
                                }
                                $xy = 3;
                            } else {
                                $key = (int) substr($xaux, 0, 1) * 100;
                                $xseek = $xarray[$key];
                                $xcadena = ' ' . $xcadena . ' ' . $xseek;
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 0, 3) < 100)
                        break;
                    case 2: // verificamos las decenas
                        $key = (int) substr($xaux, 1, 2);
                        if (10 > $key) {

                        } else {
                            if (TRUE === array_key_exists($key, $xarray)) {
                                $xseek = $xarray[$key];
                                $xsub = subfijo($xaux);
                                if (20 == $key) {
                                    $xcadena = ' ' . $xcadena . ' VEINTE ' . $xsub;
                                } else {
                                    $xcadena = ' ' . $xcadena . ' ' . $xseek . ' ' . $xsub;
                                }
                                $xy = 3;
                            } else {
                                $key = (int) substr($xaux, 1, 1) * 10;
                                $xseek = $xarray[$key];
                                if (20 == $key)
                                    $xcadena = ' ' . $xcadena . ' ' . $xseek;
                                else
                                    $xcadena = ' ' . $xcadena . ' ' . $xseek . ' Y ';
                            } // END IF ($xseek)
                        } // END IF (substr($xaux, 1, 2) < 10)
                        break;
                    case 3: // verificamos las unidades
                        $key = (int) substr($xaux, 2, 1);
                        if (1 > $key) {

                        } else {
                            $xseek = $xarray[$key];
                            $xsub = subfijo($xaux);
                            $xcadena = ' ' . $xcadena . ' ' . $xseek . ' ' . $xsub;
                        } // END IF (substr($xaux, 2, 1) < 1)
                        break;
                } // END SWITCH
            } // END FOR
            $xi = $xi + 3;
        } // END

        if ('ILLON' == substr(trim($xcadena), -5, 5)) {
            $xcadena.= ' DE';
        }
        if ('ILLONES' == substr(trim($xcadena), -7, 7)) {
            $xcadena.= ' DE';
        }
// limpiar leyenda
        if ('' != trim($xaux)) {
            switch ($xz) {
                case 0:
                    if ('1' == trim(substr($XAUX, $xz * 6, 6))) {
                        $xcadena.= 'UN BILLON ';
                    } else {
                        $xcadena.= ' BILLONES ';
                    }
                    break;
                case 1:
                    if ('1' == trim(substr($XAUX, $xz * 6, 6))) {
                        $xcadena.= 'UN MILLON ';
                    } else {
                        $xcadena.= ' MILLONES ';
                    }
                    break;
                case 2:
                    if (1 > $numero) {
                        $xcadena = "CERO {$moneda} {$xdecimales}/100 {$subfijo}";
                    }
                    if ($numero >= 1 && $numero < 2) {
                        $xcadena = "UN {$moneda} {$xdecimales}/100 {$subfijo}";
                    }
                    if ($numero >= 2) {
                        $xcadena.= " {$moneda} {$xdecimales}/100 {$subfijo}"; 
                    }
                    break;
            } // end switch
        } // ENDIF (trim($xaux) != "")
        $xcadena = str_replace('VEINTI ', 'VEINTI', $xcadena); 
        $xcadena = str_replace('  ', ' ', $xcadena); 
        $xcadena = str_replace('UN UN', 'UN', $xcadena);
        $xcadena = str_replace('  ', ' ', $xcadena);
        $xcadena = str_replace('BILLON DE MILLONES', 'BILLON DE', $xcadena);
        $xcadena = str_replace('BILLONES DE MILLONES', 'BILLONES DE', $xcadena);
        $xcadena = str_replace('DE UN', 'UN', $xcadena); 
    } // ENDFOR ($xz)
    return trim($xcadena);
}
