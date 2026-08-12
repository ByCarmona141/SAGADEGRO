<?php
    function ReporteComparativo($Params = false){
        global $Privileges;
        global $SessionUserId;
    
        if($Params !== false && is_array($Params)){
            /*Se extraen las variables si vienen en un arreglo*/
            extract($Params, EXTR_OVERWRITE);
        }
    
        $ServerQuery = array(
            'Table'     => array(
                'TableName'  => 'Nomina',
                'Alias' => 'XMLSucursal'
            ),
            'Index'     => array(
                'IndexName' => 'File',
                'Alias' => ''
            ),
            'Columns'   =>  array(
                0 => array(
                    'Type'      => 1,
                    'ColumName' => '',
                    'Alias'     => '',
                    'Extra'     => 'ActionsReport',
                    'Render'    => ''
                ),
                1 => array(
                    'Type'      => 2,
                    'ColumName' => '(select NombreComercial from Cliente where Cliente.id = XMLSucursal.Cliente)',
                    'Alias'     => 'Cliente',
                    'Extra'     => '',
                    'Render'    => ''
                ),
                2 => array(
                    'Type'      => 2,
                    'ColumName' => '(select RFC from RFCCliente where RFCCliente.id = XMLSucursal.RFC)',
                    'Alias'     => 'RFC',
                    'Extra'     => '',
                    'Render'    => ''
                ),
                3 => array(
                    'Type'      => 2,
                    'ColumName' => '(select CONCAT_WS(\' \', NombreCompleto, NoEmpleado, RFC) from Empleado where Empleado.id = XMLSucursal.Empleado)',
                    'Alias'     => 'Empleado',
                    'Extra'     => '',
                    'Render'    => ''
                ),
                4 => array(
                    'Type'      => 2,
                    'ColumName' => '(select Nombre from CelaRepositorio where CelaRepositorio.id = XMLSucursal.File)',
                    'Alias'     => 'NombreRepo',
                    'Extra'     => '',
                    'Render'    => ''
                ),
                5 => array(
                    'Type'      => 2,
                    'ColumName' => '(DATE_FORMAT(XMLSucursal.FechaInicialPago, "%Y-%m-%d"))',
                    'Alias'     => 'FechaInicio',
                    'Extra'     => '',
                    'Render'    => ''
                ),
                6 => array(
                    'Type'      => 2,
                    'ColumName' => '(DATE_FORMAT(XMLSucursal.FechaFinalPago, "%Y-%m-%d"))',
                    'Alias'     => 'FechaFin',
                    'Extra'     => '',
                    'Render'    => ''
                ),
                7 => array(
                    'Type'      => 2,
                    'ColumName' => '(select Descripci_on from PeriodicidadPago where PeriodicidadPago.D_ias = XMLSucursal.DiasDelPeriodo)',
                    'Alias'     => 'Tipo',
                    'Extra'     => '',
                    'Render'    => ''
                ),
                8 => array(
                    'Type'      => 2,
                    'ColumName' => 'File',
                    'Alias'     => '',
                    'Extra'     => '',
                    'Render'    => 'DownloadPreviewFileWhitpdf'
                )
            ),
            'Condition'     => '(File != 0 AND File IS NOT NULL) AND (Sucursal IN (select Sucursal from UsuarioSucursal where UsuarioSucursal.Usuario = ' . $SessionUserId . '))',
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
