<form class="form-horizontal form_validate" method="post" name="CelaConfiguraci_on" enctype="multipart/form-data" id="CelaConfiguraci_on" action="<?= $FormAction; ?>">
	<fieldset>
		<div class="clearfix"></div>
		<hr />
<?php
	$LastCategory           = '';
	$QueryConfigurations    =   sprintf('SELECT
											 co1.id as idConfiguraci_on,
											 co1.Nombre as NombreConfiguraci_on,
											 co1.Code,
											 co1.Valor as ValorConfiguraci_on,
											 co1.Tipo as TipoConfiguraci_on,
											 co1.Referencia as Referencia,
											 co1.Class as Clase,
											 cio.id as idCategoria,
											 cio.NombreCategor_ia as NombreCategor_ia
										  FROM CelaConfiguraci_on co1
											  INNER JOIN CelaCategor_iaConfiguraci_on cio ON ( co1.Categor_ia = cio.id  )
									      WHERE
									          co1.id IN	(select Tupla
									                     from CelaPrivilegios
									                     where
									                        TuplaAcceso = %s and
									                        Origen = %s
									                    )
									      ORDER BY cio.id ASC, co1.id ASC;',
									GetSQLValueString($SessionGroupId, 'int'),
									GetSQLValueString(5, 'int')
								);
	$ResultConfigurations = $Connection -> query($QueryConfigurations);
	$cont = 0;
	$Conf = array();
	while($RecordConfigurations = $ResultConfigurations -> fetch_assoc()){
		if($LastCategory == '' || ($LastCategory != '' && $LastCategory != $RecordConfigurations['idCategoria'])){
			$cont++;
			$Conf[$cont]['Categoria'] = $RecordConfigurations['NombreCategor_ia'];
			$Conf[$cont]['id'] = $RecordConfigurations['idCategoria'];
		}

		$NameConfig = 'Element_' . $RecordConfigurations['idConfiguraci_on'];
		$Element = '';
		switch($RecordConfigurations['TipoConfiguraci_on']){
			case 'file':
				$Element = '<input type="'.$RecordConfigurations['TipoConfiguraci_on'].'" class="focused ' . $RecordConfigurations['Clase'] . '" name="' . $NameConfig . '" id="' . $NameConfig . '" value="" />';
				$Element .= '<input type="hidden" name="' . $NameConfig . '_ant" value="' . str_replace('"', '&quot;', $RecordConfigurations['ValorConfiguraci_on']) . '" />';
				break;
			case 'checkbox':
				$Element =  '<input class="checkbox focused ' . $RecordConfigurations['Clase'] . '" name="'.$NameConfig .'" id="'.$NameConfig.'" type="'.$RecordConfigurations['TipoConfiguraci_on'].'" '.($RecordConfigurations['ValorConfiguraci_on']=='1'?'checked="checked"':"").' value="1" />';
				break;
			case 'password':
				$Element =  '<input class="form-control focused ' . $RecordConfigurations['Clase'] . '" name="'.$NameConfig .'" id="'.$NameConfig.'" type="'.$RecordConfigurations['TipoConfiguraci_on'].'" value="" />';
				break;
			case 'select':
				$OpcConfiguracion['Name']   = $NameConfig;
				$OpcConfiguracion['Class']  = 'form-control ' . $RecordConfigurations['Clase'] . '';
				$Query =    sprintf('SELECT * FROM %s;',
					$RecordConfigurations['Referencia']
				);

				$Element =  SFillSelect($Query, $OpcConfiguracion, $RecordConfigurations['ValorConfiguraci_on']);
				break;
			case 'textarea':
				$Element =  '<textarea class="form-control focused ' . $RecordConfigurations['Clase'] . '" name="'.$NameConfig .'" id="'.$NameConfig.'" rows="8" >'.str_replace('"', '&quot;', $RecordConfigurations['ValorConfiguraci_on']) . '</textarea>';
				break;
			default:
				$Element =  '<input type="text" class="form-control focused ' . $RecordConfigurations['Clase'] . '" name="'.$NameConfig .'" id="'.$NameConfig.'" value="'.str_replace('"', '&quot;', $RecordConfigurations['ValorConfiguraci_on']).'" />';
				break;
		}

		$Conf[$cont]['conf'][] = array(
			'label' => '<label for="' . $RecordConfigurations['NombreConfiguraci_on'] . '" class="form-label col-md-12">' . (empty($RecordConfigurations['Code']) ? DecodeString($RecordConfigurations['NombreConfiguraci_on']):$RecordConfigurations['NombreConfiguraci_on']) . '</label>',
			'element' => $Element
		);
		$LastCategory = $RecordConfigurations['idCategoria'];
	}

	$InputTemplate = LoadContentPage('../CelaTemplate/CelaInputsTemplate.php');
	$TagsToReplace = array(
		'<!--#INPUTLABEL#-->',
		'<!--#INPUTELEMENT#-->'
	);

	$Tabs = '<ul class="nav nav-pills">';
	$Conten = '<div class="tab-content bg-light p-3 rounded-bottom ">';

	$cont = 0;
	foreach($Conf as $c){
		$Tabs .= '<li class="nav-item">
					<a href="#conf_' . $c['id'] . '" data-bs-toggle="tab" class="nav-link ' . ($cont == 0 ? 'active':'') . '">' . $c['Categoria'] . '</a>
				</li>';

		$Conten .= '<div class="tab-pane fade ' . ($cont == 0 ? 'active show':'') . '" id="conf_' . $c['id'] . '">';
		if(isset($c['conf']) && count($c['conf']) > 0){
			foreach($c['conf'] as $e){
				$ContentElement = array(
					$e['label'],
					$e['element']
				);
				$Conten .= ReplaceContentPage($TagsToReplace, $ContentElement, $InputTemplate);
			}
		}
		$Conten .= '</div>';
		$cont ++;
	}
	$Tabs .= '</ul>';
	$Conten .= '</div>';

	print $Tabs;
	print $Conten;
?>
		<div class="clearfix"></div>
		<hr />
		<input type="hidden" name="CelaConfiguraci_onUpdate" value="CelaConfiguraci_onUpdate"/>
		<div class="form-group last">
			<div class="offset-3 col-md-9">
				<button id="Guardar" type="submit" class="btn btn-primary Save" data-loading-text="Guardando..." disabled="disabled">
					<i class="fa fa-save"></i>&nbsp; Guardar
				</button>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<button type="reset" class="btn btn-default" onclick = "location.href='Escritorio'" >
					<i class="fa fa-undo"></i>&nbsp; Cancelar
				</button>
		<?php
			if(isset($Privileges['Crear']) && $Privileges['Crear']==1){
		?>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<a class="btn btn-info" href="<?= $FormAction . '?' . EncodeThis('Action=Leer'); ?>">
					<i class="fa fa-eye"></i>&nbsp; Vista de Desarrollador
				</a>
		<?php
			}
		?>
			</div>
		</div>
	</fieldset>
</form>