<?php
	/**

		* Fecha: 21/11/2013
		* Descripci�n: Libreria de variables utilizadar en DataTableServer.php.
		* Comentarios: En este archivo se define el estilo que utilizar� una fila segun criterios definidos.
		*
		* $aRow=Fila actual
		* $sIndexColumn=Indice del registro (ID)
		* $aColumns[$i]=Valor de la columna.
	**/

	if($RendeRow == 'Status'){
		$Rows['Status'] = ($Record['Status'] == '1' ? 'success':($Record['Status'] == '0' ? 'warning':''));
	}

	if($RendeRow == 'Finaly'){
		$Rows['Finaly'] = (!empty($Record['FechaRealizado']) ? 'success':'');
	}

	if($RendeRow == 'Acotaci_on'){
		$Rows['Acotaci_on'] = $Record['Acotaci_on'];
	}
	
    if($RendeRow == 'StatusNomina'){
        $Rows['StatusNomina'] = '';
        if($Record['Incapacidades'] != '' && $Record['DiasDelPeriodo'] != '' &&
            $Record['Ausentismos']  != '' &&
            $Record['TotalDeTurnos'] != '' &&
            $Record['NetoAPagar'] != ''
        ){
            if(
                $Record['Incapacidades'] == $Record['DiasDelPeriodo'] &&
                $Record['NetoAPagar'] == 0
            ){
                $Rows['StatusNomina'] = 'table-danger';
            }elseif(
                $Record['Ausentismos'] == $Record['DiasDelPeriodo'] &&
                $Record['NetoAPagar'] == 0
            ){
                $Rows['StatusNomina'] = 'table-danger';
            }elseif(
                $Record['TotalDeTurnos'] == $Record['DiasDelPeriodo'] &&
                $Record['NetoAPagar'] == 0
            ){
                $Rows['StatusNomina'] = 'table-success';
            }elseif(
                $Record['TotalDeTurnos'] == $Record['DiasDelPeriodo'] &&
                $Record['NetoAPagar'] == 1
            ){
                $Rows['StatusNomina'] = 'table-success';
            }
        }
    }
    
    if($RendeRow == 'ResultadoRow'){
        $Rows['ResultadoRow'] = '';
        
        if($Record['PCompleto'] <= 50){
            $Rows['ResultadoRow'] = 'table-danger';
        }elseif($Record['PCompleto'] > 50 && $Record['PCompleto'] <= 80){
            $Rows['ResultadoRow'] = 'table-warning';
        }elseif($Record['PCompleto'] > 80 && $Record['PCompleto'] < 100){
            $Rows['ResultadoRow'] = 'table-info';
        }elseif($Record['PCompleto'] == 100){
            $Rows['ResultadoRow'] = 'table-success';
        }
    }
?>
