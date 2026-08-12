<?php
    global $SessionUserId;
    /*Se obtienen los indices del los archivos a descargar*/
    $Params = 'data-params="' . Encrypt(json_encode($Params), $SessionRandom) . '"';
?>
<form class="form-horizontal form_validate" method="POST" name="Form_ReporteXMLs" id="Form_XMLs" action="" data-onsubmit="SearchXMLs();">
    <fieldset>
        <span class="clearfix"></span>
        <hr/>
        <div class="form-group row">
            <div class="col-md-2 text-left">
                <a id="BotonCopia404" class="btn btn-info " href="XMLs?<?= EncodeThis('Action=Restore&Tipo=' . $Tipo) ?>"><i class="fa fa-copy"></i>&nbsp; Mover los no encontrados para procesarlos</a>
            </div>
            <div class="col-md-2 text-left">
                <a id="BotonDel404" class="btn btn-danger delete " href="XMLs?<?= EncodeThis('Action=Eliminar&Folder=404&Tipo=' . $Tipo) ?>"><i class="fa fa-trash-alt"></i>&nbsp; Eliminar no Encontrados</a>
            </div>
            <div class="col-md-2 text-left form-inline">
                <a id="XMLsDelDuplicate" class="btn btn-warning delete" href="XMLs?<?= EncodeThis('Action=Eliminar&Folder=duplicate&Tipo=' . $Tipo) ?>"><i class="fa fa-trash-restore-alt"></i>&nbsp; Eliminar Duplicados</a>
            </div>
            <div class="col-md-2 text-left form-inline">
                <a id="BotonDelOld" class="btn btn-danger " href="XMLs?<?= EncodeThis('Action=Eliminar&Folder=Old&Tipo=' . $Tipo) ?>"><i class="fa fa-clock"></i>&nbsp; Eliminar Antiguos</a>
            </div>
            <div class="col-md-4 text-right">
                <div data-position="bottom" data-intro="Busqueda general" class="form-group">
                    <label for="Search-XMLs" class="sr-only">Busqueda:&nbsp; </label>
                    <div align="right" class="input-group">
                        <input type="text" autocomplete="off" placeholder="Buscar..." class="form-control DataTableFilter" id="CelaInputSearchXMLs" data-tablesearch="Table_XMLs">
                        <span title="Buscar..." class="input-group-btn">
					<a id="CelaBot_onBuscarXMLs" data-tablesearch="Table_XMLs" class="btn btn-default btn-filter">
						<i class="fa fa-search"></i>
					</a>
				</span>
                    </div>
                </div>
            </div>
            <?php
                /*
                 * <div class="group-validate">
                <div class="col-sm-3">
                    <label class="" for="focusedInput">N&uacute;mero de Parte: </label>
                    <div class="validate">
                        <input type="text" name="N_umeroDeParte" id="N_umeroDeParte" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="group-validate">
                <div class="col-sm-5">
                    <label class="" for="focusedInput">Descripci&oacute;n: </label>
                    <div class="validate">
                        <input type="text" name="Descripci_on" id="Descripci_on" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="group-validate">
                <div class="col-sm-4">
                    <label class="" for="focusedInput">Categor&iacute;a: </label>
                    <div class="validate">
                    <?php
                        $OpcCategoria['Name'] = 'Categor_ia';
                        $OpcCategoria['Class'] = 'form-control focused';
                        $ConsultaCategor_ia = ProductoQueryCombo('Name', 'TipoDeProducto IN (1, 2)');

                        print FillSelect($ConsultaCategor_ia, $OpcCategoria, 1);
                    ?>
                    </div>
                </div>
            </div>
            <div class="group-validate">
                <div class="col-sm-3">
                    <label class="" for="focusedInput"><br /></label>
                    <div class="validate">
                        <button id="GenerarReporte" class="btn btn-primary">
                            <i class="fa fa-rocket"></i>&nbsp; Ejecutar Filtro
                        </button>
                    </div>
                </div>
            </div>
            
            
            <div class="col-md-5 text-left">
                <br>
                <a class="btn btn-danger DataTablePrint" data-table="Table_XMLs" data-function_report="ReporteDeXMLs" data-file_name="ReporteDeXMLs" data-mime_type="application/pdf" data-config="XMLsReporteConfig" data-server_source="../Reporte/XMLs/Reporte.php" ><i class="fa fa-file-pdf-o"></i>&nbsp; Exportar a PDF</a>
                <a class="btn btn-success DataTablePrint" data-table="Table_XMLs" data-function_report="ReporteDeXMLs" data-file_name="ReporteDeXMLs" data-mime_type="application/vnd.ms-excel" data-config="XMLsReporteConfig" data-server_source="../Reporte/XMLs/Reporte.php" ><i class="fa fa-file-excel-o"></i>&nbsp; Exportar a XLS</a>
                <label style="display: inherit !important;">
                    <img width="38" height="22" alt="Para los elementos que est&aacute;n marcados:"
                         src="bootstrap/img/arrow_ltr.png" class="selectallarrow">
                    &nbsp; Para los filtros seleccionados
                </label>
            </div>*/ ?>
        </div>
    </fieldset>
</form>

<table id="Table_XMLs" class="table table-striped table-bordered  table-hover datatable"
       data-source="../XMLs/Reporte.php" data-function="XMLsLeer" data-form="XMLs" data-record_length="20" <?= $Params; ?>>
    <thead>
    <tr>
        <th width="1%" title="Seleccionar todo">
            <div class="text-center">
                #
            </div>
        </th>
        <th width="35%" class="sortable" ><div class="text-center"> Nombre del Archivo </div></th>
        <th width="50%" class="sortable" ><div class="text-center"> Status </div></th>
        <th width="14%" ><div class="text-center"> Archivo </div></th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>
