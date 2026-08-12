<?php
    function XMLsLeer($Params = false){
        global $Privileges;
        global $SessionUserId;
    
        if($Params !== false && is_array($Params)){
            /*Se extraen las variables si vienen en un arreglo*/
            extract($Params, EXTR_OVERWRITE);
        }
    
        $Condition = '';
        
        if(isset($Params['Tipo']) && $Params['Tipo'] != ''){
            $Condition .= 'Tipo = "' . $Params['Tipo'] . '" AND ';
        }
        
        $Condition .= ' 1=1 ';
        
        $ServerQuery = array(
            'Table'     => array(
                'TableName'  => 'XMLs',
                'Alias' => ''
            ),
            'Index'     => array(
                'IndexName' => 'id',
                'Alias' => ''
            ),
            'Columns'   =>  array(
                0 => array(
                    'Type'      => 2,
                    'ColumName' => 'id',
                    'Alias'     => '',
                    'Extra'     => '',
                    'Render'    => ''
                ),
                1 => array(
                    'Type'      => 2,
                    'ColumName' => '(SUBSTRING_INDEX(Nombre, \'/\', -1))',
                    'Alias'     => 'Archivo',
                    'Extra'     => '',
                    'Render'    => ''
                ),
                2 => array(
                    'Type'      => 2,
                    'ColumName' => '(select Descripci_on FROM StatusProcesaXMLs where StatusProcesaXMLs.id = XMLs.Status)',
                    'Alias'     => 'Status',
                    'Extra'     => '',
                    'Render'    => ''
                ),
                3 => array(
                    'Type'      => 1,
                    'ColumName' => '',
                    'Alias'     => '',
                    'Extra'     => 'DownloadXML',
                    'Render'    => ''
                )
            ),
            'Condition'     => $Condition,
            'Group'         => '',
            'Order'         => ' id ASC ',
            'RenderRow'     => '',
            'Privileges'    => $Privileges,
            'Debug'         => 1
        );
    
        return $ServerQuery;
    }
    
    function NominaReporteConfig($Params = array()){
        $ReportConfig = array(
            'Header'        => '<tr>
				<th width="20%" class="sortable" ><div class="text-center"> Encabezado RH </div></th>
        <th width="20%" class="sortable" ><div class="text-center"> Total RH </div></th>
        <th width="20%" class="sortable" ><div class="text-center"> Encabezado PC </div></th>
        <th width="20%" class="sortable" ><div class="text-center"> Total PC </div></th>
        <th width="20%" class="sortable" ><div class="text-center"> Diferencia </div></th>
			</tr>',
            'Footer'        => '<tr>
				<td colspan="5">
					<div style="" >
						<div style="float: left;" align="left"> <span class="encabezado-lg">TOTAL DE REGISTROS: ' . $Params['TotalRecords'] . '</span></div>
						<div style="float: right;" align="right"><span class="encabezado-lg">FECHA: ' . date('d/m/Y') . '</span></div>
					</div>
				</td>
			</tr>',
            'ReportTitle'   => 'Comparativo de Nomina.'
        );
        
        return $ReportConfig;
    }
?>
