<?php
	if(isset($idMessage) && $idMessage != '' && isset($RR) && $RR != ''){
		/*Se obtienen los datos del mensaje*/
		$DataMessage = CelaMessageGetData($idMessage);
		if($DataMessage['Status'] == 'OK'){
			$Message = $DataMessage['Data'][0];
			/*Se busca si el mesaje tiene archivo*/
			$FilesExists =  GetValue(
								sprintf('SELECT 1 as Exist FROM CelaRepositorio WHERE Origen = %s AND Tupla = %s;',
									GetSQLValueString('cometchat', 'varchar'),
									GetSQLValueString($idMessage, 'int')
								),
								'Exist'
							);
		}
	}
?>
<form class="form-horizontal form_validate" method="POST" name="Form_CelaMessage" id="Form_CelaMessage" action="<?= $FormAction . '?' . EncodeThis('Action=Crear'); ?>" enctype="multipart/form-data">
	<fieldset>
		<span class="clearfix"></span>
		<hr/>
		<div class="form-group">
			<div class="group-validate">
				<div class="col-md-12">
					<div class="col-xs-12 validate">
				<?php
					$OpcTo['Name']     = 'to[]';
					$OpcTo['Class']    = 'form-control  e_requerido no-custom';
					$OpcTo['Custom']   =  'multiple="" data-placeholder="To:" style="width: 100%;" ';

					$Group  = CelaRolObtenGrupos($SessionGroupId, 'asc');

					$Query = CelaUsuarioComboQuery(false, $Group);

					if(isset($Message) && isset($RR) && $RR == 1){
						/*Se precargan los datos para responder*/
						print SFillSelect($Query, $OpcTo, $Message['from']);
					}else{
						print FillSelect($Query, $OpcTo);
					}
				?>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="group-validate">
				<div class="col-md-12">
					<div class="col-xs-12 validate">
						<input class="form-control e_requerido" placeholder="Subject:" id="subject" name="subject" value="<?= (isset($Message) ? (isset($RR) && $RR == 1 ? 'Re: ':'Fwd: ') . $Message['subject']:'') ?>">
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="group">
				<div class="col-md-12">
					<div class="col-xs-12">
						<textarea id="message" name="message" class="form-control" style="height: 300px">
							<?= (isset($Message) ? $Message['message']:'') ?>
						</textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="group-validate">
				<div class="col-md-12">
					<div class="col-xs-12 validate">
						<div class="btn btn-default btn-file">
							<i class="fa fa-paperclip"></i>&nbsp; <span data-localize="mailbox.compose.ATTACHMENT">Adjuntar</span>
							<input class="no-quit" type="file" name="Archivo[]" id="Archivo" multiple="multiple">
						</div>

				<?php
					/*Se insertan los archivos originales del mensaje*/
					if(isset($Message) && $FilesExists == 1){
				?>
						<div class="btn-group" data-toggle="buttons">
							<label class="btn btn-default" title="Adjuntar archivos originales del mensaje">
								<input type="checkbox" id="AddFiles" name="AddFiles" value="1" /><i class="fa fa-clipboard"></i>
							</label>
						</div>
				<?php
					}
				?>

						<p class="help-block" data-localize="mailbox.compose.HELPFILE">
							Puedes seleccionar varios archivos presionando la tecla ctrl y seleccionando el archivo requerido
						</p>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" name="CelaMessageInsert" value="CelaMessageInsert"/>
<?php
	if(isset($Message)){
		print '<input type="hidden" name="OriginalMessage" value="' . $Message['id'] . '"/>';
	}
?>
		<span class="clearfix"></span>
		<hr/>
		<div class="form-group">
			<div class="col-md-offset-9 col-md-3 col-sm-offset-9 col-sm-3 col-xs-offset-9 col-md-3">
				<button type="reset" class="btn btn-default" onclick="location.href='CelaMessage'">
					<i class="fa fa-times"></i> <span data-localize="mailbox.compose.DISCARD">Cancelar</span>
				</button>
				<button type="submit" id="Guardar<?= $FormAction;?>" class="btn btn-primary Save" data-loading-text="Enviando...">
					<i class="fa fa-envelope-o"></i> <span data-localize="mailbox.compose.SEND">Enviar</span>
				</button>
			</div>
		</div>
	</fieldset>
</form>