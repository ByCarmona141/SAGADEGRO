<?php
function CelaAccesoLeer(){
	global $Privileges;
	$ServerQuery = array(
		'Table'     => array(
			'TableName'  => 'CelaAcceso',
			'Alias' => ''
		),
		'Index'     => array(
			'IndexName' => 'id',
			'Alias' => ''
		),
		'Columns'   =>  array(
			0 => array(
				'Type'      => 2,
				'ColumName' => 'Fecha',
				'Alias'     => '',
				'Extra'     => '',
				'Render'    => ''
			),
			1 => array(
				'Type'      => 2,
				'ColumName' => '(select NombreCompleto from CelaUsuario where CelaUsuario.id = CelaAcceso.Usuario)',
				'Alias'     => 'Usuario',
				'Extra'     => '',
				'Render'    => ''
			),
			2 => array(
				'Type'      => 2,
				'ColumName' => 'Origen',
				'Alias'     => '',
				'Extra'     => '',
				'Render'    => ''
			),
			3 => array(
				'Type'      => 2,
				'ColumName' => 'Tupla',
				'Alias'     => '',
				'Extra'     => '',
				'Render'    => ''
			),
			4 => array(
				'Type'      => 2,
				'ColumName' => ' ( select Nombre from  CelaAcci_on where CelaAcci_on.id = CelaAcceso.Acci_on)',
				'Alias'     => 'Acci_on',
				'Extra'     => '',
				'Render'    => ''
			),
			5 => array(
				'Type'      => 1,
				'ColumName' => '',
				'Alias'     => '',
				'Extra'     => 'ShowRecord',
				'Render'    => ''
			)
		),
		'Condition'     => '',
		'Group'         => '',
		'Order'         => ' Fecha DESC ',
		'RenderRow'     => '',
		'Privileges'    => $Privileges,
		'Debug'         => 0,
		'IncludeExtras' => 0
	);

	return $ServerQuery;
}
	function CelaAccesoComboQuery($Field){
		$Query =    sprintf('SELECT DISTINCT %s, %s FROM CelaAcceso ORDER BY %s ASC',
						GetSQLValueString($Field, 'SQL'),
						GetSQLValueString($Field, 'SQL'),
						GetSQLValueString($Field, 'SQL')
					);

		return $Query;
	}

	function CelaAccesoReporteConfig($Params = array()){
		$ReportConfig = array(
			'Header'        => '<tr>
				<th width="10%">
					<div align="center">
						Fecha
					</div>
				</th>
				<th width="30%">
					<div align="center">
						Usuario
					</div>
				</th>
				<th width="30%">
					<div align="center">
						Origen
					</div>
				</th>
				<th width="10%">
					<div align="center">
						id Registro
					</div>
				</th>
				<th  width="20%">
					<div align="center">
						Acci&oacute;n
					</div>
				</th>
			</tr>',
			'Footer'        => '<tr>
				<td colspan="5">
					<div style="" >
						<div style="float: left;" align="left"> <span class="encabezado-lg">TOTAL DE REGISTROS: ' . $Params['TotalRecords'] . '</span></div>
						<div style="float: right;" align="right"><span class="encabezado-lg">FECHA: ' . date('d/m/Y') . '</span></div>
					</div>
				</td>
			</tr>',
			'ReportTitle'   => 'Listado de Accesos.'
		);

		return $ReportConfig;
	}
?>