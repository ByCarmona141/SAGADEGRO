<?php
    global $SessionUserId;
    /*Se obtienen los indices del los archivos a descargar*/
    
?>
<form class="form-horizontal form_validate" method="POST" name="Form_ReporteXMLSucursal" id="Form_XMLSucursal" action="" data-onsubmit="SearchXMLSucursal();">
    <fieldset>
        <span class="clearfix"></span>
        <hr/>
        <div class="form-group row">
            <div class="col-md-7 text-left">
                <a id="XMLSucursalBotonDescargar" class="btn btn-info " ><i class="fa fa-download"></i>&nbsp; Descargar Seleccionados</a>
                <a id="BotonDescargarAll" class="btn btn-primary " href="Nomina?<?= EncodeThis('All=1&Action=Descargar') ?>"><i class="fa fa-download"></i>&nbsp; Descargar Todo</a>
                <a id="BotonDescargarFiltro" class="btn btn-success " href=""><i class="fa fa-download"></i>&nbsp; Descargar Filtrados</a>
            </div>
            <div class="col-md-1 text-left form-inline"></div>
            <div class="col-md-4 text-right">
                <div data-position="bottom" data-intro="Busqueda general" class="form-group">
                    <label for="Search-XMLSucursal" class="sr-only">Busqueda:&nbsp; </label>
                    <div align="right" class="input-group">
                        <input type="text" autocomplete="off" placeholder="Buscar..." class="form-control DataTableFilter" id="CelaInputSearchXMLSucursal" data-tablesearch="Table_XMLSucursal">
                        <span title="Buscar..." class="input-group-btn">
					<a id="CelaBot_onBuscarXMLSucursal" data-tablesearch="Table_XMLSucursal" class="btn btn-default btn-filter">
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
                <a class="btn btn-danger DataTablePrint" data-table="Table_XMLSucursal" data-function_report="ReporteDeXMLSucursal" data-file_name="ReporteDeXMLSucursal" data-mime_type="application/pdf" data-config="XMLSucursalReporteConfig" data-server_source="../Reporte/XMLSucursal/Reporte.php" ><i class="fa fa-file-pdf-o"></i>&nbsp; Exportar a PDF</a>
                <a class="btn btn-success DataTablePrint" data-table="Table_XMLSucursal" data-function_report="ReporteDeXMLSucursal" data-file_name="ReporteDeXMLSucursal" data-mime_type="application/vnd.ms-excel" data-config="XMLSucursalReporteConfig" data-server_source="../Reporte/XMLSucursal/Reporte.php" ><i class="fa fa-file-excel-o"></i>&nbsp; Exportar a XLS</a>
                <label style="display: inherit !important;">
                    <img width="38" height="22" alt="Para los elementos que est&aacute;n marcados:"
                         src="bootstrap/img/arrow_ltr.png" class="selectallarrow">
                    &nbsp; Para los filtros seleccionados
                </label>
            </div>*/ ?>
        </div>
    </fieldset>
</form>

<table id="Table_XMLSucursal" class="table table-striped table-bordered  table-hover datatable"
       data-source="../Reporte/XMLSucursal/Reporte.php" data-function="ReporteComparativo" data-form="XMLSucursal" data-record_length="20">
    <thead>
    <tr>
        <th width="1%" title="Seleccionar todo">
            <div class="text-center">
                <label>
                    <input  type="checkbox" id="AllXMLSucursal" data-intro="Selecciona todos los registros de esta p&aacute;gina" data-position="bottom"/>
                </label>
            </div>
        </th>
        <th width="10%" class="sortable" ><div class="text-center"> Cliente </div></th>
        <th width="10%" class="sortable" ><div class="text-center"> RFC </div></th>
        <th width="15%" class="sortable" ><div class="text-center"> Empleado </div></th>
        <th width="10%" class="sortable" ><div class="text-center"> XML </div></th>
        <th width="10%" class="sortable" ><div class="text-center"> <input class="form-control input-sm" type="date" name="PeriodoInicio" id="PeriodoInicio" placeholder="Inicio del Periodo"/> </div></th>
        <th width="10%" class="sortable" ><div class="text-center"> <input class="form-control input-sm" type="date" name="PeriodoFin" id="PeriodoFin" placeholder="Fin del Periodo"/> </div></th>
        <th width="10%" class="sortable" ><div class="text-center"> Tipo N&oacute;mina </div></th>
        <th width="19%" class="sortable" ><div class="text-center"> Descargar </div></th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>
