<div class="row col-md-12">
    <div class="col-md-2 text-left">
        <?php
            if (isset($Privileges['Crear']) && $Privileges['Crear'] == 1) {
                ?>
                <a href="<?= $Table.'?' . EncodeThis(  'Action=Crear' . (isset($Vars) && $Vars != '' ? '&' . $Vars:'')); ?>" title="Agregar" class="btn btn btn-success" data-position="top" data-intro="Insertar nuevo <?= $Table; ?>" id="<?= $Table; ?>Bot_onCrear">
			<span>
				<i class="fa fa-plus"></i>&nbsp; Agregar
			</span>
                </a>
                <?php
            }
        ?>
    </div>
    <div class="col-md-3 text-left form-inline">
        <?php
            $Label = false;
            if(isset($Privileges['Actualizar']) && $Privileges['Actualizar'] == 1){
                ?>
                <a id="<?= $Table; ?>Bot_onActualizar" disabled="disabled" href="#" title="Editar seleccionados" class="btn btn btn-warning update<?= $Table; ?>" data-position="bottom" data-intro="Modifica los elementos seleccionados">
			<span>
				<i class="fa fa-pencil-alt"></i>&nbsp; Editar
			</span>
                </a>
                <?php
                $Label=true;
            }
            if(isset($Privileges['Eliminar']) && $Privileges['Eliminar'] == 1){
                ?>
                <a id="<?= $Table; ?>Bot_onEliminar" disabled="disabled" class="btn btn btn-danger delete<?= $Table; ?>" href="#" title="Eliminar seleccionados" data-position="right" data-intro="Elimina todos los elementos seleccionados">
			<span>
				<i class="fa fa-trash-alt"></i>&nbsp; Eliminar
			</span>
                </a>
                <?php
                $Label = true;
            }
            if($Label == true){
                ?>
                <label style="display: inherit !important;">
                    <img width="38" height="22" alt="Para los elementos que est&aacute;n marcados:"
                         src="bootstrap/img/arrow_ltr.png" class="selectallarrow">
                    &nbsp; Para los datos seleccionados
                </label>
                <?php
            }
        ?>
    </div>
    <div class="col-md-3 text-left form-inline">
        <a href="<?= $Table.'?' . EncodeThis(  'Action=Procesos' . (isset($Vars) && $Vars != '' ? '&' . $Vars:'')); ?>" title="Ver Historial de Procesos" class="btn btn btn-info" data-position="top" data-intro="Ver historial de proceso" id="<?= $Table; ?>Bot_onHistorial">
			<span>
				<i class="fa fa-history"></i>&nbsp; Historial
			</span>
        </a>
    </div>
    <div class="col-md-4 text-right">
        <div data-position="bottom" data-intro="Busqueda general" class="form-group">
            <label for="Search-<?= $Table; ?>" class="sr-only">Busqueda:&nbsp; </label>
            <div align="right" class="input-group">
                <input type="text" autocomplete="off" placeholder="Buscar..." class="form-control DataTableFilter" id="CelaInputSearch<?= $Table; ?>" data-tablesearch="Table_<?= $Table; ?>">
                <span title="Buscar..." class="input-group-btn">
					<a id="CelaBot_onBuscar<?= $Table; ?>" data-tablesearch="Table_<?= $Table; ?>" class="btn btn-default btn-filter">
						<i class="fa fa-search"></i>
					</a>
				</span>
            </div>
        </div>
    </div>
</div>
<?php
    if(isset($Privileges['Eliminar']) && $Privileges['Eliminar'] == 1){
        ?>
        <!-- #modal-dialog -->
        <div id="<?= $Table; ?>ModalEliminar" tabindex="100" class="modal fade" aria-hidden="false" aria-labelledby="myModalLabel" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Confirmaci&oacute;n de Acci&oacute;n</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body" id="<?= $Table; ?>ModalEliminarBody">
                        <span>&iquest;Realmente desea eliminar el/los elemento(s) seleccionado(s)?</span>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-white" id="<?= $Table; ?>Bot_onEliminarCancelar" data-bs-dismiss="modal">
                            <i class="fa fa-undo"></i>&nbsp; Cancelar
                        </a>
                        <a class="btn btn-danger" id="<?= $Table; ?>Bot_onEliminarAceptar" href="">
                            <i class="fa fa-trash-alt"></i>&nbsp; Eliminar
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
?>
<div class="row">
	<div class="col-md-12">
		<table id="Table_CelaProceso" class="table table-striped table-bordered  table-hover datatable" data-source="<?= $ServerSource; ?>" data-function="<?= $ServerFunction; ?>" data-form="<?= $RouteForm; ?>">
			<thead>
			<tr>
				<th width="1%" title="Seleccionar todo">
					<div class="text-center">
						<label>
							<input  type="checkbox" id="All<?= $Table; ?>" data-intro="Selecciona todos los registros de esta p&aacute;gina" data-position="bottom"/>
						</label>
					</div>
				</th>
				<th class="sortable" width="14%"><div class="text-center"> Nombre </div></th>
				<th class="sortable" width="20%"><div class="text-center"> Script </div></th>
				<th class="sortable" width="15%"><div class="text-center"> Parametros </div></th>
				<th class="sortable" width="10%"><div class="text-center"> Recurrente </div></th>
				<th class="sortable" width="10%"><div class="text-center"> Periodicidad </div></th>
				<th class="sortable" width="15%"><div class="text-center"> Fecha inicio </div></th>
				<th class="sortable" width="15%"><div class="text-center"> Fecha termino </div></th>
			</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
